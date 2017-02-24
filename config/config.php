<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

// Models
\Isotope\Model\Product::registerModelType('fulfillment', 'RAD\Fulfillment\\Model\\ProductModel');
\Isotope\Model\Product::registerModelType('yellowcube', 'RAD\YellowCube\\Model\\ProductModel');
$GLOBALS['TL_MODELS'][\RAD\Event\Model\EventModel::getTable()] = 'RAD\Event\\Model\\EventModel';
$GLOBALS['TL_MODELS'][\RAD\Logging\Model\LogModel::getTable()] = 'RAD\Logging\\Model\\LogModel';
$GLOBALS['TL_MODELS'][\RAD\Fulfillment\Model\FulfillmentModel::getTable()] = 'RAD\Fulfillment\\Model\\FulfillmentModel';
$GLOBALS['TL_MODELS'][\RAD\Fulfillment\Model\SupplierOrderModel::getTable()] = 'RAD\Fulfillment\\Model\\SupplierOrderModel';
$GLOBALS['TL_MODELS'][\RAD\Fulfillment\Model\Product\FulfillmentProduct::getTable()] = 'RAD\Fulfillment\\Model\\Product\\FulfillmentProduct';
$GLOBALS['TL_MODELS'][\RAD\YellowCube\Model\Product\YellowCubeProduct::getTable()] = 'RAD\YellowCube\\Model\\Product\\YellowCubeProduct';

// Hooks
$GLOBALS['ISO_HOOKS']['postCheckout'][] = array('RAD\\Fulfillment\\Service', 'postCheckout');

// Crons
$GLOBALS['TL_CRON']['minutely'][] = array('RAD\\Event\\EventDispatcher', 'run');
