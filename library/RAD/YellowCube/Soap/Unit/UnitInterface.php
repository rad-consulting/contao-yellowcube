<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */
namespace RAD\YellowCube\Soap\Util;

/**
 * Interface UnitInterface
 */
interface UnitInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getISO();

    /**
     * @return mixed
     */
    public function getValue();
}
