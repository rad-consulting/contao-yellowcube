<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

// Models
\Isotope\Model\Product::registerModelType('yellowcube', 'RAD\\YellowCube\\Model\\Product\\YellowCube');

// Subscribers
$GLOBALS['RAD_SUBSCRIBERS'][] = 'RAD\\YellowCube\\Service';
$GLOBALS['TL_CRON']['daily'][] = array('RAD\\YellowCube\\Service', 'exportAssortment');

// Maintenance
array_insert($GLOBALS['TL_MAINTENANCE'], 2, 'RAD\\YellowCube\\Backend\\Export');
