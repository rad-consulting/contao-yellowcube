<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 */
namespace RAD\YellowCube\Soap\Request\WAB;

use Isotope\Model\Address as ShopAddress;
use RAD\YellowCube\Config;

/**
 * Class Partner
 */
class Partner
{
    /**
     * @var string
     */
    protected $PartnerType = 'WE';

    /**
     * @var int
     */
    protected $PartnerNo;

    /**
     * @var int
     */
    protected $PartnerReference;

    /**
     * @var string
     */
    protected $Title;

    /**
     * @var string
     */
    protected $Name1;

    /**
     * @var string
     */
    protected $Name2;

    /**
     * @var string
     */
    protected $Name3;

    /**
     * @var string
     */
    protected $Name4;

    /**
     * @var string
     */
    protected $Street;

    /**
     * @var string
     */
    protected $CountryCode;

    /**
     * @var int
     */
    protected $ZIPCode;

    /**
     * @var string
     */
    protected $City;

    /**
     * @var string
     */
    protected $POBox;

    /**
     * @var string
     */
    protected $PhoneNo;

    /**
     * @var string
     */
    protected $MobileNo;

    /**
     * @var string
     */
    protected $FaxNo;

    /**
     * @var string
     */
    protected $LanguageCode = 'de';

    /**
     * @param ShopAddress $address
     * @param Config      $config
     * @return static
     */
    public static function factory(ShopAddress $address, Config $config)
    {
        $instance = new static();
        $instance->PartnerNo = $config->get('partnerid');
        $instance->PartnerReference = $address->pid;

        if (empty($address->company)) {
            $instance->Name1 = substr($address->firstname . ' ' . $address->lastname, 0, 35);
        }
        else {
            $instance->Name1 = substr($address->company, 0, 35);
            $instance->Name2 = substr($address->firstname . ' ' . $address->lastname, 0, 35);
        }

        $street = array();

        for ($i = 1; $i <= 3; $i++) {
            if (!empty($address->{"street_{$i}"})) {
                $street[] = $address->{"street_{$i}"};
            }
        }

        $instance->Street = substr(implode(', ', $street), 0, 35);
        $instance->ZIPCode = substr($address->postal, 0, 10);
        $instance->City = substr($address->city, 0, 35);
        $instance->CountryCode = strtoupper(substr($address->country, 0, 2));

        if (!empty($address->phone)) {
            $instance->PhoneNo = substr($address->phone, 0, 21);
        }

        return $instance;
    }
}
