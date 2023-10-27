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
use Dotclear\Core\Process;

class Prepend extends Process
{
    public static function init(): bool
    {
        return self::status(My::checkContext(My::PREPEND));
    }

    public static function process(): bool
    {
        if (!self::status()) {
            return false;
        }

        self::setUserThumbSizes();

        return true;
    }

    private static function setUserThumbSizes(): void
    {
        if (App::blog()->isDefined()) {
            $touch    = false;
            $settings = My::settings();
            if ($settings->active && is_array($settings->sizes)) {
                // userThumbSizes active and some sizes to defined
                $thumb_sizes = App::media()->getThumbSizes();
                $sizes       = $settings->sizes;
                foreach ($sizes as $code => $size) {
                    if (!array_key_exists($code, $thumb_sizes)) {
                        // $size:
                        // [0] = largest size in pixels
                        // [1] = label
                        // [2] = mode
                        $mode = isset($size[2]) && $size[2] != '' ? $size[2] : 'ratio';
                        // $thumb_sizes[$code]:
                        // [0] = largest size in pixels
                        // [1] = mode
                        // [2] = translated label
                        // [3] = label
                        $thumb_sizes[$code] = [$size[0], $mode, __($size[1]), $size[1]];
                        $touch              = true;
                    }
                }

                if ($touch) {
                    // Sort thumb_sizes DESC on largest sizes
                    $sizes = [];
                    foreach ($thumb_sizes as $code => $size) {
                        $sizes[$code] = $size[0];
                    }

                    array_multisort($sizes, SORT_DESC, $thumb_sizes);

                    App::media()->setThumbSizes($thumb_sizes);
                }
            }
        }
    }
}
