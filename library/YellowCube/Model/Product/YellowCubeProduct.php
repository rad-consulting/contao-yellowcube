<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */
namespace YellowCube\Model\Product;

use Exception;
use Isotope\Model\Product\Standard;
use YellowCube\ArticleInterface;
use YellowCube\Model\LogModel;

/**
 * Class YellowCubeProduct
 *
 * @property int   $id
 * @property int   $ean
 * @property int   $yc_export
 * @property int   $yc_exported
 * @property int   $yc_stock
 * @property int   $yc_updated
 * @property float $yc_weightGross
 * @property float $yc_weightNet
 * @property float $yc_width
 * @property float $yc_length
 * @property float $yc_height
 */
class YellowCubeProduct extends Standard implements ArticleInterface
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
        return $this->yc_stock;
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

    /**
     * @return bool
     */
    public function doExport()
    {
        return (bool)$this->yc_export;
    }

    /**
     * @inheritdoc
     */
    public function isExported()
    {
        return (bool)$this->yc_exported;
    }

    /**
     * @param bool $exported
     * @return $this
     */
    public function setExported($exported = true)
    {
        $this->yc_exported = (int)$exported;

        return $this;
    }

    /**
     * @param string|Exception $message
     * @param int              $level
     * @param string|null      $data
     * @return $this
     */
    public function log($message, $level = LogModel::INFO, $data = null)
    {
        if ($message instanceof Exception) {
            $level = $message->getCode();
            $message = $message->getMessage();
        }

        LogModel::log($this, $message, $level, $data);

        return $this;
    }
}
