<?php
# -- BEGIN LICENSE BLOCK ----------------------------------
# This file is part of userThumbSizes, a plugin for Dotclear 2.
#
# Copyright (c) Franck Paul and contributors
# carnet.franck.paul@gmail.com
#
# Icon from Faenza set by tiheum (http://tiheum.deviantart.com/art/Faenza-Icons-173323228)
#
# Licensed under the GPL version 2.0 license.
# A copy of this license is available in LICENSE file or at
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
# -- END LICENSE BLOCK ------------------------------------

if (!defined('DC_RC_PATH')) {return;}

$core->addBehavior('publicPrepend', array('behaviorPublicUserThumbSizes', 'publicPrepend'));

class behaviorPublicUserThumbSizes
{
    public static function publicPrepend($core)
    {
        if ($core->media) {
            behaviorUserThumbSizes::coreMediaConstruct($core->media);
        }
    }
}
