<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */
namespace RAD\YellowCube\Soap\Request\ART;

use ArrayAccess, Iterator;
use Contao\Model\Collection;
use RAD\YellowCube\Config;
use RAD\YellowCube\Model\Product\YellowCube;

/**
 * Class ArticleList
 */
class ArticleList implements Iterator, ArrayAccess
{
    /**
     * @var int
     */
    protected $key = 0;

    /**
     * @var Article[]
     */
    protected $articles = array();

    /**
     * @param Collection $collection
     * @param Config     $config
     * @return ArticleList
     */
    public static function factory(Collection $collection, Config $config)
    {
        $instance = new self();

        foreach ($collection as $item) {
            if ($item instanceof YellowCube) {
                if ($item->hasVariants()) {
                    $variants = YellowCube::findMultipleByIds($item->getVariantIds());

                    if ($variants instanceof Collection) {
                        foreach ($variants as $variant) {
                            if ($variant instanceof YellowCube) {
                                $instance->addArticle(Article::factory($variant, $config));
                            }
                        }
                    }

                    continue;
                }

                $instance->addArticle(Article::factory($item, $config));
            }
        }

        return $instance;
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
     * @return array
     */
    public function current()
    {
        return array('Article' => $this->articles[$this->key()]);
    }

    /**
     * @return int
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
        if (!isset($this->articles[$this->key])) {
            return false;
        }

        return $this->articles[$this->key] instanceof Article;
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->articles[$offset]);
    }

    /**
     * @param mixed $offset
     * @return Article
     */
    public function offsetGet($offset)
    {
        return $this->articles[$offset];
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->articles[$offset] = $value;
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->articles[$offset]);
    }
}
