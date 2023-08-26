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
use Dotclear\Core\Process;
use Exception;

class Install extends Process
{
    public static function init(): bool
    {
        return self::status(My::checkContext(My::INSTALL));
    }

    public static function process(): bool
    {
        if (!self::status()) {
            return false;
        }

        try {
            $settings = My::settings();

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
        } catch (Exception $e) {
            dcCore::app()->error->add($e->getMessage());
        }

        return true;
    }
}
