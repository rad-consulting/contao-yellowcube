<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */
namespace RAD\YellowCube\Soap\Response\WAR;

/**
 * Class GoodsIssue
 */
class GoodsIssue
{
    /**
     * @var GoodsIssueHeader
     */
    protected $GoodsIssueHeader;

    /**
     * @var CustomerOrderHeader
     */
    protected $CustomerOrderHeader;

    /**
     * @var CustomerOrderDetail[]
     */
    protected $CustomerOrderList = array();

    /**
     * @return GoodsIssueHeader
     */
    public function getIssueHeader()
    {
        return $this->GoodsIssueHeader;
    }

    /**
     * @return CustomerOrderHeader
     */
    public function getOrderHeader()
    {
        return $this->CustomerOrderHeader;
    }

    /**
     * @return CustomerOrderDetail[]
     */
    public function getOrderList()
    {
        return $this->CustomerOrderList;
    }
}
