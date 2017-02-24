<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

// Event
$GLOBALS['TL_MODELS'][\RAD\Event\Model\EventModel::getTable()] = 'RAD\\Event\\Model\\EventModel';
$GLOBALS['TL_CRON']['minutely'][] = array('RAD\\Event\\EventDispatcher', 'run');
$GLOBALS['RAD_LISTENERS'] = array();
$GLOBALS['RAD_SUBSCRIBERS'] = array();

$GLOBALS['BE_MOD']['system']['events'] = array(
    'tables' => array('tl_g4g_event', 'tl_g4g_log'),
    'icon' => 'system/themes/flexible/images/about.gif',
    'exec' => array('RAD\\Event\\Backend\\Command', 'executeEvent'),
);

// Fulfillment
\Isotope\Model\Product::registerModelType('fulfillment', 'RAD\\Fulfillment\\Model\\ProductModel');
$GLOBALS['TL_MODELS'][\RAD\Fulfillment\Model\FulfillmentModel::getTable()] = 'RAD\\Fulfillment\\Model\\FulfillmentModel';
$GLOBALS['TL_MODELS'][\RAD\Fulfillment\Model\SupplierOrderModel::getTable()] = 'RAD\\Fulfillment\\Model\\SupplierOrderModel';
$GLOBALS['TL_MODELS'][\RAD\Fulfillment\Model\Product\FulfillmentProduct::getTable()] = 'RAD\\Fulfillment\\Model\\Product\\FulfillmentProduct';
$GLOBALS['ISO_HOOKS']['postCheckout'][] = array('RAD\\Fulfillment\\Service', 'postCheckout');
$GLOBALS['RAD_SUBSCRIBERS'][] = 'RAD\\Fulfillment\\Service';
$GLOBALS['BE_MOD']['isotope']['iso_products']['tables'][] = 'tl_g4g_log';

$GLOBALS['BE_MOD']['isotope']['fulfillments'] = array(
    'tables' => array('tl_g4g_fulfillment', 'tl_g4g_log'),
    'icon' => 'system/themes/flexible/images/about.gif',
);

$GLOBALS['BE_MOD']['isotope']['supplierorders'] = array(
    'tables' => array('tl_g4g_supplier_order', 'tl_g4g_log'),
    'icon' => 'system/themes/flexible/images/about.gif',
);

// Logging
$GLOBALS['TL_MODELS'][\RAD\Logging\Model\LogModel::getTable()] = 'RAD\\Logging\\Model\\LogModel';

// Yellowcube
\Isotope\Model\Product::registerModelType('yellowcube', 'RAD\\YellowCube\\Model\\ProductModel');
$GLOBALS['TL_MODELS'][\RAD\YellowCube\Model\Product\YellowCubeProduct::getTable()] = 'RAD\\YellowCube\\Model\\Product\\YellowCubeProduct';
$GLOBALS['RAD_SUBSCRIBERS'][] = 'RAD\\YellowCube\\Service';
