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
$this->registerModule(
    'User defined thumbnails',
    'Add user defined thumbnails',
    'Franck Paul',
    '4.4',
    [
        'date'        => '2025-05-05T13:28:19+0200',
        'requires'    => [['core', '2.28']],
        'permissions' => 'My',
        'type'        => 'plugin',

        'details'    => 'https://open-time.net/?q=userThumbSizes',
        'support'    => 'https://github.com/franck-paul/userThumbSizes',
        'repository' => 'https://raw.githubusercontent.com/franck-paul/userThumbSizes/main/dcstore.xml',
        'license'    => 'gpl2',
    ]
);
