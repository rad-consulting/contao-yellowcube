<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 */
namespace RAD\YellowCube\Soap\Request\WAB;

use Isotope\Model\ProductCollection\Order as ShopOrder;
use RAD\YellowCube\Config;

/**
 * Class Header
 */
class Header
{
    /**
     * @var string
     */
    protected $DepositorNo;

    /**
     * @var int
     */
    protected $CustomerOrderNo;

    /**
     * @var int
     */
    protected $CustomerOrderDate;

    /**
     * @param ShopOrder $order
     * @param Config    $config
     * @return static
     */
    public static function factory(ShopOrder $order, Config $config)
    {
        $instance = new static();
        $instance->DepositorNo = $config->get('depositorno');
        $instance->CustomerOrderNo = $order->getId();
        $instance->CustomerOrderDate = date('Ymd', $order->tstamp);

        return $instance;
    }
}
