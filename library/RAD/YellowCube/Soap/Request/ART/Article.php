<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */
namespace RAD\YellowCube\Soap\Request\ART;

use RAD\YellowCube\Config;
use RAD\YellowCube\Model\Product\YellowCubeProduct;
use RAD\YellowCube\Soap\Unit\EANUnit;
use RAD\YellowCube\Soap\Unit\GrossWeightUnit;
use RAD\YellowCube\Soap\Unit\HeightUnit;
use RAD\YellowCube\Soap\Unit\LengthUnit;
use RAD\YellowCube\Soap\Unit\NetWeightUnit;
use RAD\YellowCube\Soap\Unit\UnitInterface;
use RAD\YellowCube\Soap\Unit\VolumeUnit;
use RAD\YellowCube\Soap\Unit\WidthUnit;
use RAD\YellowCube\Soap\Util\EANType;
use RAD\YellowCube\Soap\Util\ISO;

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
     * @var UnitInterface
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
     * @var UnitInterface[]
     */
    protected $UnitsOfMeasure = array();

    /**
     * @var ArticleDescription[]
     */
    protected $ArticleDescriptions = array();

    /**
     * @param YellowCubeProduct $article
     * @param Config            $config
     * @return static
     */
    public static function factory(YellowCubeProduct $article, Config $config)
    {
        $instance = new static();

        // Mandatory
        $instance->ChangeFlag = $article->isExported() ? 'U' : 'I';
        $instance->DepositorNo = $config->get('depositorno');
        $instance->PlantID = $config->get('plantid');
        $instance->ArticleNo = $article->getArticleNo();
        $instance->BaseUOM = ISO::PCE;
        $instance->NetWeight = new NetWeightUnit($article->getWeightNet()->getWeightValue(), $article->getWeightNet()->getWeightUnit());
        $instance->UnitsOfMeasure['AlternateUnitISO'] = ISO::PCE;
        $instance->addArticleDescription(new ArticleDescription($article->getName(), 'de'));

        // Optional
        $instance->addUnitOfMeasure(new GrossWeightUnit($article->getWeightGross()->getWeightValue(), $article->getWeightGross()->getWeightUnit()));
        $instance->addUnitOfMeasure(new LengthUnit($article->getLength(), ISO::CMT));
        $instance->addUnitOfMeasure(new WidthUnit($article->getWidth(), ISO::CMT));
        $instance->addUnitOfMeasure(new HeightUnit($article->getHeight(), ISO::CMT));
        $instance->addUnitOfMeasure(new VolumeUnit($article->getVolume(), ISO::CMQ));

        if ('p' == $config->get('operatingmode')) {
            $instance->addUnitOfMeasure(new EANUnit($article->getEAN(), EANType::HE));
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
     * @param UnitInterface $unit
     * @return $this
     */
    public function addUnitOfMeasure(UnitInterface $unit)
    {
        $this->UnitsOfMeasure[$unit->getName()] = $unit;

        return $this;
    }
}
