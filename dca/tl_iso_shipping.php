<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

// Palettes
$GLOBALS['TL_DCA']['tl_iso_shipping']['palettes']['yellowcube'] = str_replace('{expert_legend:hide}', '{yellowcube_legend:hide},rad_yellowcube_basicservice;{expert_legend:hide}', $GLOBALS['TL_DCA']['tl_iso_shipping']['palettes']['flat']);

// Fields
$GLOBALS['TL_DCA']['tl_iso_shipping']['fields']['rad_yellowcube_basicservice'] = array(
    'sql' => "varchar(24) NOT NULL default 'ECO'",
    'eval' => array(),
    'default' => 'ECO',
    'options' => array('ECO', 'PRI', 'APOST', 'APOSTPLUS', 'BPOST', 'RETURN', 'URGENT'),
    'reference' => &$GLOBALS['TL_LANG']['tl_iso_shipping']['rad_yellowcube_basicservice.reference'],
    'inputType' => 'select',
);
