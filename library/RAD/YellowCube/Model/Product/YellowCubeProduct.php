<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */
namespace RAD\YellowCube\Model\Product;

use RAD\Fulfillment\Model\Product\FulfillmentProduct;

/**
 * Class YellowCubeProduct
 *
 * @property int   $ean
 * @property float $rad_weightGross
 * @property float $rad_weightNet
 * @property float $rad_width
 * @property float $rad_length
 * @property float $rad_height
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
        return $this->rad_height;
    }

    /**
     * @return float
     */
    public function getLength()
    {
        return $this->rad_length;
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
        return $this->rad_weightGross;
    }

    /**
     * @return float
     */
    public function getWeightNet()
    {
        return $this->rad_weightNet;
    }

    /**
     * @return float
     */
    public function getWidth()
    {
        return $this->rad_width;
    }
}
