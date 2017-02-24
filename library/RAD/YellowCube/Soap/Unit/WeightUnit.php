<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */
namespace RAD\YellowCube\Soap\Unit;

use RAD\YellowCube\Soap\Util\ISO;

/**
 * Class WeightUnit
 */
class WeightUnit extends AbstractUnit
{
    /**
     * @param float  $value
     * @param string $ISO
     */
    public function __construct($value, $ISO)
    {
        // Map values from Haste
        $map = array(
            'kg' => ISO::KGM,
        );

        if (isset($map[$ISO])) {
            $ISO = $map[$ISO];
        }

        parent::__construct($value, $ISO);
    }
}
