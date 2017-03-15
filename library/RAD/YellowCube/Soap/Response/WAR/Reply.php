<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
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
    protected $GoodsIssue;

    /**
     * @return Header
     */
    public function getHeader()
    {
        return $this->GoodsIssue->CustomerOrderHeader;
    }

    /**
     * @return Detail[]
     */
    public function getDetails()
    {
        return $this->GoodsIssue->CustomerOrderList->CustomerOrderList;
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
