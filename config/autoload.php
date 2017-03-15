<?php
/**
 * @copyright  RAD Consulting GmbH 2017
 * @author     Chris Raidler <c.raidler@rad-consulting.ch>
 * @author     Olivier Dahinden <o.dahinden@rad-consulting.ch>
 */

// Namespaces
\NamespaceClassLoader::add('RAD', 'system/modules/rad-yellowcube/library');

// Templates
\Contao\TemplateLoader::addFiles(array(
    'be_yellowcube_masterdata' => 'system/modules/rad-yellowcube/templates/backend',
));
