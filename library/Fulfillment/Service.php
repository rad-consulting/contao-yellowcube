<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */
namespace Fulfillment;

use Event\EventDispatcher;
use Isotope\Model\ProductCollection\Order;

/**
 * Class Service
 */
class Service
{
    /**
     * @param Order $order
     * @return void
     */
    public function postCheckout(Order $order)
    {
        EventDispatcher::getInstance()->dispatch('order.create', $order);
    }
}
