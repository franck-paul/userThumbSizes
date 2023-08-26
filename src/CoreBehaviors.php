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

class CoreBehaviors
{
    public static function coreMediaConstruct($media)
    {
        if (dcCore::app()->blog !== null) {
            $touch    = false;
            $settings = My::settings();
            if ($settings->active) {
                if (is_array($settings->sizes)) {
                    // userThumbSizes active and some sizes to defined
                    $sizes = $settings->sizes;
                    foreach ($sizes as $code => $size) {
                        if (!array_key_exists($code, $media->thumb_sizes)) {
                            // [0] = largest size in pixels
                            // [1] = label
                            // [2] = mode
                            $mode                      = isset($size[2]) && $size[2] != '' ? $size[2] : 'ratio';
                            $media->thumb_sizes[$code] = [$size[0], $mode, __($size[1])];
                            $touch                     = true;
                        }
                    }
                }
                if ($touch) {
                    // Sort thumb_sizes DESC on largest sizes
                    $sizes = [];
                    foreach ($media->thumb_sizes as $code => $size) {
                        $sizes[$code] = $size[0];
                    }
                    array_multisort($sizes, SORT_DESC, $media->thumb_sizes);
                }
            }
        }
    }
}
