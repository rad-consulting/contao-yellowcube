<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

// Models
\Isotope\Model\Product::registerModelType('yellowcube', 'RAD\\YellowCube\\Model\\Product\\YellowCube');

$GLOBALS['TL_MODELS'][\RAD\YellowCube\Model\Product\YellowCube::getTable()] = 'RAD\\YellowCube\\Model\\Product\\YellowCube';

// Subscribers
$GLOBALS['RAD_SUBSCRIBERS'][] = 'RAD\\YellowCube\\Service';
