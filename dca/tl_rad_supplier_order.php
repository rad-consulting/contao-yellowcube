<?php
/**
 * Contao extension for RAD Consulting GmbH
 *
 * @copyright  RAD Consulting GmbH 2016
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

// Config
$GLOBALS['TL_DCA']['tl_rad_supplier_order']['config'] = array(
    'dataContainer' => 'Table',
    'sql' => array(
        'keys' => array(
            'id' => 'primary',
        ),
    ),
    'onsubmit_callback' => array(
        array('RAD\\Fulfillment\\Backend\\Callback', 'submitSupplierOrder'),
    ),
);

// List
$GLOBALS['TL_DCA']['tl_rad_supplier_order']['list'] = array(
    'sorting' => array(
        'mode' => 2,
        'fields' => array('id DESC'),
        'panelLayout' => 'filter;sort,search,limit',
        'flag' => 12,
    ),
    'label' => array(
        'fields' => array('id', 'tstamp', 'name'),
        'showColumns' => true,
        'label_callback' => array('G4G\\Fulfillment\\Backend\\Listing', 'listSupplierOrder'),
    ),
    'global_operations' => array(
        'back' => array(
            'label' => &$GLOBALS['TL_LANG']['MSC']['backBT'],
            'href' => 'mod=&table=',
            'class' => 'header_back',
            'attributes' => 'onclick="Backend.getScrollOffset();"',
        ),
    ),
    'operations' => array(
        'log' => array(
            'label' => &$GLOBALS['TL_LANG']['tl_g4g_event']['log'],
            'href' => 'table=tl_g4g_log',
            'icon' => 'news.gif',
            'button_callback' => array('RAD\\Logging\\Backend\\Button', 'forLog'),
        ),
        'edit' => array(
            'label' => &$GLOBALS['TL_LANG']['tl_rad_supplier_order']['edit'],
            'href' => 'act=edit',
            'icon' => 'edit.gif',
        ),
        'delete' => array(
            'label' => &$GLOBALS['TL_LANG']['tl_rad_supplier_order']['delete'],
            'href' => 'act=delete',
            'icon' => 'delete.gif',
            'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
        ),
        'show' => array(
            'label' => &$GLOBALS['TL_LANG']['tl_rad_supplier_order']['show'],
            'href' => 'act=show',
            'icon' => 'show.gif',
        ),
    ),
);

// Palettes
$GLOBALS['TL_DCA']['tl_rad_supplier_order']['palettes'] = array(
    'default' => '{order_legend},name,producttype;{position_legend},positions;{export_legend},export;',
);

// Fields
$GLOBALS['TL_DCA']['tl_rad_supplier_order']['fields'] = array(
    'id' => array(
        'sql' => "int(10) unsigned NOT NULL auto_increment",
        'label' => &$GLOBALS['TL_LANG']['tl_rad_supplier_order']['id'],
    ),
    'tstamp' => array(
        'sql' => "int(10) unsigned NOT NULL default '0'",
        'label' => &$GLOBALS['TL_LANG']['tl_rad_supplier_order']['tstamp'],
    ),
    'name' => array(
        'sql' => "varchar(255) NOT NULL default ''",
        'eval' => array('tl_class' => 'w50'),
        'label' => &$GLOBALS['TL_LANG']['tl_rad_supplier_order']['name'],
        'inputType' => 'text',
    ),
    'producttype' => array(
        'sql' => "varchar(64) NOT NULL default ''",
        'eval' => array('tl_class' => 'w50', 'chosen' => true, 'submitOnChange' => true),
        'label' => &$GLOBALS['TL_LANG']['tl_rad_supplier_order']['producttype'],
        'inputType' => 'select',
        'options' => array(
            'yellowcube' => 'YellowCube',
        ),
    ),
    'positions' => array(
        'sql' => "blob NULL",
        'eval' => array(
            'tl_class' => 'clr',
            'doNotSaveEmpty' => true,
            'disableSorting' => true,
            'columnFields' => array(
                'product' => array(
                    'eval' => array('mandatory' => true, 'chosen' => true, 'style' => 'width:350px'),
                    'label' => &$GLOBALS['TL_LANG']['tl_rad_supplier_order']['product'],
                    'inputType' => 'select',
                    'options_callback' => array('RAD\\Fulfillment\\Backend\\Field', 'forProductSupplierOrder'),
                ),
                'quantity' => array(
                    'eval' => array('mandatory' => true, 'rgxp' => 'digit', 'style' => 'width:50px'),
                    'label' => &$GLOBALS['TL_LANG']['tl_rad_supplier_order']['quantity'],
                    'inputType' => 'text',
                ),
            ),
        ),
        'label' => &$GLOBALS['TL_LANG']['tl_rad_supplier_order']['positions'],
        'inputType' => 'multiColumnWizard',
    ),
    'export' => array(
        'sql' => "char(1) NOT NULL default '0'",
        'eval' => array(),
        'label' => &$GLOBALS['TL_LANG']['tl_rad_supplier_order']['export'],
        'inputType' => 'checkbox',
    ),
);
