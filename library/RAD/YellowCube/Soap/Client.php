<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */
namespace RAD\YellowCube\Soap;

use SoapClient;
use RAD\YellowCube\Soap\Response\GenericResponse;
use RAD\YellowCube\Soap\Response\GenericStatus;
use RAD\YellowCube\Soap\Response\BAR\ArticleList;
use RAD\YellowCube\Soap\Response\WAR\IssueList;

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
 * @method IssueList getYCCustomerOrderReply(array $request)
 * @method ArticleList getInventory(array $request)
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
                // BAR
                'BAR' => 'RAD\\YellowCube\\Soap\\Response\\BAR\\ArticleList',
                // WAR
                'GoodsIssue' => 'RAD\\YellowCube\\Soap\\Response\\WAR\\GoodsIssue',
                'GoodsIssueHeader' => 'RAD\\YellowCube\\Soap\\Response\\WAR\\GoodsIssueHeader',
                'CustomerOrderHeader' => 'RAD\\YellowCube\\Soap\\Response\\WAR\\CustomerOrderHeader',
                'CustomerOrderDetail' => 'RAD\\YellowCube\\Soap\\Response\\WAR\\CustomerOrderDetail',
            )), $options));
    }

    /**
     * @param array $request
     * @return GenericResponse
     */
    public function sendArticleMasterData(array $request)
    {
        return $this->insertArticleMasterData($request);
    }

    /**
     * @param array $request
     * @return GenericStatus
     */
    public function statusArticleMasterData(array $request)
    {
        return $this->getInsertArticleMasterDataStatus($request);
    }

    /**
     * @param array $request
     * @return GenericResponse
     */
    public function sendSupplierOrder(array $request)
    {
        return $this->createYCSupplierOrder($request);
    }

    /**
     * @param array $request
     * @return GenericStatus
     */
    public function statusSupplierOrder(array $request)
    {
        return $this->getYCSupplierOrderStatus($request);
    }

    /**
     * @param array $request
     * @return GenericResponse
     */
    public function sendCustomerOrder(array $request)
    {
        return $this->createYCCustomerOrder($request);
    }

    /**
     * @param array $request
     * @return GenericStatus
     */
    public function statusCustomerOrder(array $request)
    {
        return $this->getYCCustomerOrderStatus($request);
    }

    /**
     * @param array $request
     * @return IssueList
     */
    public function getDeliveryNotices(array $request)
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
