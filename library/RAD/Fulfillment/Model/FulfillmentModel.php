<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */
namespace RAD\Fulfillment\Model;

use Exception;
use Contao\Model;
use Isotope\Model\ProductCollection\Order;
use RAD\Logging\Model\LogModel;

/**
 * Class FulfillmentModel
 *
 * @property int    $id
 * @property int    $pid
 * @property int    $status
 * @property int    $tstamp
 * @property string $reference
 * @property string $tracking
 */
class FulfillmentModel extends Model
{
    /**
     * @const int
     */
    const PENDING = 0;
    const SENT = 1;
    const CONFIRMED = 2;
    const DELIVERED = 4;
    const COMPLETED = 8;

    /**
     * @var string
     */
    public static $strTable = 'tl_rad_fulfillment';

    /**
     * @param Order $order
     * @return static
     */
    public static function factory(Order $order)
    {
        $instance = new static();
        $instance->pid = $order->id;
        $instance->status = static::PENDING;
        $instance->tstamp = time();

        return $instance;
    }

    /**
     * @return Order
     */
    public function getOrder()
    {
        return Order::findByPk($this->pid);
    }

    /**
     * @return $this
     */
    public function setCompleted()
    {
        $this->status = static::COMPLETED;

        return $this;
    }

    /**
     * @param string|null $reference
     * @param string|null $message
     * @param string|null $data
     * @return $this
     */
    public function setConfirmed($reference = null, $message = null, $data = null)
    {
        if ($reference) {
            $this->reference = $reference;
        }

        if ($message) {
            $this->log($message, LogModel::INFO, $data);
        }

        $this->status = static::CONFIRMED;

        return $this;
    }

    /**
     * @param string|null $tracking
     * @param string|null $message
     * @param string|null $data
     * @return $this
     */
    public function setDelivered($tracking = null, $message = null, $data = null)
    {
        if ($tracking) {
            $this->tracking = $tracking;
        }

        if ($message) {
            $this->log($message, LogModel::INFO, $data);
        }

        $this->status = static::DELIVERED;

        return $this;
    }

    /**
     * @param string|null $reference
     * @param string|null $message
     * @param string|null $data
     * @return $this
     */
    public function setSent($reference = null, $message = null, $data = null)
    {
        if ($reference) {
            $this->reference = $reference;
        }

        if ($message) {
            $this->log($message, LogModel::INFO, $data);
        }

        $this->status = static::SENT;

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
