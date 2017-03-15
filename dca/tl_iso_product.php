<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 */

// Config
$GLOBALS['TL_DCA']['tl_iso_product']['config']['onsubmit_callback'][] = array('RAD\\YellowCube\\Backend\\Panel', 'onSubmit');
