<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */
namespace RAD\YellowCube\Soap\Unit;

use RAD\YellowCube\Soap\Util\SimpleValue;

/**
 * Class EAN
 */
class EAN extends SimpleValue
{
    /**
     * @const string
     */
    const E5 = 'E5';
    const EA = 'EA';
    const HE = 'HE';
    const HK = 'HK';
    const I6 = 'I6';
    const IC = 'IC';
    const IE = 'IE';
    const IK = 'IK';
    const SA = 'SA';
    const SG = 'SG';
    const UC = 'UC';
    const VC = 'VC';

    /**
     * @var string
     */
    protected $EANType = 'HE';

    /**
     * @param mixed       $EAN
     * @param string|null $EANType
     */
    public function __construct($EAN, $EANType = null)
    {
        parent::__construct($EAN);

        $this->EANType = $EANType;
    }
}
