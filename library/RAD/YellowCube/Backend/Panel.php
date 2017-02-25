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
use Contao\System;
use Isotope\Model\Product;
use Isotope\Model\ProductType;
use RAD\Event\EventDispatcher;
use RAD\Fulfillment\Model\SupplierOrderModel as SupplierOrder;
use RAD\YellowCube\Model\Product\YellowCubeProduct;

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

            if ($model instanceof Product) {
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
        }
    }
}
