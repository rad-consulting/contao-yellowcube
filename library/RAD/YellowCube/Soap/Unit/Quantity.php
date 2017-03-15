<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 */
namespace RAD\YellowCube\Soap\Unit;

use RAD\YellowCube\Soap\Util\SimpleValue;

/**
 * Class Quantity
 */
class Quantity extends SimpleValue
{
    /**
     * @var string
     */
    protected $QuantityISO;

    /**
     * @param mixed       $Quantity
     * @param string|null $QuantityISO
     */
    public function __construct($Quantity, $QuantityISO = null)
    {
        parent::__construct($Quantity);

        $this->QuantityISO = $QuantityISO;
    }

    /**
     * @return string
     */
    public function getUnit()
    {
        return $this->QuantityISO;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->get();
    }
}
