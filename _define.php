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

if (!defined('DC_RC_PATH')) {return;}

$this->registerModule(
    "User defined thumbnails",     // Name
    "Add user defined thumbnails", // Description
    "Franck Paul",                 // Author
    '0.5',                         // Version
	array(
        'requires'    => array(array('core', '2.14')),
        'permissions' => 'usage,contentadmin',
        'support'     => 'https://open-time.net/?q=userThumbSizes',
        'type'        => 'plugin'
	)
);
