<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */
namespace RAD\YellowCube\Soap\Response\WAR;

/**
 * Class CustomerOrderHeader
 */
class CustomerOrderHeader
{
    /**
     * @var int
     */
    protected $YCDeliveryNo;

    /**
     * @var int
     */
    protected $YCDeliveryDate;

    /**
     * @var string
     */
    protected $CustomerOrderNo;

    /**
     * @var int
     */
    protected $CustomerOrderDate;

    /**
     * @var string
     */
    protected $PostalShipmentNo;

    /**
     * @var string
     */
    protected $PartnerReference;
}