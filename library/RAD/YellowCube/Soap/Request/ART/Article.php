<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 */
namespace RAD\YellowCube\Soap\Request\ART;

use RAD\YellowCube\Config;
use RAD\YellowCube\Model\Product\YellowCube as Model;
use RAD\YellowCube\Soap\Unit\EAN;
use RAD\YellowCube\Soap\Unit\Mass;
use RAD\YellowCube\Soap\Unit\ISO;
use RAD\YellowCube\Soap\Util\SimpleValue;

/**
 * Class Article
 *
 * @see https://service.swisspost.ch/apache/yellowcube/YellowCube_ART_REQUEST_Artikelstamm.xsd
 */
class Article
{
    /**
     * @var int
     */
    protected $EAN;

    /**
     * @var int
     */
    protected $ArticleNo;

    /**
     * @var string
     */
    protected $ChangeFlag;

    /**
     * @var string
     */
    protected $PlantID;

    /**
     * @var string
     */
    protected $BaseUOM;

    /**
     * @var string
     */
    protected $DepositorNo;

    /**
     * @var SimpleValue
     */
    protected $NetWeight;

    /**
     * @var int
     */
    protected $BatchMngtReq;

    /**
     * @var int
     */
    protected $MinRemLife;

    /**
     * @var int
     */
    protected $PeriodExpDateType;

    /**
     * @var int
     */
    protected $SerialNoFlag;

    /**
     * @var SimpleValue[]
     */
    protected $UnitsOfMeasure = array();

    /**
     * @var Description[]
     */
    protected $ArticleDescriptions = array();

    /**
     * @param Model  $model
     * @param Config $config
     * @return static
     */
    public static function factory(Model $model, Config $config)
    {
        $instance = new static();

        // Make sure PK will be PAK
        $unit = $model->getUnit();

        if ('PK' == $unit) {
            $unit = 'PAK';
        }

        // Mandatory
        $instance->ChangeFlag = $model->getExport();
        $instance->DepositorNo = $config->get('depositorno');
        $instance->PlantID = $config->get('plantid');
        $instance->ArticleNo = $model->getArticleNo();
        $instance->BaseUOM = $unit;

        if ($weight = $model->getWeight()) {
            $instance->NetWeight = new Mass(round($weight->getWeightValue(), 3), $weight->getWeightUnit());
        }
        else {
            $instance->NetWeight = new Mass(0, ISO::KGM);
        }

        $instance->UnitsOfMeasure['AlternateUnitISO'] = $unit;
        $instance->addArticleDescription(new Description($model->getName(), 'de'));

        // Optional
        if ('p' == $config->get('operatingmode') || $config->get('eanalways')) {
            $instance->addUnitOfMeasure('EAN', new EAN($model->getEAN()->getValue(), $model->getEAN()->getUnit()));
        }

        return $instance;
    }

    /**
     * @param Description $description
     * @return $this
     */
    public function addArticleDescription(Description $description)
    {
        $this->ArticleDescriptions[] = $description;

        return $this;
    }

    /**
     * @param string      $name
     * @param SimpleValue $unit
     * @return $this
     */
    public function addUnitOfMeasure($name, SimpleValue $unit)
    {
        $this->UnitsOfMeasure[$name] = $unit;

        return $this;
    }
}
