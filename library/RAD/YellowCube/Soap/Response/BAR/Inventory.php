<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */
namespace RAD\YellowCube\Soap\Response\BAR;

/**
 * Class Inventory
 */
class Inventory
{
    /**
     * @var \stdClass
     */
    protected $ArticleList;

    /**
     * @return Article[]
     */
    public function getArticles()
    {
        return $this->ArticleList->Article;
    }

    /**
     * @return bool
     */
    public function hasArticles()
    {
        return (bool)count($this->ArticleList->Article);
    }
}
