<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */
namespace RAD\YellowCube\Soap;

use Contao\System;
use SoapClient;
use RAD\YellowCube\Soap\Response\GenericResponse;

/**
 * Class Client
 *
 * @see https://service-test.swisspost.ch/apache/yellowcube-test/?wsdl
 * @see https://service.swisspost.ch/apache/yellowcube/?wsdl
 *
 * @method GenericResponse insertArticleMasterData(array $request)
 */
class Client extends SoapClient
{
    /**
     * @param string $wsdl
     * @param array  $options
     */
    public function __construct($wsdl, array $options)
    {
        System::log($wsdl . json_encode($options), __METHOD__, TL_GENERAL);

        parent::__construct($wsdl, array_merge(array(
            'classmap' => array(
                'GEN_Response' => 'RAD\\YellowCube\\Soap\\Response\\GenericResponse',
            )), $options));
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
