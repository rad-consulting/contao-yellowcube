<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */
namespace RAD\YellowCube\Backend;

use Contao\Backend;
use Contao\DataContainer;
use Isotope\Model\ProductType;
use RAD\Event\EventDispatcher;
use RAD\Fulfillment\Model\Fulfillment;
use RAD\Fulfillment\Model\MasterData;
use RAD\Fulfillment\Model\SupplierOrder;
use RAD\YellowCube\Model\Product\YellowCube;

/**
 * Class Panel
 */
class Panel extends Backend
{
    /**
     * @param DataContainer $dc
     * @return void
     */
    public function onSubmit(DataContainer $dc)
    {
        if ($dc->activeRecord) {
            $class = $GLOBALS['TL_MODELS'][$dc->table];
            $model = forward_static_call(array($class, 'findByPk'), $dc->activeRecord->id);

            if ($model instanceof YellowCube && $model->doExport()) {
                $type = ProductType::findByPk($model->type);

                if ('yellowcube' == $type->class) {
                    EventDispatcher::getInstance()->dispatch('yellowcube.sendProduct', $model);
                }

                return;
            }

            if ($model instanceof SupplierOrder && $model->doExport() && !$model->isExported()) {
                EventDispatcher::getInstance()->dispatch('yellowcube.sendSupplierOrder', $model);

                return;
            }

            if ($model instanceof Fulfillment && $model->status == $model::PENDING) {
                EventDispatcher::getInstance()->dispatch('yellowcube.sendFulfillment', $model);

                return;
            }
        }
    }
}
