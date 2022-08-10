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
if (!defined('DC_RC_PATH')) {
    return;
}

$this->registerModule(
    'User defined thumbnails',     // Name
    'Add user defined thumbnails', // Description
    'Franck Paul',                 // Author
    '0.6',                         // Version
    [
        'requires'    => [['core', '2.23']],
        'permissions' => 'usage,contentadmin',
        'type'        => 'plugin',

        'details'    => 'https://open-time.net/?q=userThumbSizes',        // Details URL
        'support'    => 'https://github.com/franck-paul/userThumbSizes',  // Support URL
        'repository' => 'https://raw.githubusercontent.com/franck-paul/userThumbSizes/master/dcstore.xml',
    ]
);
