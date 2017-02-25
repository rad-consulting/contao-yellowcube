<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */
namespace RAD\YellowCube\Soap\WBL;

use Isotope\Model\Product;
use RAD\Fulfillment\Model\SupplierOrderModel as Model;
use RAD\YellowCube\Config;
use RAD\YellowCube\Model\Product\YellowCubeProduct;

/**
 * Class Order
 *
 * @see https://service.swisspost.ch/apache/yellowcube/YellowCube_WBL_REQUEST_SupplierOrders.xsd
 */
class Order
{
    /**
     * @var
     */
    protected $SupplierOrderHeader;

    /**
     * @var array
     */
    protected $SupplierOrderPositions = array();

    /**
     * @param Model  $model
     * @param Config $config
     * @return static
     */
    public static function factory(Model $model, Config $config)
    {
        $instance = new static();
        $instance->SupplierOrderHeader = Header::factory($model, $config);

        foreach ($model->getPositions() as $position) {
            $product = YellowCubeProduct::findByPk($position['product']);

            if ($product instanceof YellowCubeProduct) {
                $instance->addPosition(Position::factory($product, $position['quantity'], $config));
            }
        }

        return $instance;
    }

    /**
     * @param Position $position
     * @return $this
     */
    public function addPosition(Position $position)
    {
        $this->SupplierOrderPositions[] = $position->setPosNo(count($this->SupplierOrderPositions) + 1);

        return $this;
    }
}
