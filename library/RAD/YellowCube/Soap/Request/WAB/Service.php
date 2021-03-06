<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 */
namespace RAD\YellowCube\Soap\Request\WAB;

use Isotope\Model\ProductCollection\Order as ShopOrder;
use RAD\Fulfillment\Model\Shipping\Swisspost;
use RAD\YellowCube\Config;

/**
 * Class Service
 */
class Service
{
    /**
     * @var string
     */
    protected $BasicShippingServices = 'ECO';

    /**
     * @param ShopOrder $order
     * @param Config    $config
     * @return static
     */
    public static function factory(ShopOrder $order, Config $config)
    {
        $instance = new static();
        $shipping = $order->getShippingMethod();

        if ($shipping instanceof Swisspost) {
            $instance->BasicShippingServices = $shipping->getBasicService();
        }

        return $instance;
    }
}
