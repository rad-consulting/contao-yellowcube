<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 */

// Config
$GLOBALS['TL_DCA']['tl_rad_fulfillment']['config']['onsubmit_callback'][] = array('RAD\\YellowCube\\Backend\\Panel', 'onSubmit');

// Palettes
$GLOBALS['TL_DCA']['tl_rad_fulfillment']['palettes']['yellowcube'] = '{type_legend},id,pid,type,status;{fulfillment_legend},reference,delivery,tracking;{position_legend},positions';
