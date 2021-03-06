<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 */
namespace RAD\YellowCube\Soap;

use SoapClient;
use RAD\YellowCube\Soap\Response\GenericResponse;
use RAD\YellowCube\Soap\Response\GenericStatus;
use RAD\YellowCube\Soap\Response\BAR\Inventory;
use RAD\YellowCube\Soap\Response\WAR\Replies;

/**
 * Class Client
 *
 * @see https://service-test.swisspost.ch/apache/yellowcube-test/?wsdl
 * @see https://service.swisspost.ch/apache/yellowcube/?wsdl
 *
 * @method GenericResponse insertArticleMasterData(array $request)
 * @method GenericStatus getInsertArticleMasterDataStatus(array $request)
 * @method GenericResponse createYCSupplierOrder(array $request)
 * @method GenericStatus getYCSupplierOrderStatus(array $request)
 * @method GenericResponse createYCCustomerOrder(array $request)
 * @method GenericStatus getYCCustomerOrderStatus(array $request)
 * @method Replies getYCCustomerOrderReply(array $request)
 * @method Inventory getInventory(array $request)
 */
class Client extends SoapClient
{
    /**
     * @param string $wsdl
     * @param array  $options
     */
    public function __construct($wsdl, array $options)
    {
        parent::__construct($wsdl, array_merge(array(
            'classmap' => array(
                // Generic
                'GEN_Response' => 'RAD\\YellowCube\\Soap\\Response\\GenericResponse',
                'GEN_STATUS' => 'RAD\\YellowCube\\Soap\\Response\\GenericStatus',
                'QuantityUOM' => 'RAD\\YellowCube\\Soap\\Unit\\Quantity',
                // BAR
                'BAR' => 'RAD\\YellowCube\\Soap\\Response\\BAR\\Inventory',
                'Article' => 'RAD\\YellowCube\\Soap\\Response\\BAR\\Article',
                // WAR
                'WAR' => 'RAD\\YellowCube\\Soap\\Response\\WAR\\Reply',
                'WAR_List' => 'RAD\\YellowCube\\Soap\\Response\\WAR\\Replies',
                'CustomerOrderHeader' => 'RAD\\YellowCube\\Soap\\Response\\WAR\\Header',
                'CustomerOrderDetail' => 'RAD\\YellowCube\\Soap\\Response\\WAR\\Detail',
            )), $options));
    }

    /**
     * @param array $request
     * @return GenericResponse
     */
    public function createArticle(array $request)
    {
        return $this->insertArticleMasterData($request);
    }

    /**
     * @param array $request
     * @return GenericStatus
     */
    public function getArticleStatus(array $request)
    {
        return $this->getInsertArticleMasterDataStatus($request);
    }

    /**
     * @param array $request
     * @return GenericResponse
     */
    public function createSupplierOrder(array $request)
    {
        return $this->createYCSupplierOrder($request);
    }

    /**
     * @param array $request
     * @return GenericStatus
     */
    public function getSupplierOrderStatus(array $request)
    {
        return $this->getYCSupplierOrderStatus($request);
    }

    /**
     * @param array $request
     * @return GenericResponse
     */
    public function createCustomerOrder(array $request)
    {
        return $this->createYCCustomerOrder($request);
    }

    /**
     * @param array $request
     * @return GenericStatus
     */
    public function getCustomerOrderStatus(array $request)
    {
        return $this->getYCCustomerOrderStatus($request);
    }

    /**
     * @param array $request
     * @return Replies
     */
    public function getCustomerOrderReplies(array $request)
    {
        return $this->getYCCustomerOrderReply($request);
    }

    /**
     * @return string
     */
    public function getLastXML()
    {
        return implode(PHP_EOL . PHP_EOL, array(
            'RESPONSE XML: ' . PHP_EOL . $this->__getLastResponse(),
            'REQUEST XML: ' . PHP_EOL . $this->__getLastRequest(),
        ));
    }
}
