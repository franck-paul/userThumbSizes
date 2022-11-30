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

// dead but useful code, in order to have translations
__('User defined thumbnails') . __('Add user defined thumbnails');

dcCore::app()->menu[dcAdmin::MENU_BLOG]->addItem(
    __('User defined thumbnails'),
    'plugin.php?p=userThumbSizes',
    urldecode(dcPage::getPF('userThumbSizes/icon.svg')),
    preg_match('/plugin.php\?p=userThumbSizes(&.*)?$/', $_SERVER['REQUEST_URI']),
    dcCore::app()->auth->check(dcCore::app()->auth->makePermissions([
        dcAuth::PERMISSION_CONTENT_ADMIN,
    ]), dcCore::app()->blog->id)
);
