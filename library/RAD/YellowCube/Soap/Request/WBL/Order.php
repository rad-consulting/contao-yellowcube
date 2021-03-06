<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 */
namespace RAD\YellowCube\Soap\Request\WBL;

use RAD\Fulfillment\Model\SupplierOrder as Model;
use RAD\YellowCube\Config;
use RAD\YellowCube\Model\Product\YellowCube;

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
            $product = YellowCube::findByPk($position['product']);

            if ($product instanceof YellowCube) {
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
