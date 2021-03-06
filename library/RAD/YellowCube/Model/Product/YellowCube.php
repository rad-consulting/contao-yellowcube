<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 */
namespace RAD\YellowCube\Model\Product;

use RAD\Fulfillment\Model\Product\Fulfillment;

/**
 * Class YellowCube
 */
class YellowCube extends Fulfillment
{
    /**
     * @inheritdoc
     */
    public function getArticleNo()
    {
        return $this->getId();
    }
}
