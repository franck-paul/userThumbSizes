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

use Dotclear\App;
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
            self::BACKEND => !App::task()->checkContext('FRONTEND')
                    // Check specific permission
                    && App::blog()->isDefined() && App::auth()->check(App::auth()->makePermissions([
                        App::auth()::PERMISSION_USAGE,
                        App::auth()::PERMISSION_CONTENT_ADMIN,
                        App::auth()::PERMISSION_MEDIA_ADMIN,
                    ]), App::blog()->id()),

            self::CONFIG => !App::task()->checkContext('FRONTEND')
                    // Check specific permission
                    && App::blog()->isDefined() && App::auth()->check(App::auth()->makePermissions([
                        App::auth()::PERMISSION_ADMIN,  // Admin+
                    ]), App::blog()->id()),

            self::MANAGE,
            self::MENU => !App::task()->checkContext('FRONTEND')
                    // Check specific permission
                    && App::blog()->isDefined() && App::auth()->check(App::auth()->makePermissions([
                        App::auth()::PERMISSION_MEDIA_ADMIN,
                        App::auth()::PERMISSION_ADMIN,  // Admin+
                    ]), App::blog()->id()),

            default => null
        };
    }
}
