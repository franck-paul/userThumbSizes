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
declare(strict_types=1);

namespace Dotclear\Plugin\userThumbSizes;

use dcCore;
use dcNsProcess;
use Exception;

class Install extends dcNsProcess
{
    public static function init(): bool
    {
        static::$init = defined('DC_CONTEXT_ADMIN')
            && My::phpCompliant()
            && dcCore::app()->newVersion(My::id(), dcCore::app()->plugins->moduleInfo(My::id(), 'version'));

        return static::$init;
    }

    public static function process(): bool
    {
        if (!static::$init) {
            return false;
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

        return true;
    }
}
