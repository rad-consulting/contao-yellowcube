<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */
namespace RAD\Fulfillment\Model\Product;

use Exception;
use Haste\Units\Mass\Weight;
use Isotope\Model\Product\Standard;
use RAD\Log\Model\LogModel as Log;

/**
 * Class FulfillmentProduct
 *
 * @property int    $id
 * @property int    $rad_ean
 * @property int    $rad_export
 * @property int    $rad_exported
 * @property int    $rad_stock
 * @property int    $rad_updated
 * @property float  $rad_width
 * @property float  $rad_weight_net
 * @property float  $rad_length
 * @property float  $rad_height
 * @property string $rad_sku
 * @property string $rad_volume
 */
class FulfillmentProduct extends Standard
{
    /**
     * @inheritdoc
     */
    public function getEAN()
    {
        return $this->rad_ean;
    }

    /**
     * @return string
     */
    public function getSKU()
    {
        return $this->rad_sku;
    }

    /**
     * @return int
     */
    public function getStock()
    {
        return $this->rad_stock;
    }

    /**
     * @param int $stock
     * @return $this
     */
    public function setStock($stock)
    {
        $this->rad_stock = (int)$stock;

        return $this;
    }

    /**
     * @return bool
     */
    public function doExport()
    {
        return (bool)$this->rad_export;
    }

    /**
     * @inheritdoc
     */
    public function isExported()
    {
        return (bool)$this->rad_exported;
    }

    /**
     * @param bool        $exported
     * @param string|null $message
     * @param string|null $data
     * @return $this
     */
    public function setExported($exported = true, $message = null, $data = null)
    {
        if ($message) {
            $this->log($message, Log::INFO, $data);
        }

        $this->rad_exported = (int)$exported;

        return $this;
    }

    /**
     * @param bool        $updated
     * @param string|null $message
     * @param string|null $data
     * @return $this
     */
    public function setUpdated($updated = true, $message = null, $data = null)
    {
        if ($message) {
            $this->log($message, Log::INFO, $data);
        }

        $this->rad_updated = $updated ? time() : 0;

        return $this;
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
     * @return float
     */
    public function getWidth()
    {
        return $this->rad_width;
    }

    /**
     * @return \Haste\Units\Mass\Weight
     */
    public function getWeight()
    {
        return parent::getWeight();
    }

    /**
     * @return \Haste\Units\Mass\Weight
     */
    public function getWeightGross()
    {
        return $this->getWeight();
    }

    /**
     * @return \Haste\Units\Mass\Weight
     */
    public function getWeightNet()
    {
        return Weight::createFromTimePeriod($this->rad_weight_net);
    }

    /**
     * @return float
     */
    public function getVolume()
    {
        return $this->rad_volume;
    }

    /**
     * @param string|Exception $message
     * @param int              $level
     * @param string|null      $data
     * @return $this
     */
    public function log($message, $level = Log::INFO, $data = null)
    {
        if ($message instanceof Exception) {
            $level = $message->getCode();
            $message = $message->getMessage();
        }

        Log::log($this, $message, $level, $data);

        return $this;
    }
}
