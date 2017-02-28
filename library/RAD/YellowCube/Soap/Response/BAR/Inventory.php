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
     * @var Header
     */
    protected $InventoryHeader;

    /**
     * ArticleList
     */
    protected $ArticleList;

    /**
     * @return ArticleList
     */
    public function getArticles()
    {
        return $this->ArticleList;
    }
}
