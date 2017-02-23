<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */
namespace YellowCube;

use Contao\DataContainer;
use Exception;
use Contao\Model;
use Contao\Model\Collection;
use Isotope\Model\OrderStatus;
use Isotope\Model\Product;
use Isotope\Model\ProductCollection\Order;
use YellowCube\Model\EventModel;
use YellowCube\Model\FulfillmentModel;
use YellowCube\Model\LogModel;
use YellowCube\Model\Product\YellowCubeProduct;
use YellowCube\Soap\ART\Article;
use YellowCube\Soap\Client;
use YellowCube\Soap\ControlReference;
use YellowCube\Soap\ResponseGeneric;

/**
 * Class Service
 */
class Service
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
     * @param string|null $name
     * @return array
     */
    public function getListeners($name = null)
    {
        $listeners = array(
            'sendFulfillment' => array(
                array($this, 'onSendFulfillment'),
            ),
            'confirmFulfillment' => array(
                array($this, 'onConfirmFulfillment'),
            ),
            'updateFulfillment' => array(
                array($this, 'onUpdateFulfillment'),
            ),
            'completeFulfillment' => array(
                array($this, 'onCompleteFulfillment'),
            ),
            'sendArticle' => array(
                array($this, 'onSendArticle'),
            ),
        );

        return $name ? $listeners[str_replace('yellowcube.', '', $name)] : $listeners;
    }

    /**
     * @return void
     */
    public function run()
    {
        $collection = EventModel::findAll(array('order' => 'id ASC', 'return' => 'Collection'));

        if ($collection instanceof Collection) {
            foreach ($collection as $item) {
                if ($item instanceof EventModel && (0 == $item->attempt || time() > $item->tstamp + $item->timeout)) {
                    try {
                        $item->attempt++;
                        $item->status = $item::RUNNING;
                        $item->tstamp = time();
                        $item->error = 0;
                        $item->save();

                        foreach ($this->getListeners($item->name) as $listener) {
                            call_user_func_array($listener, array($item, $this));
                        }

                        $item->delete();
                    }
                    catch (Exception $e) {
                        $item->status = $item::WAITING;
                        $item->error = 1;
                        $item->log($e)->save();
                    }
                }
            }
        }
    }

    /**
     * @param DataContainer $dc
     * @return void
     */
    public function sendArticle(DataContainer $dc)
    {
        if ($dc->activeRecord) {
            $product = Product::findByPk($dc->activeRecord->id);
            $this->dispatchEvent('sendArticle', $product);
        }
    }

    /**
     * @param Collection $collection
     * @return ResponseGeneric
     * @throws Exception
     */
    public function sendMasterData(Collection $collection)
    {
        $articles = array();

        foreach ($collection as $item) {
            if ($item instanceof YellowCubeProduct && $item->doExport()) {
                $articles[] = Article::factory($item);
            }
        }

        return $this->getClient()->XXX(array(
            'ControlReference' => ControlReference::factory('ART', $this->getConfig()),
            'ArticleList' => $articles,
        ));
    }

    /**
     * @param YellowCubeProduct $product
     * @return ResponseGeneric
     * @throws Exception
     */
    public function sendMasterDataSingle(YellowCubeProduct $product)
    {
        return $this->getClient()->XXX(array(
            'ControlReference' => ControlReference::factory('ART', $this->getConfig()),
            'ArticleList' => array(
                'Article' => Article::factory($product),
            ),
        ));
    }

    /**
     * @param EventModel $event
     * @return void
     * @throws Exception
     */
    public function onCompleteFulfillment(EventModel $event)
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
     * @param EventModel $event
     * @return void
     * @throws Exception
     */
    public function onConfirmFulfillment(EventModel $event)
    {
        /**
         * @var FulfillmentModel $fulfillment
         */
        $fulfillment = $event->getSubject();
        $fulfillment->setConfirmed()->log($response->getStatusText(), LogModel::INFO, $this->getLastXML())->save();

        $this->dispatchEvent('updateFulfillment', $fulfillment);
    }

    /**
     * @param Order $order
     * @return void
     * @throws Exception
     */
    public function onCreateOrder(Order $order)
    {
        if (!(bool)$this->getConfig()->get('active')) {
            return;
        }

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

        // TODO: Shoot event
        $this->dispatchEvent('sendFulfillment', $fulfillment);
    }

    /**
     * @param EventModel $event
     * @return void
     * @throws Exception
     */
    public function onSendArticle(EventModel $event)
    {
        $product = $event->getSubject();

        if ($product instanceof YellowCubeProduct) {
            $response = $this->sendMasterDataSingle($product);

            if ($response->isSuccess()) {
                $product->setExported(true)
                    ->log($response->getStatusText(), LogModel::INFO, $this->getLastXML())
                    ->save();
            }
            else {
                $product->log($response->getStatusText(), LogModel::ERROR, $this->getLastXML());

                throw new Exception($response->getStatusText(), LogModel::ERROR);
            }
        }

        throw new Exception('Event subject must be instance of YellowCube\\Model\\Product\\YellowCubeProduct', LogModel::ERROR);
    }

    /**
     * @param EventModel $event
     * @return void
     * @throws Exception
     */
    public function onSendFulfillment(EventModel $event)
    {
        /**
         * @var FulfillmentModel $fulfillment
         */
        $fulfillment = $event->getSubject();
        $fulfillment->setSent($response->getReference())->log($response->getStatusText(), LogModel::INFO, $this->getLastXML())->save();

        $this->dispatchEvent('confirmFulfillment', $fulfillment);
    }

    /**
     * @param EventModel $event
     * @return void
     * @throws Exception
     */
    public function onUpdateFulfillment(EventModel $event)
    {
        /**
         * @var FulfillmentModel $fulfillment
         */
        $fulfillment = $event->getSubject();
        // TODO
    }

    /**
     * @param string $name
     * @param Model  $subject
     */
    protected function dispatchEvent($name, Model $subject)
    {
        $event = EventModel::factory('yellowcube.' . $name, $subject);
        $event->save();
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
