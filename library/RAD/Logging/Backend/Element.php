<?php
/**
 * Contao extension for RAD Consulting GmbH
 *
 * @copyright  RAD Consulting GmbH 2016
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */
namespace RAD\Log\Backend;

use Contao\Backend;
use Contao\Config;
use RAD\Log\Model\LogModel as Log;

/**
 * Class Element
 */
class Element extends Backend
{
    /**
     * @param array $row
     * @return string
     */
    public function showLog(array &$row)
    {
        $class = 'limit_height';

        if (!Config::get('doNotCollapse')) {
            $class .= ' h64';
        }

        switch ($row['type']) {
            case Log::EMERGENCY:
            case Log::ALERT:
            case Log::CRITICAL:
            case Log::ERROR:
                $color = 'tl_red';
                break;
            case Log::INFO:
                $color = 'tl_green';
                break;

            default:
                $color = '';
        }

        if (empty($row['data'])) {
            return implode('', array(
                '<div class="' . $color . '">' . date('Y-m-d H:i:s', $row['tstamp']) . ' - ' . $row['type'] . '</div>',
                '<div><p><strong>' . $row['message'] . '</strong></p></div>',
            ));
        }

        return implode('', array(
            '<div class="' . $color . '">' . date('Y-m-d H:i:s', $row['tstamp']) . ' - ' . $row['type'] . '</div>',
            '<div class="' . trim($class) . '"><p><strong>' . $row['message'] . '</strong></p><pre>' . htmlentities($row['data']) . '</pre></div>',
        ));
    }
}
