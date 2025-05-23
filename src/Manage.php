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

use Dotclear\App;
use Dotclear\Core\Backend\Notices;
use Dotclear\Core\Backend\Page;
use Dotclear\Core\Process;
use Dotclear\Helper\Html\Form\Caption;
use Dotclear\Helper\Html\Form\Checkbox;
use Dotclear\Helper\Html\Form\Form;
use Dotclear\Helper\Html\Form\Input;
use Dotclear\Helper\Html\Form\Label;
use Dotclear\Helper\Html\Form\Number;
use Dotclear\Helper\Html\Form\Para;
use Dotclear\Helper\Html\Form\Select;
use Dotclear\Helper\Html\Form\Submit;
use Dotclear\Helper\Html\Form\Table;
use Dotclear\Helper\Html\Form\Tbody;
use Dotclear\Helper\Html\Form\Td;
use Dotclear\Helper\Html\Form\Text;
use Dotclear\Helper\Html\Form\Th;
use Dotclear\Helper\Html\Form\Thead;
use Dotclear\Helper\Html\Form\Tr;
use Dotclear\Helper\Html\Html;
use Exception;

class Manage extends Process
{
    /**
     * Dotclear reserved thumbnails codes
     * sq = square
     * t = thumbnail
     * s = small
     * m = medium
     * o = original
     *
     * @var        array<string>
     */
    protected static array $excluded_codes = ['sq', 't', 's', 'm', 'o'];

    /**
     * Initializes the page.
     */
    public static function init(): bool
    {
        return self::status(My::checkContext(My::MANAGE));
    }

    /**
     * Processes the request(s).
     */
    public static function process(): bool
    {
        if (!self::status()) {
            return false;
        }

        if (!empty($_POST['uts_codes'])) {
            try {
                $uts_active = (bool) $_POST['uts_active'];
                $uts_sizes  = [];
                for ($i = 0; $i < (is_countable($_POST['uts_codes']) ? count($_POST['uts_codes']) : 0); ++$i) {
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
                $settings = My::settings();
                $settings->put('active', $uts_active);
                $settings->put('sizes', $uts_sizes, 'array');

                App::blog()->triggerBlog();

                Notices::addSuccessNotice(__('Settings have been successfully updated.'));
                My::redirect();
            } catch (Exception $e) {
                App::error()->add($e->getMessage());
            }
        }

        return true;
    }

    /**
     * Renders the page.
     */
    public static function render(): void
    {
        if (!self::status()) {
            return;
        }

        Page::openModule(My::name());

        echo Page::breadcrumb(
            [
                Html::escapeHTML(App::blog()->name()) => '',
                __('User defined thumbnails')         => '',
            ]
        );
        echo Notices::getNotices();

        // Form
        $settings   = My::settings();
        $uts_active = (bool) $settings->active;
        $uts_sizes  = $settings->sizes;
        if (!is_array($uts_sizes)) {
            $uts_sizes = [];
        }

        $modes_combo  = ['ratio' => '', 'crop' => 'crop'];
        $code_pattern = '(?![' . implode('', array_filter(static::$excluded_codes, static fn ($item): bool => strlen($item) <= 1)) . '])[a-z]';

        // Prepare rows
        $rows = [];
        foreach ($uts_sizes as $code => $size) {
            if (is_array($size)) {
                $rows[] = (new Tr())
                    ->items([
                        (new Td())
                            ->items([
                                (new Input(['uts_codes[]']))
                                ->size(1)
                                ->maxlength(1)
                                ->value($code)
                                ->pattern($code_pattern),
                            ]),
                        (new Td())
                            ->items([
                                (new Number(['uts_sizes[]'], 0, 9_999, (int) $size[0])),
                            ]),
                        (new Td())
                            ->items([
                                (new Select(['uts_modes[]']))
                                    ->items($modes_combo)
                                    ->default($size[2] ?? ''),
                            ]),
                        (new Td())
                            ->items([
                                (new Input(['uts_labels[]']))
                                ->size(30)
                                ->maxlength(255)
                                ->value($size[1]),
                            ]),
                    ]);
            }
        }

        // Empty row in order to add new thumbnail size
        $rows[] = (new Tr())
            ->items([
                (new Td())
                    ->items([
                        (new Input(['uts_codes[]']))
                        ->size(1)
                        ->maxlength(1)
                        ->pattern($code_pattern),
                    ]),
                (new Td())
                    ->items([
                        (new Number(['uts_sizes[]'], 0, 9_999)),
                    ]),
                (new Td())
                    ->items([
                        (new Select(['uts_modes[]']))
                            ->items($modes_combo),
                    ]),
                (new Td())
                    ->items([
                        (new Input(['uts_labels[]']))
                        ->size(30)
                        ->maxlength(255),
                    ]),
            ]);

        echo
        (new Form('uts_form'))
            ->action(App::backend()->getPageURL())
            ->method('post')
            ->fields([
                // Activation
                (new Para())->items([
                    (new Checkbox('uts_active', $uts_active))
                        ->value(1)
                        ->label((new Label(__('Activate user defined thumbnails for this blog'), Label::INSIDE_TEXT_AFTER))),
                ]),
                // Table
                (new Table())
                    ->caption((new Caption(__('Thumbnails sizes')))->class('as_h3'))
                    ->items([
                        // Head
                        (new Thead())
                            ->items([
                                (new Tr())
                                    ->items([
                                        (new Th())
                                            ->text(__('Code'))
                                            ->scope('col'),
                                        (new Th())
                                            ->text(__('Size in pixels'))
                                            ->scope('col'),
                                        (new Th())
                                            ->text(__('Mode'))
                                            ->scope('col'),
                                        (new Th())
                                            ->text(__('Label'))
                                            ->scope('col'),
                                    ]),
                            ]),
                        // Body
                        (new Tbody())
                            ->items($rows),
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
                    ... My::hiddenFields(),
                ]),
            ])
        ->render();

        Page::closeModule();
    }
}
