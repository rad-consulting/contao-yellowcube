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
use RAD\Fulfillment\Model\SupplierOrderModel;
use RAD\Log\LogException;
use RAD\Log\Model\LogModel as Log;
use RAD\YellowCube\Model\Product\YellowCubeProduct;
use RAD\YellowCube\Soap\Request;
use RAD\YellowCube\Soap\Client;
use RAD\YellowCube\Soap\Request\WBL\Order as SupplierOrder;
use RAD\YellowCube\Soap\Request\WAB\Order as CustomerOrder;

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
            'yellowcube.sendProduct' => 'onSendProduct',
            'yellowcube.statusProduct' => 'onStatusProduct',
            'yellowcube.sendSupplierOrder' => 'onSendSupplierOrder',
            'yellowcube.statusSupplierOrder' => 'onStatusSupplierOrder',
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
    public function onSendProduct(Event $event)
    {
        $product = $event->getSubject();

        if ($product instanceof YellowCubeProduct) {
            $response = $this->getClient()->sendArticleMasterData(array(
                'ControlReference' => Request\ControlReference::factory('ART', $this->getConfig()),
                'ArticleList' => array(
                    'Article' => Request\ART\Article::factory($product, $this->getConfig()),
                ),
            ));

            if ($response->isSuccess()) {
                $product->log($response->getStatusText(), Log::DEBUG, $this->getLastXML());
                $this->dispatch('statusProduct', $product, array('reference' => $response->getReference()));

                return;
            }

            $product->log($response->getStatusText(), Log::ERROR, $this->getLastXML());
            throw new Exception($response->getStatusText());
        }
    }

    /**
     * @param Event $event
     * @throws Exception
     */
    public function onStatusProduct(Event $event)
    {
        $product = $event->getSubject();
        $reference = $event->getArgument('reference');

        if ($product instanceof YellowCubeProduct) {
            $response = $this->getClient()->statusArticleMasterData(array(
                'ControlReference' => Request\ControlReference::factory('ART', $this->getConfig()),
                'Reference' => $reference,
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
    public function onSendSupplierOrder(Event $event)
    {
        $model = $event->getSubject();

        if ($model instanceof SupplierOrderModel) {
            $order = SupplierOrder::factory($model, $this->getConfig());
            $response = $this->getClient()->sendSupplierOrder(array(
                'ControlReference' => Request\ControlReference::factory('WBL', $this->getConfig()),
                'SupplierOrder' => $order,
            ));

            if ($response->isSuccess()) {
                $model->log($response->getStatusText(), Log::DEBUG, $this->getLastXML());
                $this->dispatch('statusSupplierOrder', $model, array('reference' => $response->getReference()));

                return;
            }

            $model->log($response->getStatusText(), Log::ERROR, $this->getLastXML());
            throw new Exception($response->getStatusText());
        }
    }

    /**
     * @param Event $event
     * @throws Exception
     */
    public function onStatusSupplierOrder(Event $event)
    {
        $model = $event->getSubject();
        $reference = $event->getArgument('reference');

        if ($model instanceof SupplierOrderModel) {
            $response = $this->getClient()->statusSupplierOrder(array(
                'ControlReference' => Request\ControlReference::factory('WBL', $this->getConfig()),
                'Reference' => $reference,
            ));

            if ($response->isSuccess()) {
                $model->setExported(true, $response->getStatusText(), $this->getLastXML())->save();

                return;
            }

            $model->log($response->getStatusText(), Log::ERROR, $this->getLastXML());
            throw new Exception($response->getStatusText());
        }
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
        $model = $event->getSubject();

        if ($model instanceof Fulfillment) {
            try {
                $response = $this->getClient()->statusCustomerOrder(array(
                    'ControlReference' => Request\ControlReference::factory('WAB', $this->getConfig()),
                    'Reference' => $model->getReference(),
                ));

                if ($response->isSuccess()) {
                    $model->setConfirmed($response->getReference(), $response->getStatusText(), $this->getLastXML())->save();
                    $this->dispatch('updateFulfillment', $model);

                    return;
                }
            }
            catch (Exception $e) {
                throw new LogException($e->getMessage(), Log::ERROR, null, $this->getLastXML());
            }

            $model->log($response->getStatusText(), Log::ERROR, $this->getLastXML());
            throw new Exception($response->getStatusText());
        }
    }

    /**
     * @param Event $event
     * @return void
     * @throws Exception
     */
    public function onSendFulfillment(Event $event)
    {
        $model = $event->getSubject();

        if ($model instanceof Fulfillment) {
            $response = $this->getClient()->sendCustomerOrder(array(
                'ControlReference' => Request\ControlReference::factory('WAB', $this->getConfig()),
                'Order' => CustomerOrder::factory($model->getOrder(), $this->getConfig()),
            ));

            if ($response->isSuccess()) {
                $model->setSent($response->getReference(), $response->getStatusText(), $this->getLastXML())->save();
                $this->dispatch('confirmFulfillment', $model, array('reference' => $response->getReference()));

                return;
            }

            $model->log($response->getStatusText(), Log::ERROR, $this->getLastXML());
            throw new Exception($response->getStatusText());
        }
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
     * @param array|null $arguments
     * @return $this
     */
    protected function dispatch($name, Model $subject = null, array $arguments = null)
    {
        EventDispatcher::getInstance()->dispatch('yellowcube.' . $name, $subject, $arguments);

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
