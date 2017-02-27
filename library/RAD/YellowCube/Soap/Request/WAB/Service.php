<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */
namespace RAD\YellowCube\Soap\Request\WAB;

use Isotope\Model\ProductCollection\Order as ShopOrder;
use RAD\YellowCube\Config;
use RAD\YellowCube\Model\Shipping\YellowCube as YellowCubeBasic;

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

        if ($shipping instanceof YellowCubeBasic) {
            $instance->BasicShippingServices = $shipping->getBasicService();
        }

        return $instance;
    }
}
