<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 */
namespace RAD\YellowCube;

use Contao\Config as Contao;

/**
 * Class Config
 */
class Config
{
    /**
     * @const string
     */
    const TEST = 'T';
    const PROD = 'P';

    /**
     * @var array
     */
    protected $wsdl = array(
        self::TEST => 'https://service-test.swisspost.ch/apache/yellowcube-test/?wsdl',
        self::PROD => 'https://service.swisspost.ch/apache/yellowcube/?wsdl',
    );

    /**
     * @param string     $name
     * @param mixed|null $default
     * @return mixed
     */
    public function get($name, $default = null)
    {
        if ('wsdl' == $name) {
            return $this->wsdl[Contao::get('rad_yellowcube_operatingmode')];
        }

        if ('options' == $name) {
            return array(
                'trace' => true,
                'features' => SOAP_SINGLE_ELEMENT_ARRAYS,
                'exceptions' => true,
                'cache_wsdl' => WSDL_CACHE_MEMORY,
                'soap_version' => SOAP_1_1,
            );
        }

        $value = Contao::get('rad_yellowcube_' . $name);

        return $value ? $value : $default;
    }
}
