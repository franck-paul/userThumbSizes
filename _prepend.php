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

if (!defined('DC_RC_PATH')) { return; }

$core->addBehavior('coreMediaConstruct',array('behaviorUserThumbSizes','coreMediaConstruct'));

class behaviorUserThumbSizes
{
	public static function coreMediaConstruct($media)
	{
		global $core;

		$touch = false;
		$core->blog->settings->addNamespace('userthumbsizes');
		if ($core->blog->settings->userthumbsizes->active) {
			if ($core->blog->settings->userthumbsizes->sizes != '') {
				// userThumbSizes active and some sizes to defined
				$sizes = @unserialize($core->blog->settings->userthumbsizes->sizes);
				if (is_array($sizes)) {
					foreach ($sizes as $code => $size) {
						if (!array_key_exists($code,$media->thumb_sizes)) {
							// [0] = largest size in pixels
							// [1] = label
							$media->thumb_sizes[$code] = array($size[0],'ratio',__($size[1]));
							$touch = true;
						}
					}
				}
			}
			if ($touch) {
				// Sort thumb_sizes DESC on largest sizes
				$sizes = array();
				foreach ($media->thumb_sizes as $code => $size) {
					$sizes[$code] = $size[0];
				}
				array_multisort($sizes,SORT_DESC,$media->thumb_sizes);
			}
		}
	}
}
