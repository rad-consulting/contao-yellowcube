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

        // Map values from Haste
        $map = array(
            'kg' => ISO::KGM,
        );

        if (isset($map[$ISO])) {
            $this->ISO = $map[$ISO];
        }
    }
}
