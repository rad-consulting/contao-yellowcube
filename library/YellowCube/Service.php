<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */
namespace YellowCube;

use Event\EventDispatcher;
use Event\EventSubscriberInterface;
use Event\Model\EventModel as Event;
use Exception;
use Contao\DataContainer;
use Contao\Model;
use Isotope\Model\OrderStatus;
use Isotope\Model\Product;
use Isotope\Model\ProductCollection\Order;
use Fulfillment\Model\FulfillmentModel;
use Logging\Model\LogModel;
use YellowCube\Model\Product\YellowCubeProduct;
use YellowCube\Soap\ART\Article;
use YellowCube\Soap\Client;
use YellowCube\Soap\ControlReference;

/**
 * Class Service
 */
class Service implements EventSubscriberInterface
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
            'yellowcube.exportSupplierorder' => 'onExportSupplierorder',
            'yellowcube.import' => 'onImport',
        );
    }

    /**
     * @param DataContainer $dc
     * @return void
     */
    public function exportProduct(DataContainer $dc)
    {
        if ($dc->activeRecord) {
            $product = YellowCubeProduct::findByPk($dc->activeRecord->id);

            if ($product instanceof YellowCubeProduct && $product->doExport()) {
                $this->dispatch('exportProduct', $product);
            }
        }
    }

    /**
     * @param DataContainer $dc
     * @return void
     */
    public function exportSupplierorder(DataContainer $dc)
    {
        if ($dc->activeRecord) {
            $order = SupplierOrder::findByPk($dc->activeRecord->id);

            if ($order instanceof SupplierOrder && $order->doExport()) {
                $this->dispatch('exportSupplierorder', $order);
            }
        }
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
            $response = $this->getClient()->XXX(array(
                'ControlReference' => ControlReference::factory('ART', $this->getConfig()),
                'ArticleList' => array(
                    'Article' => Article::factory($product),
                ),
            ));

            if ($response->isSuccess()) {
                $product->setExported()->log($response->getStatusText(), LogModel::INFO, $this->getLastXML())->save();

                return;
            }

            throw new Exception($response->getStatusText(), LogModel::ERROR);
        }
    }

    /**
     * @param Event $event
     * @return void
     * @throws Exception
     */
    public function onExportSupplierorder(Event $event)
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
         * @var FulfillmentModel $fulfillment
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
         * @var FulfillmentModel $fulfillment
         */
        $fulfillment = $event->getSubject();
        $fulfillment->setConfirmed()->log($response->getStatusText(), LogModel::INFO, $this->getLastXML())->save();

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
         * @var FulfillmentModel $fulfillment
         */
        $fulfillment = $event->getSubject();
        $fulfillment->setSent($response->getReference())->log($response->getStatusText(), LogModel::INFO, $this->getLastXML())->save();

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
         * @var FulfillmentModel $fulfillment
         */
        $fulfillment = $event->getSubject();
        // TODO

        $response = $this->getClient()->XXX($p);

        if ($response instanceof GoodsIssue) {
            $fulfillment->setDelivered($response->getPostalNo())->save();
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

            $fulfillment = FulfillmentModel::factory($order);
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
        return implode(PHP_EOL . PHP_EOL, array(
            'RESPONSE XML: ' . PHP_EOL . $this->getClient()->__getLastResponse(),
            'REQUEST XML: ' . PHP_EOL . $this->getClient()->__getLastRequest(),
        ));
    }
}
