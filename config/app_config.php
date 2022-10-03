<?php

use Cake\Core\Configure;

define("APP_VERSION", "6.5.3");
define("CAKEPHP_APP_VERSION", "3.10.1");

// Cache
\Cake\Cache\Cache::setConfig('5min', [
    'className' => 'Cake\Cache\Engine\FileEngine',
    'duration' => '+5 minutes',
    'serialize' => true,
    'path' => CACHE . 'models' . DS,
    'prefix' => 'ms_',
]);

\Cake\Cache\Cache::setConfig('15min', [
    'className' => 'Cake\Cache\Engine\FileEngine',
    'duration' => '+15 minutes',
    'serialize' => true,
    'path' => CACHE . 'models' . DS,
    'prefix' => 'ms_',
]);

\Cake\Cache\Cache::setConfig('1hour', [
    'className' => 'Cake\Cache\Engine\FileEngine',
    'duration' => '+1 hour',
    'serialize' => true,
    'path' => CACHE . 'models' . DS,
    'prefix' => 'ms_',
]);

\Cake\Cache\Cache::setConfig('1day', [
    'className' => 'Cake\Cache\Engine\FileEngine',
    'duration' => '+1 day',
    'serialize' => true,
    'path' => CACHE . 'models' . DS,
    'prefix' => 'ms_',
]);

\Cake\Cache\Cache::setConfig('1week', [
    'className' => 'Cake\Cache\Engine\FileEngine',
    'duration' => '+1 week',
    'serialize' => true,
    'path' => CACHE . 'models' . DS,
    'prefix' => 'ms_',
]);

\Cake\Cache\Cache::setConfig('1month', [
    'className' => 'Cake\Cache\Engine\FileEngine',
    'duration' => '+1 month',
    'serialize' => true,
    'path' => CACHE . 'models' . DS,
    'prefix' => 'ms_',
]);

\Cake\Cache\Cache::setConfig('proxy', [
    'className' => 'Cake\Cache\Engine\FileEngine',
    'duration' => '+1 month',
    'serialize' => true,
    'path' => CACHE . 'proxy' . DS,
    'prefix' => 'ms_',
]);

// https://book.cakephp.org/3.0/en/core-libraries/logging.html#logging-scopes
\Cake\Log\Log::setConfig('payments', [
    'className' => 'File',
    'path' => LOGS,
    'levels' => [],
    'scopes' => ['payments'],
    'file' => 'payments.log',
]);

/**
 * A base URL to use for absolute links.
 */
//Configure::write('App.fullBaseUrl', get_option('base_url'));

/**
 * Set server timezone to UTC. You can change it to another timezone of your
 * choice but using UTC makes time calculations / conversions easier.
 */
date_default_timezone_set('UTC');

/**
 * Configure the mbstring extension to use the correct encoding.
 */
mb_internal_encoding(Configure::read('App.encoding'));

/**
 * Set the default locale. This controls how dates, number and currency is
 * formatted and sets the default language to use for translations.
 */
ini_set('intl.default_locale', get_option('language', 'en_US'));
