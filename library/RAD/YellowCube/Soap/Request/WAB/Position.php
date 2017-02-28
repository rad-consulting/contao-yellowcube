<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */
namespace RAD\YellowCube\Soap\Request\WAB;

use Isotope\Model\ProductCollectionItem as ShopPosition;
use RAD\YellowCube\Config;
use RAD\YellowCube\Model\Product\YellowCube;
use RAD\YellowCube\Soap\Unit\ISO;

/**
 * Class Position
 */
class Position
{
    /**
     * @var int
     */
    protected $EAN;

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
     * @var string
     */
    protected $Plant;

    /**
     * @var string
     */
    protected $ShortDescription;

    /**
     * @param ShopPosition $position
     * @param int          $no
     * @param Config       $config
     * @return static
     */
    public static function factory(ShopPosition $position, $no, Config $config)
    {
        $instance = new static();
        $product = $position->getProduct();

        if ($product instanceof YellowCube) {
            $instance->ArticleNo = $product->getId();
            $instance->PosNo = $no * 10;
            $instance->Plant = $config->get('plantid');
            $instance->Quantity = $position->quantity;
            $instance->QuantityISO = ISO::PCE;

            if ('P' == $config->get('operatingmode')) {
                $instance->EAN = $product->getEAN()->getValue();
            }
        }

        return $instance;
    }
}
