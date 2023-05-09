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
        static::$init = My::checkContext(My::INSTALL);

        return static::$init;
    }

    public static function process(): bool
    {
        if (!static::$init) {
            return false;
        }

        try {
            $settings = dcCore::app()->blog->settings->get(My::id());

            // Update from older versions
            $old_version = dcCore::app()->getVersion(My::id());
            if (version_compare((string) $old_version, '2.2', '<')) {
                // Rename settings namespace
                if (dcCore::app()->blog->settings->exists('userthumbsizes')) {
                    dcCore::app()->blog->settings->delNamespace(My::id());
                    dcCore::app()->blog->settings->renNamespace('userthumbsizes', My::id());
                }
            }

            // Chech if settings exist, create them if not
            if (!$settings->getGlobal('active')) {
                $settings->put('active', false, 'boolean', 'Active', false, true);
            }
            if (!$settings->getGlobal('sizes')) {
                $settings->put('sizes', [], 'array', 'Sizes', false, true);
            }

            return true;
        } catch (Exception $e) {
            dcCore::app()->error->add($e->getMessage());
        }

        return true;
    }
}
