<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

// Models
\Isotope\Model\Product::registerModelType('fulfillment', 'Fulfillment\\Model\\ProductModel');
\Isotope\Model\Product::registerModelType('yellowcube', 'YellowCube\\Model\\ProductModel');
$GLOBALS['TL_MODELS'][\Event\Model\EventModel::getTable()] = 'Event\\Model\\EventModel';
$GLOBALS['TL_MODELS'][\Logging\Model\LogModel::getTable()] = 'Logging\\Model\\LogModel';
$GLOBALS['TL_MODELS'][\Fulfillment\Model\Product\FulfillmentProduct::getTable()] = 'Fulfillment\\Model\\Product\\FulfillmentProduct';
$GLOBALS['TL_MODELS'][\YellowCube\Model\Product\YellowCubeProduct::getTable()] = 'YellowCube\\Model\\Product\\YellowCubeProduct';

// Hooks
$GLOBALS['ISO_HOOKS']['postCheckout'][] = array('Fulfillment\\Service', 'postCheckout');

// Crons
$GLOBALS['TL_CRON']['minutely'][] = array('Event\\EventDispatcher', 'run');
