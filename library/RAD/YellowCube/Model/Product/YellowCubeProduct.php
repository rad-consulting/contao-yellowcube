<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */
namespace RAD\YellowCube\Model\Product;

use RAD\Fulfillment\Model\Product\FulfillmentProduct;

/**
 * Class YellowCubeProduct
 */
class YellowCubeProduct extends FulfillmentProduct
{
    /**
     * @inheritdoc
     */
    public function getArticleNo()
    {
        return $this->getId();
    }
}
