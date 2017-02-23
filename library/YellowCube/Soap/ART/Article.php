<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */
namespace YellowCube\Soap\ART;

use YellowCube\ArticleInterface;

/**
 * Class Article
 */
class Article
{
    /**
     * @var int
     */
    protected $EAN;

    /**
     * @var int
     */
    protected $ArticleNo;

    /**
     * @var string
     */
    protected $ChangeFlag;

    /**
     * @param ArticleInterface $article
     * @return static
     */
    public static function factory(ArticleInterface $article)
    {
        $instance = new static();
        $instance->EAN = $article->getEAN();
        $instance->ArticleNo = $article->getArticleNo();
        $instance->ChangeFlag = $article->isExported() ? 'U' : 'I';

        return $instance;
    }
}
