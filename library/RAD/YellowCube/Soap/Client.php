<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */
namespace RAD\YellowCube\Soap;

use SoapClient;

/**
 * Class Client
 *
 * @method ResponseGeneric XXX($p)
 */
class Client extends SoapClient
{

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
