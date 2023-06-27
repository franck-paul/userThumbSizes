<?php
/**
 * @brief userThumbSizes, a plugin for Dotclear 2
 *
 * @package Dotclear
 * @subpackage Plugins
 *
 * @author Franck Paul and contributors
 *
 * @copyright Franck Paul carnet.franck.paul@gmail.com
 * @copyright GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
declare(strict_types=1);

namespace Dotclear\Plugin\userThumbSizes;

use dcCore;
use dcNsProcess;
use dcPage;
use Dotclear\Helper\Html\Form\Checkbox;
use Dotclear\Helper\Html\Form\Form;
use Dotclear\Helper\Html\Form\Input;
use Dotclear\Helper\Html\Form\Label;
use Dotclear\Helper\Html\Form\Number;
use Dotclear\Helper\Html\Form\Para;
use Dotclear\Helper\Html\Form\Select;
use Dotclear\Helper\Html\Form\Submit;
use Dotclear\Helper\Html\Form\Text;
use Dotclear\Helper\Html\Html;
use Exception;

class Manage extends dcNsProcess
{
    protected static $init = false; /** @deprecated since 2.27 */
    // Dotclear reserved thumbnails codes
    // sq = square
    // t = thumbnail
    // s = small
    // m = medium
    // o = original
    protected static $excluded_codes = ['sq', 't', 's', 'm', 'o'];

    /**
     * Initializes the page.
     */
    public static function init(): bool
    {
        static::$init = My::checkContext(My::MANAGE);

        return static::$init;
    }

    /**
     * Processes the request(s).
     */
    public static function process(): bool
    {
        if (!static::$init) {
            return false;
        }

        if (!empty($_POST['uts_codes'])) {
            try {
                $uts_active = (bool) $_POST['uts_active'];
                $uts_sizes  = [];
                for ($i = 0; $i < (is_countable($_POST['uts_codes']) ? count($_POST['uts_codes']) : 0); $i++) {
                    $code = $_POST['uts_codes'][$i];
                    if (($code != '') && (!in_array($code, static::$excluded_codes))) {
                        $size  = isset($_POST['uts_sizes'][$i]) ? abs((int) $_POST['uts_sizes'][$i]) : 0;
                        $label = $_POST['uts_labels'][$i] ?? '';
                        $mode  = $_POST['uts_modes'][$i]  ?? 'ratio';
                        if (($size > 0) && ($label != '')) {
                            $uts_sizes[$code] = [$size, $label, $mode];
                        }
                    }
                }

                # Everything's fine, save options
                $settings = dcCore::app()->blog->settings->get(My::id());
                $settings->put('active', $uts_active);
                $settings->put('sizes', $uts_sizes, 'array');

                dcCore::app()->blog->triggerBlog();

                dcPage::addSuccessNotice(__('Settings have been successfully updated.'));
                dcCore::app()->adminurl->redirect('admin.plugin.' . My::id());
            } catch (Exception $e) {
                dcCore::app()->error->add($e->getMessage());
            }
        }

        return true;
    }

    /**
     * Renders the page.
     */
    public static function render(): void
    {
        if (!static::$init) {
            return;
        }

        dcPage::openModule(__('User defined thumbnails'));

        echo dcPage::breadcrumb(
            [
                Html::escapeHTML(dcCore::app()->blog->name) => '',
                __('User defined thumbnails')               => '',
            ]
        );
        echo dcPage::notices();

        // Form
        $settings   = dcCore::app()->blog->settings->get(My::id());
        $uts_active = (bool) $settings->active;
        $uts_sizes  = $settings->sizes;
        if (!is_array($uts_sizes)) {
            $uts_sizes = [];
        }

        $modes_combo  = ['ratio' => '', 'crop' => 'crop'];
        $code_pattern = '(?![' . implode('', array_filter(static::$excluded_codes, fn ($item) => strlen($item) <= 1)) . '])[a-z]';

        // Prepare rows
        $rows = [];
        foreach ($uts_sizes as $code => $size) {
            if (is_array($size)) {
                $rows[] = (new Para(null, 'tr'))->items([
                    (new Para(null, 'td'))->extra('scope="row"')->items([
                        (new Input(['uts_codes[]']))
                        ->size(1)
                        ->maxlength(1)
                        ->value($code)
                        ->pattern($code_pattern),
                    ]),
                    (new Para(null, 'td'))->items([
                        (new Number(['uts_sizes[]'], 0, 9_999, (int) $size[0])),
                    ]),
                    (new Para(null, 'td'))->items([
                        (new Select(['uts_modes[]']))
                            ->items($modes_combo)
                            ->default($size[2] ?? ''),
                    ]),
                    (new Para(null, 'td'))->items([
                        (new Input(['uts_labels[]']))
                        ->size(30)
                        ->maxlength(255)
                        ->value($size[1]),
                    ]),
                ]);
            }
        }
        // Empty row in order to add new thumbnail size
        $rows[] = (new Para(null, 'tr'))->items([
            (new Para(null, 'td'))->extra('scope="row"')->items([
                (new Input(['uts_codes[]']))
                ->size(1)
                ->maxlength(1)
                ->pattern($code_pattern),
            ]),
            (new Para(null, 'td'))->items([
                (new Number(['uts_sizes[]'], 0, 9_999)),
            ]),
            (new Para(null, 'td'))->items([
                (new Select(['uts_modes[]']))
                    ->items($modes_combo),
            ]),
            (new Para(null, 'td'))->items([
                (new Input(['uts_labels[]']))
                ->size(30)
                ->maxlength(255),
            ]),
        ]);

        echo
        (new Form('uts_form'))
            ->action(dcCore::app()->admin->getPageURL())
            ->method('post')
            ->fields([
                // Activation
                (new Para())->items([
                    (new Checkbox('uts_active', $uts_active))
                        ->value(1)
                        ->label((new Label(__('Activate user defined thumbnails for this blog'), Label::INSIDE_TEXT_AFTER))),
                ]),
                // Table
                (new Para(null, 'table'))->items([
                    // Caption
                    (new Text('caption', __('Thumbnails sizes')))->class('as_h3'),
                    // Head
                    (new Para(null, 'thead'))->items([
                        (new Para(null, 'tr'))->items([
                            (new Text('th', __('Code')))->extra('scope="col"'),
                            (new Text('th', __('Size in pixels')))->extra('scope="col"'),
                            (new Text('th', __('Mode')))->extra('scope="col"'),
                            (new Text('th', __('Label')))->extra('scope="col"'),
                        ]),
                    ]),
                    // Body
                    (new Para(null, 'tbody'))->items($rows),
                ]),
                // Info
                (new Para())->class('form-note')->items([
                    (new Text(null, __('Clear any field in row to delete this row'))),
                ]),
                (new Para())->class('form-note')->items([
                    (new Text(null, sprintf(__('Code must not be one of these: %s'), implode(', ', static::$excluded_codes)))),
                ]),
                (new Para())->class('form-note')->items([
                    (new Text(null, __('Mode: <strong>ratio</strong> will preserve aspect, <strong>crop</strong> will produce square'))),
                ]),
                // Submit
                (new Para())->items([
                    (new Submit(['frmsubmit']))
                        ->value(__('Save')),
                    dcCore::app()->formNonce(false),
                ]),
            ])
        ->render();

        dcPage::closeModule();
    }
}
