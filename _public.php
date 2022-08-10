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
if (!defined('DC_RC_PATH')) {
    return;
}

dcCore::app()->addBehavior('publicPrepend', ['behaviorPublicUserThumbSizes', 'publicPrepend']);

class behaviorPublicUserThumbSizes
{
    public static function publicPrepend($core = null)
    {
        if (dcCore::app()->media) {
            behaviorUserThumbSizes::coreMediaConstruct(dcCore::app()->media);
        }
    }
}
