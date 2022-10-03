<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         3.5.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

/*
 * You can empty out this file, if you are certain that you match all requirements.
 */

/*
 * You can remove this if you are confident that your PHP version is sufficient.
 */

if (version_compare(PHP_VERSION, '7.3.0') < 0) {
    exit('Your PHP version must be equal or higher than 7.3.0 to use the script. ' .
        'Please ask your hosting company to update it.');
}

/*
 *  You can remove this if you are confident you have intl installed.
 */
if (!extension_loaded('intl')) {
    exit('You must enable the intl extension to use the script. Please ask your hosting company to enable it.');
}

/*
 * You can remove this if you are confident you have proper version of intl.
 */
if (version_compare(INTL_ICU_VERSION, '50.1', '<')) {
    exit('ICU >= 50.1 is needed to use CakePHP. Please update the `libicu` package of your system.');
}

/*
 *  You can remove this if you are confident you have intl installed.
 */
if (!extension_loaded('bcmath')) {
    exit('You must enable the bcmath extension to use the script. Please ask your hosting company to enable it.');
}

/*
 * You can remove this if you are confident you have mbstring installed.
 */
if (!extension_loaded('mbstring')) {
    exit('You must enable the mbstring extension to use the script. Please ask your hosting company to enable it.');
}

// Check if tmp directory and its subdirectories are writable
$root = dirname(__DIR__);

$config = $root . DIRECTORY_SEPARATOR . 'config';
$logs = $root . DIRECTORY_SEPARATOR . 'logs';
$tmp = $root . DIRECTORY_SEPARATOR . 'tmp';

$temp = [
    $config,
    $logs,
    $tmp,
    $tmp . DIRECTORY_SEPARATOR . 'cache',
    $tmp . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'models',
    $tmp . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'persistent',
    $tmp . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'proxy',
    $tmp . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'views',
    $tmp . DIRECTORY_SEPARATOR . 'sessions',
    $tmp . DIRECTORY_SEPARATOR . 'tests',
];

foreach ($temp as $dir) {
    if (!is_writable($dir)) {
        exit("<b>{$dir}</b> directory must be writable.");
    }
}
