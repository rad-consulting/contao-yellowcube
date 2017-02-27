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
class ArticleList extends ArrayObject
{
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
        $this->append($article);

        return $this;
    }

    /**
     * @param mixed $index
     * @return array
     */
    public function offsetGet($index)
    {
        return array('Article' => parent::offsetGet($index));
    }
}
