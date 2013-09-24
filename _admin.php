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

if (!defined('DC_CONTEXT_ADMIN')) { return; }

// dead but useful code, in order to have translations
__('User defined thumbnails').__('Add user defined thumbnails');

$_menu['Blog']->addItem(__('User defined thumbnails'),'plugin.php?p=userThumbSizes','index.php?pf=userThumbSizes/icon.png',
		preg_match('/plugin.php\?p=userThumbSizes(&.*)?$/',$_SERVER['REQUEST_URI']),
		$core->auth->check('contentadmin',$core->blog->id));
?>