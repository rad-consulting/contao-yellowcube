<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */
namespace YellowCube;

use Contao\Config as Contao;

/**
 * Class Config
 */
class Config
{
    /**
     * @const string
     */
    const TEST = 'I';
    const PRODUCTION = 'P';

    /**
     * @var array
     */
    protected $wsdl = array(
        self::TEST => '',
        self::PRODUCTION => '',
    );

    /**
     * @param string     $name
     * @param mixed|null $default
     * @return mixed
     */
    public function get($name, $default = null)
    {
        if ('wsdl' == $name) {
            return $this->wsdl[$this->get('operatingmode', 'I')];
        }

        if ('options' == $name) {
            return array(
                'trace' => true,
            );
        }

        $value = Contao::get('yc_' . $name);

        return empty($value) ? $default : $value;
    }
}
