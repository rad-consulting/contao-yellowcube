<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */
namespace RAD\YellowCube\Soap\Request\ART;

use ArrayObject;
use Contao\Model\Collection;
use RAD\YellowCube\Config;
use RAD\YellowCube\Model\Product\YellowCube;

/**
 * Class ArticleList
 */
class ArticleList implements \Iterator
{
    protected $key = 0;
    protected $articles = array();
    protected $Article;

    /**
     * @param Collection $collection
     * @param Config     $config
     * @return ArticleList
     */
    public static function factory(Collection $collection, Config $config)
    {
        $instance = new static();

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
     * @return mixed
     */
    public function current()
    {
        $class = new \stdClass();

        return $class->Article = $this->articles[$this->key];
    }

    /**
     * @return string
     */
    public function key()
    {
        return 'Article';
    }

    public function next()
    {
        $this->key++;
    }

    public function rewind()
    {
        $this->key = 0;
    }

    public function valid()
    {
        return isset($this->articles[$this->key]) && $this->articles[$this->key] instanceof Article;
    }
}
