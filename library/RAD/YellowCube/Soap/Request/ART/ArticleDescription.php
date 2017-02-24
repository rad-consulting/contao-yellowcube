<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */
namespace RAD\YellowCube\Soap\Request\ART;

use RAD\YellowCube\Soap\Util\SimpleValue;

/**
 * Class ArticleDescription
 */
class ArticleDescription extends SimpleValue
{
    /**
     * @var string
     */
    protected $ArticleDescriptionLC;

    /**
     * @param string $value
     * @param string $language
     */
    public function __construct($value, $language)
    {
        parent::__construct(substr($value, 0, 40));
        $this->ArticleDescriptionLC = $language;
    }
}
