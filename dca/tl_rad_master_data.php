<?php
/**
 * Contao extension for RAD Consulting GmbH
 *
 * @copyright  RAD Consulting GmbH 2016
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

// Yellowcube
$GLOBALS['TL_DCA']['tl_rad_master_data']['config']['onsubmit_callback'][] = array('RAD\\YellowCube\\Backend\\Panel', 'onSubmit');
