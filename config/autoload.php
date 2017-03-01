<?php
/**
 * Contao extension for RAD Consulting GmbH
 *
 * @copyright  RAD Consulting GmbH 2016
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

// Namespaces
\NamespaceClassLoader::add('RAD', 'system/modules/rad-yellowcube/library');

// Templates
\Contao\TemplateLoader::addFiles(array(
    'be_yellowcube_masterdata' => 'system/modules/rad-yellowcube/templates/backend',
));
