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
 * Class AbstractUnit
 */
abstract class AbstractUnit extends SimpleValue implements UnitInterface
{
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

    /**
     * @return string
     */
    public function getISO()
    {
        return $this->ISO;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return str_replace('Unit', '', array_pop(explode('\\', get_called_class())));
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->get();
    }
}
