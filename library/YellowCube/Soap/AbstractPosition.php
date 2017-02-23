<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */
namespace YellowCube\Soap;

use YellowCube\Config;
use YellowCube\Model\Product\YellowCubeProduct;

/**
 * Class AbstractPosition
 */
abstract class AbstractPosition
{
    /**
     * @var int
     */
    protected $PosNo;

    /**
     * @param YellowCubeProduct $product
     * @param int               $quantity
     * @param int               $no
     * @param Config            $config
     * @return static
     */
    public static function factory(YellowCubeProduct $product, $quantity, $no, Config $config)
    {
        $instance = new static();
        $instance->PosNo = $no * 10;

        return $instance;
    }
}
