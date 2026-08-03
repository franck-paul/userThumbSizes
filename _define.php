<?php

/**
 * @brief userThumbSizes, a plugin for Dotclear 2
 *
 * @package Dotclear
 * @subpackage Plugins
 *
 * @author Franck Paul
 *
 * @copyright Franck Paul contact@open-time.net
 * @copyright GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
$this->registerModule(
    'User defined thumbnails',
    'Add user defined thumbnails',
    'Franck Paul',
    '6.0',
    [
        'date'        => '2026-08-03T10:15:34+0200',
        'requires'    => [['core', '2.39']],
        'permissions' => 'My',
        'type'        => 'plugin',

        'details'    => 'https://open-time.net/?q=userThumbSizes',
        'support'    => 'https://github.com/franck-paul/userThumbSizes',
        'repository' => 'https://raw.githubusercontent.com/franck-paul/userThumbSizes/main/dcstore.xml',
        'license'    => 'gpl2',
    ]
);
