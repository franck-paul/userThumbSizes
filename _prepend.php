<?php
# -- BEGIN LICENSE BLOCK ---------------------------------------
#
# This file is part of Dotclear 2.
#
# Copyright (c) 2003-2011 Franck Paul
# Licensed under the GPL version 2.0 license.
# See LICENSE file or
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
#
# -- END LICENSE BLOCK -----------------------------------------
if (!defined('DC_RC_PATH')) { return; }


$GLOBALS['core']->addBehavior('coreMediaConstruct',array('behaviorUserThumbSizes','coreMediaConstruct'));

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
				// Keeping in mind that DC build thumbnails from his first larger (or from original for the largest)
				$sizes = array();
				foreach ($media->thumb_sizes as $code => $size) {
					$sizes[$code] = $size[0];
				}
				array_multisort($sizes,SORT_DESC,$media->thumb_sizes);
			}
		}
	}
}
?>