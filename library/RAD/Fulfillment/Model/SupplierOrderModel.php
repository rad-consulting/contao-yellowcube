<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */
namespace RAD\Fulfillment\Model;

/**
 * Class SupplierOrderModel
 *
 * @property int $export
 * @property int $exported
 */
class SupplierOrderModel extends AbstractModel
{
    /**
     * @var string
     */
    public static $strTable = 'tl_rad_supplier_order';
}
