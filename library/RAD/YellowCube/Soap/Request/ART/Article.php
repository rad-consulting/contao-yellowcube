<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
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
     * @var ArticleDescription[]
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

        // Mandatory
        $instance->ChangeFlag = $model->isExported() ? 'U' : 'I';
        $instance->DepositorNo = $config->get('depositorno');
        $instance->PlantID = $config->get('plantid');
        $instance->ArticleNo = $model->getArticleNo();
        $instance->BaseUOM = ISO::PCE;

        if ($weight = $model->getWeight()) {
            $instance->NetWeight = new Mass(round($weight->getWeightValue() * .91, 3), $weight->getWeightUnit());
        }
        else {
            $instance->NetWeight = new Mass(0, ISO::KGM);
        }

        $instance->UnitsOfMeasure['AlternateUnitISO'] = ISO::PCE;
        $instance->addArticleDescription(new ArticleDescription($model->getName(), 'de'));

        // Optional
        if ('p' == $config->get('operatingmode')) {
            $instance->addUnitOfMeasure('EAN', new EAN($model->getEAN()->getValue(), $model->getEAN()->getUnit()));
        }

        return $instance;
    }

    /**
     * @param ArticleDescription $description
     * @return $this
     */
    public function addArticleDescription(ArticleDescription $description)
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
