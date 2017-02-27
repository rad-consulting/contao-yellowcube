<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */
namespace RAD\YellowCube\Soap\Request\ART;

use Contao\Model\Collection;
use RAD\YellowCube\Config;
use RAD\YellowCube\Model\Product\YellowCube;

/**
 * Class ArticleList
 */
class ArticleList
{
    /**
     * @var Article[]
     */
    protected $Article = array();

    /**
     * @param Collection $collection
     * @param Config     $config
     * @return static
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
                                $instance->Article[] = Article::factory($variant, $config);
                            }
                        }
                    }

                    continue;
                }

                $instance->Article[] = Article::factory($item, $config);
            }
        }

        return $instance;
    }
}
