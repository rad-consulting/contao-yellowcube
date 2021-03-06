<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 */
namespace RAD\YellowCube\Soap\Unit;

use Haste\Units\Mass\Unit;
use RAD\YellowCube\Soap\Util\SimpleValue;

/**
 * Class Mass
 */
class Mass extends SimpleValue
{
    /**
     * @var string
     */
    protected $ISO = ISO::KGM;

    /**
     * @param float  $value
     * @param string $ISO
     */
    public function __construct($value, $ISO)
    {
        parent::__construct($value);

        // Map units from Haste
        $map = array(
            Unit::KILOGRAM => ISO::KGM,
            Unit::GRAM => ISO::GRM,
        );

        if (isset($map[$ISO])) {
            $this->ISO = $map[$ISO];
        }
    }
}
