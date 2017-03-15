<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 */
namespace RAD\YellowCube\Soap\Request\WAB;

use Isotope\Model\ProductCollection\Order as ShopOrder;
use RAD\YellowCube\Config;
use RAD\YellowCube\Model\Product\YellowCube;

/**
 * Class Order
 */
class Order
{
    /**
     * @var Header
     */
    protected $OrderHeader;

    /**
     * @var Partner[]
     */
    protected $PartnerAddress = array();

    /**
     * @var Service[]
     */
    protected $ValueAddedServices = array();

    /**
     * @var Position[]
     */
    protected $OrderPositions = array();

    /**
     * @param ShopOrder $order
     * @param Config    $config
     * @return static
     */
    public static function factory(ShopOrder $order, Config $config)
    {
        $instance = new static();
        $instance->OrderHeader = Header::factory($order, $config);

        // Add partner address
        $instance->PartnerAddress[] = Partner::factory($order->getShippingAddress(), $config);

        // Add service
        $instance->ValueAddedServices[] = array(
            'AdditionalService' => Service::factory($order, $config),
        );

        // Add positions
        foreach ($order->getItems() as $item) {
            $product = $item->getProduct();

            if ($product instanceof YellowCube) {
                $instance->OrderPositions[] = Position::factory($item, count($instance->OrderPositions) + 1, $config);
            }
        }

        return $instance;
    }
}
