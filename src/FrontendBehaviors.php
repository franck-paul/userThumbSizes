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

use Dotclear\App;

class FrontendBehaviors
{
    public static function publicPrepend(): string
    {
        CoreBehaviors::coreMediaConstruct(App::media());    // @phpstan-ignore-line - should be Media (as this class cope with thumb_sizes)

        return '';
    }
}
