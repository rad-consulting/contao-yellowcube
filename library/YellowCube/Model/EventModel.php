<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */
namespace YellowCube\Model;

use Exception;
use Contao\Model;

/**
 * Class EventModel
 *
 * @property int    $id
 * @property int    $pid
 * @property int    $tstamp
 * @property int    $attempt
 * @property int    $timeout
 * @property int    $error
 * @property int    $status
 * @property string $ptable
 * @property string $argument
 */
class EventModel extends Model
{
    /**
     * @const int
     */
    const WAITING = 0;
    const RUNNING = 1;

    /**
     * @var string
     */
    public static $strTable = 'tl_yc_event';

    /**
     * @param string     $name
     * @param Model      $subject
     * @param array|null $arguments
     * @param int        $timeout
     * @return static
     */
    public static function factory($name, Model $subject, array $arguments = null, $timeout = 295)
    {
        $instance = new static();
        $instance->name = $name;
        $instance->pid = $subject->id;
        $instance->ptable = $subject::getTable();
        $instance->tstamp = time();
        $instance->status = static::WAITING;
        $instance->argument = $arguments ? serialize($arguments) : null;
        $instance->timeout = $timeout;

        return $instance;
    }

    /**
     * @param string     $name
     * @param mixed|null $default
     * @return mixed|null
     */
    public function getArgument($name, $default = null)
    {
        $arguments = $this->getArguments();

        if (isset($arguments[$name]) && !empty($arguments[$name])) {
            return $arguments[$name];
        }

        return $default;
    }

    /**
     * @param string $name
     * @param mixed  $value
     * @return $this
     */
    public function setArgument($name, $value)
    {
        $arguments = $this->getArguments();
        $arguments[$name] = $value;
        $this->setArguments($arguments);

        return $this;
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        return deserialize($this->argument, true);
    }

    /**
     * @param array $arguments
     * @return $this
     */
    public function setArguments(array $arguments)
    {
        $this->argument = serialize($arguments);

        return $this;
    }

    /**
     * @return Model|null
     */
    public function getSubject()
    {
        if (!empty($this->pid) || empty(!$this->ptable)) {
            $class = $GLOBALS['TL_MODELS'][$this->ptable];
            $subject = forward_static_call(array($class, 'findByPk'), $this->pid);

            if ($subject instanceof Model) {
                return $subject;
            }
        }

        return null;
    }

    /**
     * @param Model $subject
     * @return $this
     */
    public function setSubject(Model $subject)
    {
        $this->pid = $subject->id;
        $this->ptable = $subject::getTable();

        return $this;
    }

    /**
     * @return bool
     */
    public function isError()
    {
        return (bool)$this->error;
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

    /**
     * @return int
     */
    public function delete()
    {
        LogModel::deleteByModel($this);

        return parent::delete();
    }

    /**
     * @return $this
     */
    public function run()
    {
        $this->status = static::RUNNING;
        $this->attempt++;
        $this->error = 0;
        $this->tstamp = time();
        $this->save();

        return $this;
    }

    /**
     * @return $this
     */
    public function wait()
    {
        $this->status = static::WAITING;
        $this->save();

        return $this;
    }

    /**
     * @return bool
     */
    public function isRunning()
    {
        return static::RUNNING == $this->status;
    }
}
