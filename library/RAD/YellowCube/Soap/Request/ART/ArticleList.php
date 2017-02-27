<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */
namespace RAD\YellowCube\Soap\Request\ART;

use Contao\Model\Collection;
use Contao\System;
use Iterator;
use RAD\YellowCube\Config;
use RAD\YellowCube\Model\Product\YellowCube;

/**
 * Class ArticleList
 */
class ArticleList implements Iterator
{
    /**
     * @var \Closure
     */
    protected $Article;

    /**
     * @var array
     */
    protected $articles = array();

    /**
     * @var int
     */
    protected $key = 0;

    /**
     * @param Collection $collection
     * @param Config     $config
     * @return static
     */
    public static function factory(Collection $collection, Config $config)
    {
        $instance = new static();

        foreach ($collection as $product) {
            if ($product instanceof YellowCube) {
                $instance->addArticle(Article::factory($product, $config));
            }
        }

        return $instance;
    }

    /**
     * ArticleList constructor.
     */
    public function __construct()
    {
        $this->Article = function () {
            System::log(count($this->articles), __METHOD__, TL_ERROR);
            return $this->articles[$this->key++];
        };
    }

    /**
     * @param Article $article
     * @return $this
     */
    public function addArticle(Article $article)
    {
        $this->articles[] = $article;

        return $this;
    }

    /**
     * @return Article
     */
    public function current()
    {
        return $this->articles[$this->key];
    }

    /**
     * @return string
     */
    public function key()
    {
        return $this->key;
    }

    /**
     * @return void
     */
    public function next()
    {
        $this->key++;
    }

    /**
     * @return void
     */
    public function rewind()
    {
        $this->key = 0;
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return isset($this->articles[$this->key]) && $this->articles[$this->key] instanceof Article;
    }
}
