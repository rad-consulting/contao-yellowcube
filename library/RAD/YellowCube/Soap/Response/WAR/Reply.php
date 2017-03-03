<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */
namespace RAD\YellowCube\Soap\Response\WAR;

/**
 * Class Reply
 */
class Reply
{
    /**
     * @var \stdClass
     */
    protected $GoodsIssueHeader;

    /**
     * @var Header
     */
    protected $CustomerOrderHeader;

    /**
     * @var \stdClass
     */
    protected $CustomerOrderList;

    /**
     * @return Detail[]
     */
    public function getDetails()
    {
        return $this->CustomerOrderList->CustomerOrderDetail;
    }

    /**
     * @return Header
     */
    public function getHeader()
    {
        return $this->CustomerOrderHeader;
    }

    /**
     * @return string
     */
    public function getOrderId()
    {
        return $this->getHeader()->getOrderId();
    }

    /**
     * @return string
     */
    public function getTracking()
    {
        return $this->getHeader()->getTracking();
    }
}
