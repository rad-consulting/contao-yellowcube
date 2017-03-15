<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 */
namespace RAD\YellowCube\Soap\Request\WBL;

use RAD\YellowCube\Config;
use RAD\YellowCube\Model\Product\YellowCube as Model;
use RAD\YellowCube\Soap\Unit\ISO;

/**
 * Class Position
 */
class Position
{
    /**
     * @var int
     */
    protected $PosNo;

    /**
     * @var int
     */
    protected $ArticleNo;

    /**
     * @var int
     */
    protected $Quantity;

    /**
     * @var string
     */
    protected $QuantityISO;

    /**
     * @param Model  $product
     * @param int    $quantity
     * @param Config $config
     * @return static
     */
    public static function factory(Model $product, $quantity, Config $config)
    {
        $instance = new static();
        $instance->ArticleNo = $product->getArticleNo();
        $instance->Quantity = $quantity;
        $instance->QuantityISO = $product->getUnit();

        // Make sure PK will be PAK
        if ('PK' == $instance->QuantityISO) {
            $instance->QuantityISO = 'PAK';
        }

        return $instance;
    }

    /**
     * @return int
     */
    public function getPosNo()
    {
        return $this->PosNo;
    }

    /**
     * @param int $PosNo
     * @return $this
     */
    public function setPosNo($PosNo)
    {
        $this->PosNo = $PosNo * 10;

        return $this;
    }
}
