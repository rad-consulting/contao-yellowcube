<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */
namespace RAD\YellowCube\Soap\Response\BAR;

use RAD\YellowCube\Soap\Unit\Quantity;

/**
 * Class Article
 */
class Article
{
    /**
     * @var string
     */
    protected $YCArticleNo;

    /**
     * @var string
     */
    protected $EAN;

    /**
     * @var string
     */
    protected $ArticleNo;

    /**
     * @var string
     */
    protected $ArticleDescription;

    /**
     * @var string
     */
    protected $Plant;

    /**
     * @var string
     */
    protected $StorageLocation;

    /**
     * @var string
     */
    protected $YCLot;

    /**
     * @var string
     */
    protected $Lot;

    /**
     * @var string
     */
    protected $BestBeforeDate;

    /**
     * @var string
     */
    protected $ProductionDate;

    /**
     * @var string
     */
    protected $StockType;

    /**
     * @var Quantity
     */
    protected $QuantityUOM;

    /**
     * @return string
     */
    public function getArticleNo()
    {
        return $this->ArticleNo;
    }

    /**
     * @return mixed
     */
    public function getStock()
    {
        return $this->QuantityUOM->getValue();
    }

    /**
     * @return string
     */
    public function getSKU()
    {
        return $this->YCArticleNo;
    }
}
