<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */
namespace YellowCube;

/**
 * Interface ArticleInterface
 */
interface ArticleInterface
{
    /**
     * @return int
     */
    public function getArticleNo();

    /**
     * @return int
     */
    public function getEAN();

    /**
     * @return bool
     */
    public function isExported();
}
