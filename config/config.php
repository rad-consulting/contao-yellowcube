<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

// Models
\Isotope\Model\Product::registerModelType('yellowcube', 'YellowCube\\Model\\ProductModel');
$GLOBALS['TL_MODELS'][\YellowCube\Model\EventModel::getTable()] = 'YellowCube\\Model\\EventModel';
$GLOBALS['TL_MODELS'][\YellowCube\Model\LogModel::getTable()] = 'YellowCube\\Model\\LogModel';
$GLOBALS['TL_MODELS'][\YellowCube\Model\Product\YellowCubeProduct::getTable()] = 'YellowCube\\Model\\Product\\YellowCubeProduct';
