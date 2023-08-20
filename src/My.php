<?php
/**
 * @brief userThumbSizes, a plugin for Dotclear 2
 *
 * @package Dotclear
 * @subpackage Plugins
 *
 * @author Franck Paul and contributors
 *
 * @copyright Franck Paul carnet.franck.paul@gmail.com
 * @copyright GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
declare(strict_types=1);

namespace Dotclear\Plugin\userThumbSizes;

use dcCore;
use Dotclear\Module\MyPlugin;

/**
 * Plugin definitions
 */
class My extends MyPlugin
{
    /**
     * Check permission depending on given context
     *
     * @param      int   $context  The context
     *
     * @return     bool  true if allowed, else false, null if default
     */
    public static function checkCustomContext(int $context): ?bool
    {
        return match ($context) {
            self::BACKEND => defined('DC_CONTEXT_ADMIN')
                    // Check specific permission
                    && dcCore::app()->blog && dcCore::app()->auth->check(dcCore::app()->auth->makePermissions([
                        dcCore::app()->auth::PERMISSION_USAGE,
                        dcCore::app()->auth::PERMISSION_CONTENT_ADMIN,
                        dcCore::app()->auth::PERMISSION_MEDIA_ADMIN,
                    ]), dcCore::app()->blog->id),

            self::CONFIG => defined('DC_CONTEXT_ADMIN')
                    // Check specific permission
                    && dcCore::app()->blog && dcCore::app()->auth->check(dcCore::app()->auth->makePermissions([
                        dcCore::app()->auth::PERMISSION_ADMIN,  // Admin+
                    ]), dcCore::app()->blog->id),

            self::MANAGE,
            self::MENU => defined('DC_CONTEXT_ADMIN')
                    // Check specific permission
                    && dcCore::app()->blog && dcCore::app()->auth->check(dcCore::app()->auth->makePermissions([
                        dcCore::app()->auth::PERMISSION_MEDIA_ADMIN,
                        dcCore::app()->auth::PERMISSION_ADMIN,  // Admin+
                    ]), dcCore::app()->blog->id),

            default => null
        };
    }

    /**
     * Return URL regexp scheme cope by the plugin
     *
     * @return     string
     */
    public static function urlScheme(): string
    {
        return '/' . preg_quote(dcCore::app()->admin->url->get('admin.plugin.' . self::id())) . '(&.*)?$/';
    }
}
