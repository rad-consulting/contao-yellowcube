<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 */

// Yellowcube
$GLOBALS['TL_DCA']['tl_rad_supplier_order']['config']['onsubmit_callback'][] = array('RAD\\YellowCube\\Backend\\Panel', 'onSubmit');
