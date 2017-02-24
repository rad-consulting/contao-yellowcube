<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */
namespace RAD\Fulfillment\Model\Product;

use Exception;
use Isotope\Model\Product\Standard;
use RAD\Logging\Model\LogModel;

/**
 * Class FulfillmentProduct
 *
 * @property int    $id
 * @property int    $rad_export
 * @property int    $rad_exported
 * @property int    $rad_stock
 * @property int    $rad_updated
 * @property string $rad_sku
 */
class FulfillmentProduct extends Standard
{
    /**
     * @return string
     */
    public function getDistributorSKU()
    {
        return $this->rad_sku;
    }

    /**
     * @return int
     */
    public function getDistributorStock()
    {
        return $this->rad_stock;
    }

    /**
     * @param int $stock
     * @return $this
     */
    public function setDistributorStock($stock)
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
            $this->log($message, LogModel::INFO, $data);
        }

        $this->rad_exported = (int)$exported;

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
