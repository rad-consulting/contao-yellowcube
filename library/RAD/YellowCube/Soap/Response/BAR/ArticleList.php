<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */
namespace RAD\YellowCube\Soap\Response\BAR;

use Iterator;

/**
 * Class ArticleList
 */
class ArticleList implements Iterator
{
    /**
     * @var Article[]
     */
    protected $Article = array();

    /**
     * @var int
     */
    protected $key = 0;

    /**
     * @return Article
     */
    public function current()
    {
        return $this->Article[$this->key];
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

    public function valid()
    {
        return isset($this->Article[$this->key]);
    }

    /**
     * @return void
     */
    public function rewind()
    {
        $this->key = 0;
    }
}
