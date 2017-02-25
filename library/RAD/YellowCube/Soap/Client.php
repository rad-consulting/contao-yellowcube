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
                'GEN_Response' => 'RAD\\YellowCube\\Soap\\Response\\GenericResponse',
                'GEN_STATUS' => 'RAD\\YellowCube\\Soap\\Response\\GenericStatus',
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
