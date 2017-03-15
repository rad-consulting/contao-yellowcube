<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 */
namespace RAD\YellowCube\Soap\Unit;

use RAD\YellowCube\Soap\Util\SimpleValue;

/**
 * Class ISO
 *
 * @see http://www.robert-kuhlemann.de/iso_masseinheiten.htm
 */
abstract class ISO extends SimpleValue
{
    /**
     * @const string
     */
    const LTR = 'LTR';
    const BA = 'BA';
    const GRM = 'GRM';
    const KGM = 'KGM';
    const MGM = 'MGM';
    const MMT = 'MMT';
    const CM = 'CM';
    const CMK = 'CMK';
    const CMQ = 'CMQ';
    const CMT = 'CMT';
    const M = 'M';
    const MTR = 'MTR';
    const M3 = 'M3';
    const MTK = 'MTK';
    const MTQ = 'MTQ';
    const GR = 'GR';
    const GRA = 'GRA';
    const EUR = 'EUR';
    const EWP = 'EWP';
    const STK = 'STK';
    const PCE = 'PCE';
    const PA = 'PA';
    const BG = 'BG';
    const CA = 'CA';

    /**
     * @var string
     */
    protected $ISO;

    /**
     * @param mixed  $value
     * @param string $ISO
     */
    public function __construct($value, $ISO)
    {
        parent::__construct($value);
        $this->ISO = $ISO;
    }
}
