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

if (!defined('DC_CONTEXT_ADMIN')) {return;}

$core->blog->settings->addNamespace('userthumbsizes');
$uts_active = (boolean) $core->blog->settings->userthumbsizes->active;
$uts_sizes  = $core->blog->settings->userthumbsizes->sizes;
if (!is_array($uts_sizes)) {
	$uts_sizes = [];
}

$excluded_codes = ['sq', 't', 's', 'm', 'o'];
$modes_combo    = ['ratio' => '', 'crop' => 'crop'];

if (!empty($_POST)) {
	try
	{
		$uts_active = (boolean) $_POST['uts_active'];
        $uts_sizes  = [];
		if (!empty($_POST['uts_codes'])) {
			for ($i = 0; $i < count($_POST['uts_codes']); $i++) {
				$code = $_POST['uts_codes'][$i];
                if (($code != '') && (!in_array($code, $excluded_codes))) {
                    $size  = isset($_POST['uts_sizes'][$i]) ? abs((integer) $_POST['uts_sizes'][$i]) : 0;
					$label = isset($_POST['uts_labels'][$i]) ? $_POST['uts_labels'][$i] : '';
                    $mode  = isset($_POST['uts_modes'][$i]) ? $_POST['uts_modes'][$i] : 'ratio';
					if (($size > 0) && ($label != '')) {
                        $uts_sizes[$code] = [$size, $label, $mode];
					}
				}
			}
		}

		# Everything's fine, save options
		$core->blog->settings->addNamespace('userthumbsizes');
        $core->blog->settings->userthumbsizes->put('active', $uts_active);
        $core->blog->settings->userthumbsizes->put('sizes', $uts_sizes, 'array');

		//$core->emptyTemplatesCache();
		$core->blog->triggerBlog();

		dcPage::addSuccessNotice(__('Settings have been successfully updated.'));
		http::redirect($p_url);
    } catch (Exception $e) {
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
	[
		html::escapeHTML($core->blog->name) => '',
        __('User defined thumbnails')       => ''
	]);
echo dcPage::notices();

echo
'<form action="' . $p_url . '" method="post">' .
'<p>' . form::checkbox('uts_active', 1, $uts_active) . ' ' .
'<label for="uts_active" class="classic">' . __('Activate user defined thumbnails for this blog') . '</label></p>';

echo
'<table>' . '<caption class="as_h3">' . __('Thumbnails sizes') . '</caption>' .
'<thead><tr>' .
'<th scope="col">' . __('Code') . '</th>' .
'<th scope="col">' . __('Size in pixels') . '</th>' .
'<th scope="col">' . __('Mode') . '</th>' .
'<th scope="col">' . __('Label') . '</th>' .
    '</tr></thead>' .
'<tbody>';

foreach ($uts_sizes as $code => $size) {
	if (is_array($size)) {
        echo '<tr>' .
        '<td scope="row">' . form::field(['uts_codes[]'], 1, 1, $code) . '</td>' .
        '<td>' . form::number(['uts_sizes[]'], [
            'min'     => 0,
            'max'     => 9999,
            'default' => $size[0]
        ]) . '</td>' .
        '<td>' . form::combo(['uts_modes[]'], $modes_combo, isset($size[2]) ? $size[2] : '') . '</td>' .
        '<td>' . form::field(['uts_labels[]'], 30, 255, $size[1]) . '</td>' .
			'</tr>';
	}
}
// Empty row in order to add new thumbnail size
echo
'<tr>' .
'<td scope="row">' . form::field(['uts_codes[]'], 1, 1, '') . '</td>' .
'<td>' . form::number(['uts_sizes[]'], [
    'min' => 0,
    'max' => 9999
]) . '</td>' .
'<td>' . form::combo(['uts_modes[]'], $modes_combo) . '</td>' .
'<td>' . form::field(['uts_labels[]'], 30, 255, '') . '</td>' .
    '</tr>' .
'</tbody></table>';

echo
'<p class="form-note">' . __('Clear any field in row to delete this row') . '</p>' .
'<p class="form-note">' . sprintf(__('Code must not be one of these: %s'), implode(', ', $excluded_codes)) . '</p>' .
'<p class="form-note">' . __('Mode: <strong>ratio</strong> will preserve aspect, <strong>crop</strong> will produce square') . '</p>' .

'<p>' . $core->formNonce() . '<input type="submit" value="' . __('Save') . '" /></p>' .
'</form>';

?>
</body>
</html>
