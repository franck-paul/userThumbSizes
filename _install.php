<?php
/**
 * @brief userThumbSizes, a plugin for Dotclear 2
 *
 * @package Dotclear
 * @subpackage Plugins
 *
 * @author Franck Paul
 *
 * @copyright Franck Paul carnet.franck.paul@gmail.com
 * @copyright GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
if (!defined('DC_CONTEXT_ADMIN')) {
    return;
}

$new_version = dcCore::app()->plugins->moduleInfo('userThumbSizes', 'version');
$old_version = dcCore::app()->getVersion('userThumbSizes');

if (version_compare($old_version, $new_version, '>=')) {
    return;
}

try {
    if (version_compare($old_version, '0.4') < 0) {
        // Convert oldschool settings
        dcUpgrade::settings2array('userthumbsizes', 'sizes');
    }

    // Create namespace if necessary
    dcCore::app()->blog->settings->addNamespace('userthumbsizes');

    // Chech if settings exist, create them if not
    if (!dcCore::app()->blog->settings->userthumbsizes->getGlobal('active')) {
        dcCore::app()->blog->settings->userthumbsizes->put('active', false, 'boolean', 'Active', false, true);
    }
    if (!dcCore::app()->blog->settings->userthumbsizes->getGlobal('sizes')) {
        dcCore::app()->blog->settings->userthumbsizes->put('sizes', [], 'array', 'Sizes', false, true);
    }

    dcCore::app()->setVersion('userThumbSizes', $new_version);

    return true;
} catch (Exception $e) {
    dcCore::app()->error->add($e->getMessage());
}

return false;
