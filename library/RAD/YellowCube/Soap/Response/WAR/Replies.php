<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */
namespace RAD\YellowCube\Soap\Response\WAR;

use Iterator, Countable;

/**
 * Class Replies
 */
class Replies implements Iterator, Countable
{
    /**
     * @var Reply[]
     */
    protected $WAR = array();

    /**
     * @var int
     */
    protected $key = 0;

    /**
     * @return bool
     */
    public function isSuccess()
    {
        return (bool)$this->count();
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->WAR);
    }

    /**
     * @return Reply
     */
    public function current()
    {
        return $this->WAR[$this->key];
    }

    /**
     * @return void
     */
    public function next()
    {
        $this->key++;
    }

    /**
     * @return int
     */
    public function key()
    {
        return $this->key;
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return $this->WAR[$this->key] instanceof Reply;
    }

    /**
     * @return void
     */
    public function rewind()
    {
        $this->key = 0;
    }
}
