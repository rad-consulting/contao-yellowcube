<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 */
namespace RAD\YellowCube\Soap\Response;

/**
 * Class GenericResponse
 *
 * @see https://service.swisspost.ch/apache/yellowcube/YellowCube_GEN_RESPONSE_General.xsd
 */
class GenericResponse
{
    /**
     * @var string
     */
    protected $EventTimestamp;

    /**
     * @var string
     */
    protected $MessageType;

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
     * @var string
     */
    protected $Reference;

    /**
     * @var string
     */
    protected $Reference1;

    /**
     * @var string
     */
    protected $Reference2;

    /**
     * @var string
     */
    protected $Reference3;

    /**
     * @var string
     */
    protected $Reference4;

    /**
     * @return string
     */
    public function getEventTimestamp()
    {
        return $this->EventTimestamp;
    }

    /**
     * @return string
     */
    public function getMessageType()
    {
        return $this->MessageType;
    }

    /**
     * @param int|null $no
     * @return string
     */
    public function getReference($no = null)
    {
        return 0 < (int)$no ? $this->{'Reference' . $no} : $this->Reference;
    }

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
     * @return string
     */
    public function getStatusType()
    {
        return $this->StatusType;
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        return 'S' == $this->StatusType;
    }

    /**
     * @return bool
     */
    public function isError()
    {
        return 'E' == $this->StatusType;
    }
}
