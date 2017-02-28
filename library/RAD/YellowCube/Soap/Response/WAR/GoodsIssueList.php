<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */
namespace RAD\YellowCube\Soap\Response\WAR;

/**
 * Class GoodsIssueList
 */
class GoodsIssueList
{
    /**
     * @var GoodsIssue[]
     */
    protected $WAR = array();

    /**
     * @return GoodsIssue[]
     */
    public function getIssues()
    {
        return $this->WAR;
    }

    /**
     * @return string
     */
    public function getTracking()
    {
        $trackings = array();

        foreach ($this->getIssues() as $issue) {
            $tracking = $issue->getOrderHeader()->getTracking();

            if (!empty($tracking)) {
                $trackings[] = $tracking;
            }
        }

        return implode(';', $trackings);
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        return (bool)count($this->WAR);
    }
}
