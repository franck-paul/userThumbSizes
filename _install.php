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

if (!dcCore::app()->newVersion(basename(__DIR__), dcCore::app()->plugins->moduleInfo(basename(__DIR__), 'version'))) {
    return;
}

try {
    // Chech if settings exist, create them if not
    if (!dcCore::app()->blog->settings->userthumbsizes->getGlobal('active')) {
        dcCore::app()->blog->settings->userthumbsizes->put('active', false, 'boolean', 'Active', false, true);
    }
    if (!dcCore::app()->blog->settings->userthumbsizes->getGlobal('sizes')) {
        dcCore::app()->blog->settings->userthumbsizes->put('sizes', [], 'array', 'Sizes', false, true);
    }

    return true;
} catch (Exception $e) {
    dcCore::app()->error->add($e->getMessage());
}

return false;
