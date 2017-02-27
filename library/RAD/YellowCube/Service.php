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
use RAD\Event\Model\Event;
use Exception;
use Contao\Model;
use Isotope\Model\ProductCollection\Order as ShopOrder;
use RAD\Fulfillment\Model\Fulfillment;
use RAD\Fulfillment\Model\MasterData;
use RAD\Fulfillment\Model\Product\Fulfillment as FulfillmentProduct;
use RAD\Fulfillment\Model\SupplierOrder as ShopSupplierOrder;
use RAD\Log\LogException;
use RAD\Log\Model\Log;
use RAD\YellowCube\Model\Product\YellowCube;
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
            'yellowcube.sendAssortment' => 'onSendAssortment',
            'yellowcube.sendProduct' => 'onSendProduct',
            'yellowcube.statusAssortment' => 'onStatusAssortment',
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
        $this->dispatch('yellowcube.importStock');
    }

    /**
     * @param Event $event
     * @return void
     * @throws Exception
     */
    public function onSendAssortment(Event $event)
    {
        $model = $event->getSubject();

        if ($model instanceof MasterData && 'yellowcube' == $model->producttype) {
            try {
                $collection = FulfillmentProduct::findByType('yellowcube');
                $response = $this->getClient()->sendArticleMasterData(array(
                    'ControlReference' => Request\ControlReference::factory('ART', $this->getConfig()),
                    'ArticleList' => array(
                        'Article' => Request\ART\ArticleList::factory($collection, $this->getConfig()),
                    ),
                ));

                if ($response->isSuccess()) {
                    $model->log($response->getStatusText(), Log::DEBUG, $this->getLastXML());
                    $this->dispatch('yellowcube.statusAssortment', $model, array('reference' => $response->getReference()));

                    return;
                }

                $model->log($response->getStatusText(), Log::ERROR, $this->getLastXML());
                throw new Exception($response->getStatusText());
            }
            catch (Exception $e) {
                throw new LogException($e->getMessage(), Log::ERROR, $e, $this->getLastXML());
            }
        }
    }

    /**
     * @param Event $event
     * @throws Exception
     */
    public function onStatusAssortment(Event $event)
    {
        $model = $event->getSubject();
        $reference = $event->getArgument('reference');

        if ($model instanceof MasterData) {
            $response = $this->getClient()->statusArticleMasterData(array(
                'ControlReference' => Request\ControlReference::factory('ART', $this->getConfig()),
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
    public function onSendProduct(Event $event)
    {
        $product = $event->getSubject();

        if ($product instanceof YellowCube) {
            $response = $this->getClient()->sendArticleMasterData(array(
                'ControlReference' => Request\ControlReference::factory('ART', $this->getConfig()),
                'ArticleList' => array(
                    'Article' => Request\ART\Article::factory($product, $this->getConfig()),
                ),
            ));

            if ($response->isSuccess()) {
                $product->log($response->getStatusText(), Log::DEBUG, $this->getLastXML());
                $this->dispatch('yellowcube.statusProduct', $product, array('reference' => $response->getReference()));

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

        if ($product instanceof YellowCube) {
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

        if ($model instanceof ShopSupplierOrder) {
            $order = SupplierOrder::factory($model, $this->getConfig());
            $response = $this->getClient()->sendSupplierOrder(array(
                'ControlReference' => Request\ControlReference::factory('WBL', $this->getConfig()),
                'SupplierOrder' => $order,
            ));

            if ($response->isSuccess()) {
                $model->log($response->getStatusText(), Log::DEBUG, $this->getLastXML());
                $this->dispatch('yellowcube.statusSupplierOrder', $model, array('reference' => $response->getReference()));

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

        if ($model instanceof ShopSupplierOrder) {
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
                    $this->dispatch('yellowcube.updateFulfillment', $model);

                    return;
                }

                throw new Exception($response->getStatusText());
            }
            catch (Exception $e) {
                $model->setRejected($e->getMessage(), $this->getLastXML())->save();
            }
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
                $this->dispatch('yellowcube.confirmFulfillment', $model, array('reference' => $response->getReference()));

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

        $this->dispatch('fulfillment.complete', $fulfillment);
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

        if ($order instanceof ShopOrder) {
            foreach ($order->getItems() as $item) {
                if ('yellowcube' == $item->type) {

                    $fulfillment = Fulfillment::factory($order);
                    $fulfillment->save();
                    $this->dispatch('yellowcube.sendFulfillment', $fulfillment);

                    return;
                }
            }
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
        EventDispatcher::getInstance()->dispatch($name, $subject, $arguments);

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
