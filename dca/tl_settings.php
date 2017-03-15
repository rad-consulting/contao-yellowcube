<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 */

// Palettes
$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] .= ';{rad_yellowcube_legend},rad_yellowcube_depositorno,rad_yellowcube_supplierno,rad_yellowcube_partnerid,rad_yellowcube_plantid,rad_yellowcube_sender,rad_yellowcube_operatingmode,rad_yellowcube_active,rad_yellowcube_eanalways,rad_yellowcube_termofdelivery;';

// Fields
$GLOBALS['TL_DCA']['tl_settings']['fields']['rad_yellowcube_eanalways'] = array(
    'eval' => array('tl_class' => 'w50'),
    'label' => &$GLOBALS['TL_LANG']['tl_settings']['rad_yellowcube_eanalways'],
    'inputType' => 'checkbox',
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['rad_yellowcube_active'] = array(
    'eval' => array('tl_class' => 'w50'),
    'label' => &$GLOBALS['TL_LANG']['tl_settings']['rad_yellowcube_active'],
    'inputType' => 'checkbox',
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['rad_yellowcube_depositorno'] = array(
    'eval' => array('maxlength' => 10, 'tl_class' => 'w50'),
    'label' => &$GLOBALS['TL_LANG']['tl_settings']['rad_yellowcube_depositorno'],
    'inputType' => 'text',
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['rad_yellowcube_operatingmode'] = array(
    'eval' => array('maxlength' => 1, 'tl_class' => 'w50'),
    'label' => &$GLOBALS['TL_LANG']['tl_settings']['rad_yellowcube_operatingmode'],
    'inputType' => 'select',
    'options' => array('T' => 'Test', 'P' => 'Production'),
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['rad_yellowcube_partnerid'] = array(
    'eval' => array('maxlength' => 10, 'tl_class' => 'w50'),
    'label' => &$GLOBALS['TL_LANG']['tl_settings']['rad_yellowcube_partnerid'],
    'inputType' => 'text',
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['rad_yellowcube_plantid'] = array(
    'eval' => array('maxlength' => 4, 'tl_class' => 'w50'),
    'label' => &$GLOBALS['TL_LANG']['tl_settings']['rad_yellowcube_plantid'],
    'inputType' => 'text',
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['rad_yellowcube_sender'] = array(
    'eval' => array('maxlength' => 10, 'tl_class' => 'w50'),
    'label' => &$GLOBALS['TL_LANG']['tl_settings']['rad_yellowcube_sender'],
    'inputType' => 'text',
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['rad_yellowcube_supplierno'] = array(
    'eval' => array('maxlength' => 10, 'tl_class' => 'w50'),
    'label' => &$GLOBALS['TL_LANG']['tl_settings']['rad_yellowcube_supplierno'],
    'inputType' => 'text',
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['rad_yellowcube_termofdelivery'] = $GLOBALS['TL_DCA']['tl_settings']['fields']['rad_fulfillment_termofdelivery'];
$GLOBALS['TL_DCA']['tl_settings']['fields']['rad_yellowcube_termofdelivery']['label'] = &$GLOBALS['TL_LANG']['tl_settings']['rad_yellowcube_termofdelivery'];
