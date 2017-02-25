<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */
namespace RAD\YellowCube\Soap\Request\WBL;

use RAD\Fulfillment\Model\SupplierOrderModel as Model;
use RAD\YellowCube\Config;

/**
 * Class Header
 */
class Header
{
    /**
     * @var string
     */
    protected $Plant;

    /**
     * @var int
     */
    protected $SupplierNo;

    /**
     * @var int
     */
    protected $SupplierOrderNo;

    /**
     * @var int
     */
    protected $SupplierOrderDate;

    /**
     * @var int
     */
    protected $SupplierOrderDeliveryDate;

    /**
     * @param Model  $model
     * @param Config $config
     * @return static
     */
    public static function factory(Model $model, Config $config)
    {
        $instance = new static();
        $instance->Plant = $config->get('plantid');
        $instance->SupplierNo = $config->get('supplierno');
        $instance->SupplierOrderNo = $model->id;

        // TODO: Add supplier order date, deliverydate

        return $instance;
    }
}
