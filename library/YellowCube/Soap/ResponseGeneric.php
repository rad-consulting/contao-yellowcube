<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */
namespace YellowCube\Soap;

/**
 * Class ResponseGeneric
 */
class ResponseGeneric
{
    /**
     * @var int
     */
    protected $StatusCode;

    /**
     * @var string
     */
    protected $StatusText;

    /**
     * @var string
     */
    protected $StatusType;

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->StatusCode;
    }

    /**
     * @return string
     */
    public function getStatusText()
    {
        return $this->StatusText;
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        return 'S' == $this->StatusType;
    }
}
