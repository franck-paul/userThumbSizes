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

if (!defined('DC_CONTEXT_ADMIN')){return;}

$new_version = $core->plugins->moduleInfo('userThumbSizes','version');
$old_version = $core->getVersion('userThumbSizes');

if (version_compare($old_version,$new_version,'>=')) return;

try
{
	$core->blog->settings->addNamespace('userthumbsizes');

	// Default state is inactive
	$core->blog->settings->userthumbsizes->put('active',false,'boolean','Active',false,true);
	$core->blog->settings->userthumbsizes->put('sizes','','string','Sizes',false,true);

	$core->setVersion('userThumbSizes',$new_version);

	return true;
}
catch (Exception $e)
{
	$core->error->add($e->getMessage());
}
return false;

?>