<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */
namespace RAD\YellowCube\Soap\Response\WAR;

use RAD\YellowCube\Soap\Shared\CustomerOrderDetail;
use RAD\YellowCube\Soap\Shared\CustomerOrderHeader;

/**
 * Class Issue
 */
class Issue
{
    /**
     * @var IssueHeader
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
     * @return IssueHeader
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
    public function getOrderDetails()
    {
        return $this->CustomerOrderList;
    }
}
