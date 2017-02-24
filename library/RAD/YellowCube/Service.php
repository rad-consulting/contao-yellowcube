<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */
namespace RAD\YellowCube;

use RAD\Event\EventDispatcher;
use RAD\Event\EventSubscriberInterface as EventSubscriber;
use RAD\Event\Model\EventModel as Event;
use Exception;
use Contao\Model;
use Isotope\Model\OrderStatus;
use Isotope\Model\ProductCollection\Order;
use RAD\Fulfillment\Model\FulfillmentModel as Fulfillment;
use RAD\Log\Model\LogModel as Log;
use RAD\YellowCube\Model\Product\YellowCubeProduct;
use RAD\YellowCube\Soap\Request;
use RAD\YellowCube\Soap\Client;

/**
 * Class Service
 */
class Service implements EventSubscriber
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @return Client
     */
    public function getClient()
    {
        if (empty($this->client)) {
            $config = $this->getConfig();
            $this->client = new Client($config->get('wsdl'), $config->get('options'));
        }

        return $this->client;
    }

    /**
     * @return Config
     */
    public function getConfig()
    {
        if (empty($this->config)) {
            $this->config = new Config();
        }

        return $this->config;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            'order.create' => 'onCreateOrder',
            'yellowcube.sendFulfillment' => 'onSendFulfillment',
            'yellowcube.confirmFulfillment' => 'onConfirmFulfillment',
            'yellowcube.updateFulfillment' => 'onUpdateFulfillment',
            'yellowcube.exportProduct' => 'onExportProduct',
            'yellowcube.exportSupplierOrder' => 'onExportSupplierOrder',
            'yellowcube.importStock' => 'onImportStock',
        );
    }

    /**
     * @return void
     */
    public function importStock()
    {
        $this->dispatch('importStock');
    }

    /**
     * @param Event $event
     * @return void
     * @throws Exception
     */
    public function onExportProduct(Event $event)
    {
        $product = $event->getSubject();

        if ($product instanceof YellowCubeProduct) {
            $response = $this->getClient()->insertArticleMasterData(array(
                'ControlReference' => Request\ControlReference::factory('ART', $this->getConfig()),
                'ArticleList' => array(
                    'Article' => Request\ART\Article::factory($product, $this->getConfig()),
                ),
            ));

            if ($response->isSuccess()) {
                $product->setExported(true, $response->getStatusText(), $this->getLastXML())->save();

                return;
            }

            $product->log($response->getStatusText(), Log::ERROR, $this->getLastXML());
            throw new Exception($response->getStatusText());
        }
    }

    /**
     * @param Event $event
     * @return void
     * @throws Exception
     */
    public function onExportSupplierOrder(Event $event)
    {
        // TODO
    }

    /**
     * @param Event $event
     * @return void
     * @throws Exception
     */
    public function onCompleteFulfillment(Event $event)
    {
        /**
         * @var Fulfillment $fulfillment
         */
        $fulfillment = $event->getSubject();
        $fulfillment->setCompleted()->save();

        $status = OrderStatus::findBy('name', 'Complete');
        $order = Order::findByPk($fulfillment->pid);

        if ($status->id == $order->order_status) {
            return;
        }

        // TODO
    }

    /**
     * @param Event $event
     * @return void
     * @throws Exception
     */
    public function onConfirmFulfillment(Event $event)
    {
        /**
         * @var Fulfillment $fulfillment
         */
        $fulfillment = $event->getSubject();
        $fulfillment->setConfirmed($response->getReference(), $response->getStatusText(), $this->getLastXML())->save();

        $this->dispatch('updateFulfillment', $fulfillment);
    }

    /**
     * @param Event $event
     * @return void
     * @throws Exception
     */
    public function onSendFulfillment(Event $event)
    {
        /**
         * @var Fulfillment $fulfillment
         */
        $fulfillment = $event->getSubject();
        $fulfillment->setSent($response->getReference(), $response->getStatusText(), $this->getLastXML())->save();

        $this->dispatch('confirmFulfillment', $fulfillment);
    }

    /**
     * @param Event $event
     * @return void
     * @throws Exception
     */
    public function onUpdateFulfillment(Event $event)
    {
        /**
         * @var Fulfillment $fulfillment
         */
        $fulfillment = $event->getSubject();
        // TODO

        $response = $this->getClient()->XXX($p);

        if ($response instanceof GoodsIssue) {
            $fulfillment->setDelivered($response->getPostalNo(), $response->getStatusText(), $this->getLastXML())->save();
        }

        $this->dispatch('completeFulfillment', $fulfillment);
    }

    /**
     * @param Event $event
     * @return void
     * @throws Exception
     */
    public function onImportStock(Event $event)
    {
        // TODO
    }

    /**
     * @param Event $event
     * @return void
     * @throws Exception
     */
    public function onCreateOrder(Event $event)
    {
        if (!(bool)$this->getConfig()->get('active')) {
            return;
        }

        $order = $event->getSubject();

        if ($order instanceof Order) {
            $items = array();

            foreach ($order->getItems() as $item) {
                if ('yellowcube' == $item->type) {
                    $items[] = $item;
                }
            }

            if (!count($items)) {
                return;
            }

            $fulfillment = Fulfillment::factory($order);
            $fulfillment->save();

            $this->dispatch('sendFulfillment', $fulfillment);
        }
    }

    /**
     * @param string     $name
     * @param Model|null $subject
     * @return $this
     */
    protected function dispatch($name, Model $subject = null)
    {
        EventDispatcher::getInstance()->dispatch('yellowcube.' . $name, $subject);

        return $this;
    }

    /**
     * @return string
     */
    protected function getLastXML()
    {
        return $this->getClient()->getLastXML();
    }
}
