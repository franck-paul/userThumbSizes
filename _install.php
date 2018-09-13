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

if (!defined('DC_CONTEXT_ADMIN')) {return;}

$new_version = $core->plugins->moduleInfo('userThumbSizes', 'version');
$old_version = $core->getVersion('userThumbSizes');

if (version_compare($old_version, $new_version, '>=')) {
    return;
}

try
{
    if (version_compare($old_version, '0.4') < 0) {
        // Convert oldschool settings
        dcUpgrade::settings2array('userthumbsizes', 'sizes');
    }

    // Create namespace if necessary
    $core->blog->settings->addNamespace('userthumbsizes');

    // Chech if settings exist, create them if not
    if (!$core->blog->settings->userthumbsizes->getGlobal('active')) {
        $core->blog->settings->userthumbsizes->put('active', false, 'boolean', 'Active', false, true);
    }
    if (!$core->blog->settings->userthumbsizes->getGlobal('sizes')) {
        $core->blog->settings->userthumbsizes->put('sizes', [], 'array', 'Sizes', false, true);
    }

    $core->setVersion('userThumbSizes', $new_version);

    return true;
} catch (Exception $e) {
    $core->error->add($e->getMessage());
}
return false;
