<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 */
namespace RAD\YellowCube\Soap\Request;

use RAD\YellowCube\Config;

/**
 * Class ControlReference
 *
 *
 */
class ControlReference
{
    /**
     * @var string
     */
    protected $Type;

    /**
     * @var string
     */
    protected $Sender;

    /**
     * @var string
     */
    protected $Receiver;

    /**
     * @var int
     */
    protected $Timestamp;

    /**
     * @var string
     */
    protected $OperatingMode;

    /**
     * @var string
     */
    protected $Version;

    /**
     * @var string
     */
    protected $CommType = 'SOAP';

    /**
     * @param string $type
     * @param Config $config
     * @return static
     */
    public static function factory($type, Config $config)
    {
        $instance = new static();
        $instance->OperatingMode = $config->get('operatingmode', 'I');
        $instance->Timestamp = date('YmdHis');
        $instance->Receiver = $config->get('receiver', 'YELLOWCUBE');
        $instance->Version = $config->get('version', '1.0');
        $instance->Sender = $config->get('sender', 'YCtest');
        $instance->Type = $type;

        return $instance;
    }
}
