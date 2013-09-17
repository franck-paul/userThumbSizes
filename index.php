<?php
# -- BEGIN LICENSE BLOCK ----------------------------------
#
# This file is part of Dotclear 2.
#
# Copyright (c) 2003-2008 Olivier Meunier and contributors
# Licensed under the GPL version 2.0 license.
# See LICENSE file or
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
#
# -- END LICENSE BLOCK ------------------------------------

$core->blog->settings->addNamespace('userthumbsizes');
$uts_active = (boolean) $core->blog->settings->userthumbsizes->active;
$uts_sizes = @unserialize($core->blog->settings->userthumbsizes->sizes);
if (!is_array($uts_sizes)) {
	$uts_sizes = array();
}

$excluded_codes = array('sq','t','s','m','o');

if (!empty($_POST))
{
	try
	{
		$uts_active = (boolean) $_POST['uts_active'];
		$uts_sizes = array();
		if (!empty($_POST['uts_codes'])) {
			for ($i = 0; $i < count($_POST['uts_codes']); $i++) {
				$code = $_POST['uts_codes'][$i];
				if (($code != '') && (!in_array($code,$excluded_codes))) {
					$size = isset($_POST['uts_sizes'][$i]) ? abs((integer) $_POST['uts_sizes'][$i]) : 0;
					$label = isset($_POST['uts_labels'][$i]) ? $_POST['uts_labels'][$i] : '';
					if (($size > 0) && ($label != '')) {
						$uts_sizes[$code] = array($size,$label);
					}
				}
			}
		}

		# Everything's fine, save options
		$core->blog->settings->addNamespace('userthumbsizes');
		$core->blog->settings->userthumbsizes->put('active',$uts_active);
		$core->blog->settings->userthumbsizes->put('sizes',(count($uts_sizes) ? serialize($uts_sizes) : ''));

		//$core->emptyTemplatesCache();
		$core->blog->triggerBlog();

		http::redirect($p_url.'&upd=1');
	}
	catch (Exception $e)
	{
		$core->error->add($e->getMessage());
	}
}

?>
<html>
<head>
	<title><?php echo __('User defined thumbnails'); ?></title>
</head>

<body>
<?php
echo dcPage::breadcrumb(
	array(
		html::escapeHTML($core->blog->name) => '',
		'<span class="page-title">'.__('User defined thumbnails').'</span>' => ''
	));

if (!empty($_GET['upd'])) {
	dcPage::success(__('Settings have been successfully updated.'));
}

echo
'<form action="'.$p_url.'" method="post">'.
'<p>'.form::checkbox('uts_active',1,$uts_active).' '.
'<label for="uts_active" class="classic">'.__('Activate user defined thumbnails for this blog').'</label></p>';

echo
'<table>'.'<caption class="as_h3">'.__('Thumbnails sizes').'</caption>'.
'<thead><tr>'.
	'<th scope="col">'.__('Code').'</th>'.
	'<th scope="col">'.__('Size in pixels').'</th>'.
	'<th scope="col">'.__('Label').'</th>'.
'</tr></thead>'.
'<tbody>';

foreach ($uts_sizes as $code => $size) {
	echo '<tr>'.
			'<td scope="row">'.form::field(array('uts_codes[]'),1,1,$code).'</td>'.
			'<td>'.form::field(array('uts_sizes[]'),3,3,$size[0]).'</td>'.
			'<td>'.form::field(array('uts_labels[]'),30,255,$size[1]).'</td>'.
		'</tr>';
}
// Empty row in order to add new thumbnail size
echo
'<tr>'.
	'<td scope="row">'.form::field(array('uts_codes[]'),1,1,'').'</td>'.
	'<td>'.form::field(array('uts_sizes[]'),3,3,'').'</td>'.
	'<td>'.form::field(array('uts_labels[]'),30,255,'').'</td>'.
'</tr>'.
'</tbody></table>';

echo
'<p class="form-note">'.__('Clear any field in row to delete this row').'</p>'.
'<p class="form-note">'.sprintf(__('Code must not be one of these: %s'),implode(', ',$excluded_codes)).'</p>'.

'<p>'.$core->formNonce().'<input type="submit" value="'.__('Save').'" /></p>'.
'</form>';

?>
</body>
</html>