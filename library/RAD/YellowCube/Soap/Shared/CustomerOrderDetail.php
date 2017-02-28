<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */
namespace RAD\YellowCube\Soap\Shared;

/**
 * Class CustomerOrderDetail
 */
class CustomerOrderDetail
{
    /**
     * @var int
     */
    protected $BVPosNo;

    /**
     * @var int
     */
    protected $CustomerOrderPosNo;

    /**
     * @var string
     */
    protected $YCArticleNo;

    /**
     * @var string
     */
    protected $ArticleNo;

    /**
     * @var string
     */
    protected $EAN;

    /**
     * @var string
     */
    protected $Lot;

    /**
     * @var string
     */
    protected $Plant;

    /**
     * @var string
     */
    protected $StorageLocation;

    /**
     * @var int
     */
    protected $TransactionType;

    /**
     * @var string
     */
    protected $StockType;


    protected $QuantityUOM;

    /**
     * @var string
     */
    protected $ReturnReason;

    /**
     * @var string
     */
    protected $SerialNumbers;
}
