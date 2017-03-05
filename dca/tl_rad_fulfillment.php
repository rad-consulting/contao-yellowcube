<?php
/**
 * Contao extension for RAD Consulting GmbH
 *
 * @copyright  RAD Consulting GmbH 2016
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

// Config
$GLOBALS['TL_DCA']['tl_rad_fulfillment']['config']['onsubmit_callback'][] = array('RAD\\YellowCube\\Backend\\Panel', 'onSubmit');

// Palettes
$GLOBALS['TL_DCA']['tl_rad_fulfillment']['palettes']['yellowcube'] = '{type_legend},id,pid,type,status;{fulfillment_legend},reference,delivery,tracking';
