<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */
namespace RAD\YellowCube\Soap\Request\WAB;

use Isotope\Model\Address as ShopAddress;
use RAD\YellowCube\Config;

/**
 * Class Partner
 */
class Partner
{
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
        $instance->Title = $address->salutation;

        if (empty($address->company)) {
            $instance->Name1 = $address->firstname . ' ' . $address->lastname;
        }
        else {
            $instance->Name1 = $address->company;
            $instance->Name2 = $address->firstname . ' ' . $address->lastname;
        }

        $street = array();

        for ($i = 1; $i <= 3; $i++) {
            if (!empty($address->{"street{$i}"})) {
                $street[] = $address->{"street{$i}"};
            }
        }

        $instance->Street = implode(', ', $street);
        $instance->ZIPCode = $address->postal;
        $instance->City = $address->city;
        $instance->CountryCode = strtoupper($address->country);

        if (!empty($address->phone)) {
            $instance->PhoneNo = $address->phone;
        }

        return $instance;
    }
}
