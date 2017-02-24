<?php
/**
 * Contao extension for RAD Consulting GmbH
 *
 * @copyright  RAD Consulting GmbH 2016
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

// Palettes
$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] .= ';{rad_yellowcube_legend},rad_yellowcube_operatingmode,rad_yellowcube_depositorid,rad_yellowcube_depositorno,rad_yellowcube_supplierno,rad_yellowcube_partnerid,rad_yellowcube_plant;';

// Fields
$GLOBALS['TL_DCA']['tl_settings']['fields']['rad_yellowcube_depositorno'] = array(
    'eval' => array('maxlength' => 10),
    'label' => &$GLOBALS['TL_LANG']['tl_settings']['rad_yellowcube_depositorno'],
    'inputType' => 'text',
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['rad_yellowcube_operatingmode'] = array(
    'eval' => array('maxlength' => 1),
    'label' => &$GLOBALS['TL_LANG']['tl_settings']['rad_yellowcube_operatingmode'],
    'inputType' => 'select',
    'options' => array('T' => 'Test', 'P' => 'Production'),
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['rad_yellowcube_partnerid'] = array(
    'eval' => array('maxlength' => 10),
    'label' => &$GLOBALS['TL_LANG']['tl_settings']['rad_yellowcube_partnerid'],
    'inputType' => 'text',
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['rad_yellowcube_plant'] = array(
    'eval' => array('maxlength' => 4),
    'label' => &$GLOBALS['TL_LANG']['tl_settings']['rad_yellowcube_plant'],
    'inputType' => 'text',
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['rad_yellowcube_depositorid'] = array(
    'eval' => array('maxlength' => 10),
    'label' => &$GLOBALS['TL_LANG']['tl_settings']['rad_yellowcube_depositorid'],
    'inputType' => 'text',
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['rad_yellowcube_supplierno'] = array(
    'eval' => array('maxlength' => 10),
    'label' => &$GLOBALS['TL_LANG']['tl_settings']['rad_yellowcube_supplierno'],
    'inputType' => 'text',
);
