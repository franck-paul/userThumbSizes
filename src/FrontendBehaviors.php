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

class FrontendBehaviors
{
    public static function publicPrepend(): string
    {
        if (dcCore::app()->media) { // @phpstan-ignore-line
            CoreBehaviors::coreMediaConstruct(dcCore::app()->media);    // @phpstan-ignore-line | waiting for 2.28+ compliance
        }

        return '';
    }
}
