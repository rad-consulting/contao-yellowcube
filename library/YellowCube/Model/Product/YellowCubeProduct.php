<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */
namespace YellowCube\Model\Product;

use Fulfillment\Model\Product\FulfillmentProduct;

/**
 * Class YellowCubeProduct
 *
 * @property int   $ean
 * @property float $yc_weightGross
 * @property float $yc_weightNet
 * @property float $yc_width
 * @property float $yc_length
 * @property float $yc_height
 */
class YellowCubeProduct extends FulfillmentProduct
{
    /**
     * @inheritdoc
     */
    public function getArticleNo()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getEAN()
    {
        return $this->ean;
    }

    /**
     * @return float
     */
    public function getHeight()
    {
        return $this->yc_height;
    }

    /**
     * @return float
     */
    public function getLength()
    {
        return $this->yc_length;
    }

    /**
     * @return int
     */
    public function getStock()
    {
        return $this->getDistributorStock();
    }

    /**
     * @param int $stock
     * @return $this
     */
    public function setStock($stock)
    {
        $this->setDistributorStock($stock);

        return $this;
    }

    /**
     * @return float
     */
    public function getWeightGross()
    {
        return $this->yc_weightGross;
    }

    /**
     * @return float
     */
    public function getWeightNet()
    {
        return $this->yc_weightNet;
    }

    /**
     * @return float
     */
    public function getWidth()
    {
        return $this->yc_width;
    }
}
