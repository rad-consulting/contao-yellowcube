<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */
namespace Fulfillment\Model\Product;

use Exception;
use Isotope\Model\Product\Standard;
use Logging\Model\LogModel;

/**
 * Class FulfillmentProduct
 *
 * @property int    $id
 * @property int    $ff_export
 * @property int    $ff_exported
 * @property int    $ff_stock
 * @property int    $ff_updated
 * @property string $ff_sku
 */
class FulfillmentProduct extends Standard
{
    /**
     * @return string
     */
    public function getDistributorSKU()
    {
        return $this->ff_sku;
    }

    /**
     * @return int
     */
    public function getDistributorStock()
    {
        return $this->ff_stock;
    }

    /**
     * @param int $stock
     * @return $this
     */
    public function setDistributorStock($stock)
    {
        $this->ff_stock = (int)$stock;

        return $this;
    }

    /**
     * @return bool
     */
    public function doExport()
    {
        return (bool)$this->ff_export;
    }

    /**
     * @inheritdoc
     */
    public function isExported()
    {
        return (bool)$this->ff_exported;
    }

    /**
     * @param bool $exported
     * @return $this
     */
    public function setExported($exported = true)
    {
        $this->ff_exported = (int)$exported;

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
