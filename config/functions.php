<?php

use Cake\Core\Configure;

//\Cake\Cache\Cache::disable();

function database_connect()
{
    try {
        $connection = \Cake\Datasource\ConnectionManager::get('default');
        $connected = $connection->connect();
    } catch (\Exception $ex) {
        $connected = false;
        $errorMsg = $ex->getMessage();
        if (method_exists($ex, 'getAttributes')) {
            $attributes = $ex->getAttributes();
            if (isset($errorMsg['message'])) {
                $errorMsg .= '<br />' . $attributes['message'];
            }
        }
    }

    return $connected;
}

function is_app_installed()
{
    if (Configure::read('Adlinkfly.installed')) {
        return true;
    }

    if ((bool)get_option('installed', 0)) {
        return true;
    }

    return false;
}

function get_option($name, $default = '')
{
    if (!database_connect()) {
        return $default;
    }

    try {
        static $settings;

        if (!isset($settings)) {
            $options = \Cake\ORM\TableRegistry::getTableLocator()->get('Options');
            //$query   = $options->find()->select( ['name', 'value' ] )->cache( 'options' )->all();
            $query = $options->find()->select(['name', 'value'])->all();
            $settings = [];
            foreach ($query as $row) {
                $settings[$row->name] = (is_serialized($row->value)) ? unserialize($row->value) : $row->value;
            }
        }

        if (!array_key_exists($name, $settings)) {
            return $default;
        }

        if (is_array($settings[$name])) {
            return (!empty($settings[$name])) ? $settings[$name] : $default;
        } else {
            return (isset($settings[$name]) && strlen($settings[$name]) > 0) ? $settings[$name] : $default;
        }
    } catch (\Exception $ex) {
        return $default;
    }
}

// https://github.com/WordPress/WordPress/blob/5.8.1/wp-includes/functions.php#L642
function is_serialized($data, $strict = true)
{
    // If it isn't a string, it isn't serialized.
    if (!is_string($data)) {
        return false;
    }
    $data = trim($data);
    if ('N;' === $data) {
        return true;
    }
    if (strlen($data) < 4) {
        return false;
    }
    if (':' !== $data[1]) {
        return false;
    }
    if ($strict) {
        $lastc = substr($data, -1);
        if (';' !== $lastc && '}' !== $lastc) {
            return false;
        }
    } else {
        $semicolon = strpos($data, ';');
        $brace = strpos($data, '}');
        // Either ; or } must exist.
        if (false === $semicolon && false === $brace) {
            return false;
        }
        // But neither must be in the first X characters.
        if (false !== $semicolon && $semicolon < 3) {
            return false;
        }
        if (false !== $brace && $brace < 4) {
            return false;
        }
    }
    $token = $data[0];
    switch ($token) {
        case 's':
            if ($strict) {
                if ('"' !== substr($data, -2, 1)) {
                    return false;
                }
            } elseif (false === strpos($data, '"')) {
                return false;
            }
        // Or else fall through.
        case 'a':
        case 'O':
            return (bool)preg_match("/^{$token}:[0-9]+:/s", $data);
        case 'b':
        case 'i':
        case 'd':
            $end = $strict ? '$' : '';
            return (bool)preg_match("/^{$token}:[0-9.E+-]+;$end/", $data);
    }
    return false;
}

/*
function is_serialized($data)
{
    if (@unserialize($data) === false) {
        return false;
    } else {
        return true;
    }
}
*/

function get_http_headers($url, $options = [])
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_NOBODY, true);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);

    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 1);

    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    foreach ($options as $option => $value) {
        curl_setopt($ch, $option, $value);
    }
    $headers_string = curl_exec($ch);

    curl_close($ch);

    $data = [];
    //$headers = explode(PHP_EOL, $headers_string);
    $headers = explode("\n", str_replace("\r", "\n", $headers_string));
    foreach ($headers as $header) {
        $parts = explode(':', $header);
        if (count($parts) === 2) {
            $data[strtolower(trim($parts[0]))] = strtolower(trim($parts[1]));
        }
    }

    return $data;
}

function curlRequest($url, $method = 'GET', $data = [], $headers = [], $options = [])
{
    $ch = curl_init();

    switch ($method) {
        case "POST":
            curl_setopt($ch, CURLOPT_POST, 1);

            if ($data) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            }
            break;
        case "PUT":
            curl_setopt($ch, CURLOPT_PUT, 1);
            break;
        default:
            if ($data) {
                $url = sprintf("%s?%s", $url, http_build_query($data));
            }
    }

    curl_setopt($ch, CURLOPT_URL, $url);
    if ($headers) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    if (empty(@ini_get('open_basedir'))) {
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 1);
    }
    //curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    //curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    if (null != env('HTTP_USER_AGENT')) {
        curl_setopt($ch, CURLOPT_USERAGENT, env('HTTP_USER_AGENT'));
    }

    foreach ($options as $option => $value) {
        curl_setopt($ch, $option, $value);
    }

    $response = curl_exec($ch);
    //$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    $error = '';
    if (curl_errno($ch)) {
        $error = curl_error($ch);
        \Cake\Log\Log::write('error', curl_error($ch));
    }

    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);

    curl_close($ch);

    $result = new \stdClass();
    $result->header = substr($response, 0, $header_size);
    $result->body = substr($response, $header_size);
    $result->error = $error;

    return $result;
}

function curlHtmlHeadRequest($url, $method = 'GET', $data = [], $headers = [], $options = [])
{
    $obj = new \stdClass(); //create an object variable to access class functions and variables
    $obj->result = '';

    $ch = curl_init();

    switch ($method) {
        case "POST":
            curl_setopt($ch, CURLOPT_POST, 1);

            if ($data) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            }
            break;
        case "PUT":
            curl_setopt($ch, CURLOPT_PUT, 1);
            break;
        default:
            if ($data) {
                $url = sprintf("%s?%s", $url, http_build_query($data));
            }
    }

    curl_setopt($ch, CURLOPT_URL, $url);
    if ($headers) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }

    //curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_WRITEFUNCTION, function ($ch, $str) use ($obj) {
        $obj->result .= $str;
        /*
          if (stripos($obj->result, '<body') !== false) {
          return false;
          }
         */
        return strlen($str); //return the exact length
    });
    curl_setopt($ch, CURLOPT_NOPROGRESS, false);
    curl_setopt($ch, CURLOPT_PROGRESSFUNCTION, function ($ch, $downloadSize, $downloaded, $uploadSize, $uploaded) {
        // If $Downloaded exceeds 128KB, returning non-0 breaks the connection!
        return ($downloaded > (128 * 1024)) ? 1 : 0;
    });

    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    if (empty(@ini_get('open_basedir'))) {
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 1);
    }
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    if (null != env('HTTP_USER_AGENT')) {
        curl_setopt($ch, CURLOPT_USERAGENT, env('HTTP_USER_AGENT'));
    }

    foreach ($options as $option => $value) {
        curl_setopt($ch, $option, $value);
    }

    curl_exec($ch);
    //$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_errno($ch)) {
        //\Cake\Log\Log::write('error', curl_error($ch));
    }

    curl_close($ch);

    return $obj->result;
}

function emptyCache()
{
    $dir = new \Cake\Filesystem\Folder(CACHE);
    $files = $dir->findRecursive('.*', true);

    foreach ($files as $file) {
        $file = new \Cake\Filesystem\File($file);
        if (!in_array($file->name, ['empty', 'ms_license_response_result'])) {
            @$file->delete();
        }
        $file->close();
    }
}

function emptyLogs()
{
    $dir = new \Cake\Filesystem\Folder(LOGS);
    $files = $dir->findRecursive('.*', true);

    foreach ($files as $file) {
        $file = new \Cake\Filesystem\File($file);
        if (!in_array($file->name, ['empty'])) {
            @$file->delete();
        }
        $file->close();
    }
}

function isset_captcha()
{
    $enable_captcha = get_option('enable_captcha', 'no');
    if ('yes' != $enable_captcha) {
        return false;
    }

    $captcha_type = get_option('captcha_type', 'recaptcha');

    if ($captcha_type === 'securimage') {
        return true;
    }

    if ($captcha_type === 'recaptcha') {
        $recaptcha_siteKey = get_option('reCAPTCHA_site_key');
        $recaptcha_secretKey = get_option('reCAPTCHA_secret_key');
        if (!empty($recaptcha_siteKey) && !empty($recaptcha_secretKey)) {
            return true;
        }
    }

    if ($captcha_type === 'invisible-recaptcha') {
        $recaptcha_siteKey = get_option('invisible_reCAPTCHA_site_key');
        $recaptcha_secretKey = get_option('invisible_reCAPTCHA_secret_key');
        if (!empty($recaptcha_siteKey) && !empty($recaptcha_secretKey)) {
            return true;
        }
    }

    if ($captcha_type === 'hcaptcha_checkbox') {
        $hcaptcha_checkbox_site_key = get_option('hcaptcha_checkbox_site_key');
        $hcaptcha_checkbox_secret_key = get_option('hcaptcha_checkbox_secret_key');
        if (!empty($hcaptcha_checkbox_site_key) && !empty($hcaptcha_checkbox_secret_key)) {
            return true;
        }
    }

    if ($captcha_type === 'solvemedia') {
        $solvemedia_challenge_key = get_option('solvemedia_challenge_key');
        $solvemedia_verification_key = get_option('solvemedia_verification_key');
        $solvemedia_authentication_key = get_option('solvemedia_authentication_key');
        if (!empty($solvemedia_challenge_key) &&
            !empty($solvemedia_verification_key) &&
            !empty($solvemedia_authentication_key)
        ) {
            return true;
        }
    }

    return false;
}

function generate_random_string($length = 10, $special = false)
{
    $specialChars = '~!@#$%^&*(){}[],./?';
    $alphaNum = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    $all_chars = $alphaNum;
    if ($special) {
        $all_chars .= $specialChars;
    }

    $string = '';
    $i = 0;
    while ($i < $length) {
        $random = mt_rand(0, strlen($all_chars) - 1);
        $string .= $all_chars[$random];
        $i = $i + 1;
    }

    return $string;
}

/**
 * Generate random IP address
 * @return string Random IP address
 */
function random_ipv4()
{
    // http://stackoverflow.com/a/10268612
    //return mt_rand(0,255).".".mt_rand(0,255).".".mt_rand(0,255).".".mt_rand(0,255);
    // // http://board.phpbuilder.com/showthread.php?10346623-Generating-a-random-IP-Address&p=10830872&viewfull=1#post10830872
    return long2ip(rand(0, 255 * 255) * rand(0, 255 * 255));
}

/**
 * Get client IP address
 * @return string IP address
 */
function get_ip()
{
    static $ip;

    if (!isset($ip)) {
        if (!empty($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $ip = $_SERVER["HTTP_CF_CONNECTING_IP"];
        } elseif (!empty($_SERVER["HTTP_FASTLY_CLIENT_IP"])) {
            $ip = $_SERVER["HTTP_FASTLY_CLIENT_IP"];
        } elseif (!empty($_SERVER["HTTP_CLIENT_IP"])) {
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        } elseif (!empty($_SERVER["HTTP_X_REAL_IP"])) {
            $ip = $_SERVER["HTTP_X_REAL_IP"];
        } else {
            $ip = $_SERVER["REMOTE_ADDR"];
        }

        if (strstr($ip, ',')) {
            $tmp = explode(',', $ip);
            $ip = trim($tmp[0]);
        }
        //$ip = random_ipv4();
    }

    return $ip;
}

/**
 * @return bool
 */
function validCrawler()
{
    $ips = file_get_contents(APP . 'Library/ips.txt');
    $ips = array_filter(array_map('trim', explode("\n", $ips)));

    return \App\Library\IpUtils::checkIp(get_ip(), $ips);
}

function price_database_format($price = 0)
{
    return number_format(floatval($price), 9, '.', '');
}

function display_price_currency($price, $options = [])
{
    $defaults = [
        'precision' => get_option('price_decimals', 6),
        'places' => get_option('price_decimals', 6),
        'locale' => locale_get_default(),
        get_option('currency_position', 'before') => get_option('currency_symbol', '$'),
    ];
    $options = array_merge($defaults, $options);

    return \Cake\I18n\Number::format($price, $options);
}

function display_date_timezone($time)
{
    if (!$time) {
        return '';
    }

    try {
        return \Cake\I18n\Time::instance($time)->i18nFormat(null, get_option('timezone', 'UTC'), null);
    } catch (\Exception $exception) {
        return $time;
    }
}

function require_database_upgrade()
{
    if (version_compare(APP_VERSION, get_option('app_version', '1.0.0'), '>')) {
        return true;
    }

    return false;
}

function get_logo()
{
    $site_name = h(get_option('site_name'));
    $logo_url = h(get_option('logo_url'));

    $data = ['type' => 'text', 'content' => $site_name];

    if (!empty($logo_url)) {
        $data['type'] = 'image';
        $data['content'] = "<img src='{$logo_url}' alt='{$site_name}' />";
    }

    return $data;
}

function get_logo_alt()
{
    $site_name = h(get_option('site_name'));
    $logo_url = h(get_option('logo_url_alt'));

    $data = ['type' => 'text', 'content' => $site_name];

    if (!empty($logo_url)) {
        $data['type'] = 'image';
        $data['content'] = "<img src='{$logo_url}' alt='{$site_name}' />";
    }

    return $data;
}

function build_main_domain_url($path = null)
{
    if (preg_match('#^(http://|https://|//)#i', $path) === 1) {
        return $path;
    }

    static $base_url;

    if (!isset($base_url)) {
        $request = \Cake\Routing\Router::getRequest();

        $base = '';
        if ($request !== null) {
            $base = $request->getAttribute("base");
        }

        $main_domain = get_option('main_domain');

        $protocol = (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off") ? "http://" : "https://";

        $base_url = $protocol . $main_domain . $base;
    }

    $url = $base_url;

    if ($path) {
        $url .= $path;
    }

    return $url;
}

function get_short_url($alias = '', $domain = '')
{
    //\Cake\Routing\Router::url(['_name' => 'short', 'alias' => 'jimmy'], true);
    if (empty($domain)) {
        $domain = get_default_short_domain();
    }

    $request = \Cake\Routing\Router::getRequest();

    $scheme = 'http://';
    if (get_option('https_shortlinks', false)) {
        $scheme = 'https://';
    }

    $base_url = $scheme . $domain . $request->getAttribute("base");

    return $base_url . '/' . $alias;
}

function get_default_short_domain()
{
    $default_short_domain = get_option('default_short_domain', '');
    if (!empty($default_short_domain)) {
        return $default_short_domain;
    }

    $main_domain = get_option('main_domain', '');
    if (!empty($main_domain)) {
        return $main_domain;
    }

    return env("HTTP_HOST", "");
}

function get_multi_domains_list()
{
    $domains = explode(',', get_option('multi_domains'));
    $domains = array_map('trim', $domains);
    $domains = array_filter($domains);
    $domains = array_unique($domains);
    $domains = array_combine($domains, $domains);

    $default_short_domain = get_option('default_short_domain', '');

    unset($domains[$default_short_domain]);

    return $domains;
}

function get_all_multi_domains_list()
{
    $domains = get_multi_domains_list();
    $add_domains = [];

    $default_short_domain = get_option('default_short_domain', '');
    if (!empty($default_short_domain)) {
        $add_domains[$default_short_domain] = $default_short_domain;

        return $add_domains + $domains;
    }

    $main_domain = get_option('main_domain', '');
    if (!empty($main_domain)) {
        $add_domains[$main_domain] = $main_domain;

        return $add_domains + $domains;
    }

    $add_domains[$_SERVER['HTTP_HOST']] = $_SERVER['HTTP_HOST'];

    return $add_domains + $domains;
}

function get_all_domains_list()
{
    $domains = get_multi_domains_list();
    $add_domains = [];

    $default_short_domain = get_option('default_short_domain', '');
    if (!empty($default_short_domain)) {
        $add_domains[$default_short_domain] = $default_short_domain;
        $add_domains = $add_domains + $domains;
    }

    $main_domain = get_option('main_domain', '');
    if (!empty($main_domain)) {
        $add_domains[$main_domain] = $main_domain;
        $add_domains = $add_domains + $domains;
    }

    $add_domains[$_SERVER['HTTP_HOST']] = $_SERVER['HTTP_HOST'];

    return $add_domains + $domains;
}

function get_allowed_ads()
{
    $plan = user_or_anonymous()->plan;

    $ads = [];

    if (version_compare(get_option('app_version'), '6.5.0', '<')) {
        if (get_option('enable_noadvert', 'yes') == 'yes') {
            $ads[0] = __('No Advert');
        }

        if (get_option('enable_interstitial', 'yes') == 'yes') {
            $ads[1] = __('Interstitial Advertisement');
        }

        if (get_option('enable_banner', 'yes') == 'yes') {
            $ads[2] = __('Banner Advertisement');
        }

        if ((bool)get_option('enable_random_ad_type', 0)) {
            if (array_key_exists(1, $ads) && array_key_exists(2, $ads)) {
                $ads[3] = __('Random(Interstitial or Banner) Advertisement');
            }
        }
    } else {
        if ($plan->direct_redirect) {
            $ads[0] = __('No Advert');
        }

        if ($plan->interstitial_redirect) {
            $ads[1] = __('Interstitial Advertisement');
        }

        if ($plan->banner_redirect) {
            $ads[2] = __('Banner Advertisement');
        }

        if ($plan->random_redirect) {
            if (array_key_exists(1, $ads) && array_key_exists(2, $ads)) {
                $ads[3] = __('Random(Interstitial or Banner) Advertisement');
            }
        }
    }

    return $ads;
}

function get_statistics_reasons()
{
    return [
        0 => __("---"),
        1 => __("Earn"),
        2 => __("Disabled Cookies"),
        3 => __("Anonymous User"),
        4 => __("Adblock"),
        5 => __("Proxy"),
        6 => __("IP Changed"),
        7 => __("Not Unique"),
        8 => __("Full Weight"),
        9 => __("Default Campaign"),
        10 => __("Direct"),
        11 => __("Invalid country"),
        12 => __("Earnings disabled"),
        13 => __("User disabled earnings"),
        14 => __("Blocked referer domain"),
        15 => __("Reached the hourly limit"),
        16 => __("Reached the daily limit"),
        17 => __("Reached the monthly limit"),
    ];
}

function get_link_methods($method = null)
{
    $methods = [
        1 => __('Website'),
        2 => __('Quick Link'),
        3 => __('Mass Shrinker'),
        4 => __('Full Page Script'),
        5 => __('API'),
        6 => __('Bookmarklet'),
    ];

    if ($method === null) {
        return $methods;
    }

    return $methods[$method];
}

function get_payment_methods()
{
    $payment_methods = [];

    if ((bool)get_option('wallet_enable', false)) {
        $payment_methods['wallet'] = __("My Wallet");
    }

    if (get_option('paypal_enable', 'no') == 'yes') {
        $payment_methods['paypal'] = __("PayPal");
    }

    if ((bool)get_option('stripe_enable', false)) {
        $payment_methods['stripe'] = __("Stripe");
    }

    if ((bool)get_option('skrill_enable', false)) {
        $payment_methods['skrill'] = __("Skrill");
    }

    if (get_option('bitcoin_processor', 'coinbase') === 'coinpayments' &&
        (bool)get_option('coinpayments_enable', false)) {
        $payment_methods['coinpayments'] = __("Bitcoin");
    }

    if (get_option('bitcoin_processor', 'coinbase') === 'coinbase' &&
        get_option('coinbase_enable', 'no') == 'yes') {
        $payment_methods['coinbase'] = __("Bitcoin");
    }

    if (get_option('webmoney_enable', 'no') == 'yes') {
        $payment_methods['webmoney'] = __("Webmoney");
    }

    if ((bool)get_option('perfectmoney_enable', false)) {
        $payment_methods['perfectmoney'] = __("Perfect Money");
    }

    if ((bool)get_option('payeer_enable', false)) {
        $payment_methods['payeer'] = __("Payeer");
    }

    if ((bool)get_option('paystack_enable', false)) {
        $payment_methods['paystack'] = __("Paystack");
    }

    if ((bool)get_option('paytm_enable', false)) {
        $payment_methods['paytm'] = __("Paytm");
    }

    if (get_option('banktransfer_enable', 'no') == 'yes') {
        $payment_methods['banktransfer'] = __("Bank Transfer");
    }

    return $payment_methods;
}

function get_withdrawal_methods()
{
    $options = \Cake\ORM\TableRegistry::getTableLocator()->get('Options');

    $withdrawal_methods = json_decode($options->findByName('withdraw_methods')->first()->value, true);

    $methods = [];

    foreach ($withdrawal_methods as $withdrawal_method) {
        if ($withdrawal_method['status']) {
            $methods[] = $withdrawal_method;
        }
    }

    if ((bool)get_option('wallet_enable', false)) {
        $methods[] = [
            'id' => 'wallet',
            'name' => __('My Wallet'),
            'amount' => get_option('wallet_withdrawal_amount', 5),
            'image' => '',
            'description' => '',
        ];
    }

    return $methods;
}

function get_site_languages($all = false)
{
    $default_language = get_option('language');
    $site_languages = get_option('site_languages', []);
    $site_languages = array_combine($site_languages, $site_languages);
    unset($site_languages[$default_language]);
    if ($all === true) {
        $site_languages[$default_language] = $default_language;
    }
    ksort($site_languages);

    return $site_languages;
}

/**
 * @return \App\Model\Entity\User|null
 */
function user()
{
    $request = \Cake\Routing\Router::getRequest();
    $user_id = $request->getSession()->read('Auth.User.id');

    if ($user_id === null) {
        return null;
    }

    /**
     * @var \App\Model\Table\UsersTable $users
     */
    $users = \Cake\ORM\TableRegistry::getTableLocator()->get('Users');

    return $users->find()->contain(['Plans'])->where(['Users.id' => $user_id])->first();
}

/**
 * @return \App\Model\Entity\User
 */
function user_or_anonymous()
{
    $user = user();

    if ($user) {
        return $user;
    }

    /**
     * @var \App\Model\Table\UsersTable $usersTable
     */
    $usersTable = \Cake\ORM\TableRegistry::getTableLocator()->get('Users');

    return $usersTable->find()->contain(['Plans'])->where(['Users.id' => 1])->first();
}

/**
 * @param int $user_id
 * @return \App\Model\Entity\Plan
 */
function get_user_plan($user_id)
{
    /**
     * @var \App\Model\Entity\User $user
     */
    $user = \Cake\ORM\TableRegistry::getTableLocator()->get('Users')->find()
        ->contain(['Plans'])->where(['Users.id' => $user_id])->first();

    $expiration = $user->expiration;

    /*
    if (is_object($user)) {
        $expiration = $user->expiration;
    }

    if (is_array($user)) {
        $expiration = $user['expiration'];
        $user = json_decode(json_encode($user), false);
    }
    */

    if ($user->plan_id == 1) {
        return $user->plan;
    }

    static $default_plan;

    if (!isset($default_plan)) {
        $default_plan = \Cake\ORM\TableRegistry::getTableLocator()->get('Plans')->get(1);
    }

    if (!isset($expiration)) {
        return $user->plan;
    }

    $time = new \Cake\I18n\Time($expiration);

    if ($time->isPast()) {
        return $default_plan;
    }

    return $user->plan;
}

function campaign_statuses($id = null)
{
    $statuses = [
        1 => __('Active'),
        2 => __('Paused'),
        3 => __('Canceled'),
        4 => __('Finished'),
        5 => __('Under Review'),
        6 => __('Pending Payment'),
        7 => __('Invalid Payment'),
        8 => __('Refunded'),
    ];

    if ($id === null) {
        return $statuses;
    }

    return $statuses[$id];
}

function withdraw_statuses($id = null)
{
    $statuses = [
        1 => __('Approved'),
        2 => __('Pending'),
        3 => __('Complete'),
        4 => __('Cancelled'),
        5 => __('Returned'),
    ];

    if ($id === null) {
        return $statuses;
    }

    return $statuses[$id];
}

function invoice_statuses($id = null)
{
    $statuses = [
        1 => __('Paid'),
        2 => __('Unpaid'),
        3 => __('Canceled'),
        4 => __('Invalid Payment'),
        5 => __('Refunded'),
    ];

    if ($id === null) {
        return $statuses;
    }

    return $statuses[$id];
}

function data_encrypt($value)
{
    $key = \Cake\Utility\Security::getSalt();
    $value = serialize($value);
    $value = \Cake\Utility\Security::encrypt($value, $key);

    return base64_encode($value);
}

function data_decrypt($value)
{
    if (!is_string($value)) {
        return false;
    }

    $key = \Cake\Utility\Security::getSalt();
    $value = base64_decode($value);
    $value = \Cake\Utility\Security::decrypt($value, $key);

    return unserialize($value);
}

function createEmailFile()
{
    /**
     * @var \App\Model\Table\OptionsTable|\App\Model\Entity\Option[]
     */
    $options = \Cake\ORM\TableRegistry::getTableLocator()->get('Options');

    $config = [
        'site_name' => $options->findByName('site_name')->first()->value,
        'email_from' => $options->findByName('email_from')->first()->value,
        'email_method' => $options->findByName('email_method')->first()->value,
        'email_smtp_host' => $options->findByName('email_smtp_host')->first()->value,
        'email_smtp_port' => $options->findByName('email_smtp_port')->first()->value,
        'email_smtp_username' => $options->findByName('email_smtp_username')->first()->value,
        'email_smtp_password' => $options->findByName('email_smtp_password')->first()->value,
        'email_smtp_tls' => 'false',
    ];

    $config = array_map(function ($value) {
        return addcslashes($value, '\'');
    }, $config);

    $email_smtp_security = $options->findByName('email_smtp_security')->first()->value;

    if (preg_match('#^ssl://#i', $config['email_smtp_host'])) {
        $config['email_smtp_host'] = preg_replace('#^ssl://#i', '', $config['email_smtp_host']);
        $email_smtp_host = $options->findByName('email_smtp_host')->first();
        $email_smtp_host->value = $config['email_smtp_host'];
        $options->save($email_smtp_host);
    }

    if ($email_smtp_security == 'tls') {
        $config['email_smtp_tls'] = 'true';
    }

    if ($email_smtp_security == 'ssl') {
        $config['email_smtp_host'] = 'ssl://' . $config['email_smtp_host'];
    }

    $result = copy(CONFIG . 'email.install', CONFIG . 'email.php');
    if (!$result) {
        \Cake\Log\Log::write('debug', 'Could not copy email.php file.');

        return false;
    }

    $file = new \Cake\Filesystem\File(CONFIG . 'email.php');
    $content = $file->read();

    foreach ($config as $configKey => $configValue) {
        $content = str_replace('{' . $configKey . '}', $configValue, $content);
    }

    if (!$file->write($content)) {
        \Cake\Log\Log::write('debug', 'Could not write email.php file.');

        return false;
    }

    return true;
}

/**
 * @see https://github.com/symfony/polyfill-php55/blob/v1.10.0/Php55ArrayColumn.php#L32
 *
 * @param array $input
 * @param $columnKey
 * @param null $indexKey
 *
 * @return array
 */
function array_column_polyfill(array $input, $columnKey, $indexKey = null)
{
    return array_column($input, $columnKey, $indexKey);

    $output = [];
    foreach ($input as $row) {
        $key = $value = null;
        $keySet = $valueSet = false;
        if (null !== $indexKey && array_key_exists($indexKey, $row)) {
            $keySet = true;
            $key = (string)$row[$indexKey];
        }
        if (null === $columnKey) {
            $valueSet = true;
            $value = $row;
        } elseif (\is_array($row) && \array_key_exists($columnKey, $row)) {
            $valueSet = true;
            $value = $row[$columnKey];
        }
        if ($valueSet) {
            if ($keySet) {
                $output[$key] = $value;
            } else {
                $output[] = $value;
            }
        }
    }

    return $output;
}

function menu_display($name, $options = [], $language = false)
{
    $request = \Cake\Routing\Router::getRequest();
    $user_id = $request->getSession()->read('Auth.User.id');

    /**
     * @var \App\Model\Table\OptionsTable|\App\Model\Entity\Option[]
     */
    $optionsTable = \Cake\ORM\TableRegistry::getTableLocator()->get('Options')
        ->addBehavior('Translate', ['fields' => ['value']]);

    $menu = $optionsTable->findByName($name)->first();
    $menuItems = json_decode($menu->value);

    $options = array_merge([
        'ul_class' => '',
        'li_class' => '',
        'a_class' => '',
    ], $options);

    $html = '<ul class="' . h($options['ul_class']) . '">';
    foreach ($menuItems as $item) {
        if (($item->visibility === 'all') ||
            ($user_id && $item->visibility === 'logged') ||
            (!$user_id && $item->visibility === 'guest')
        ) {
            $html .= '<li class="' . h($options['li_class']) . ' ' . h($item->class) . '">' .
                '<a class="' . h($options['a_class']) . '" href="' . build_main_domain_url($item->link) . '">' .
                '<span>' . h($item->title) . '</span>' .
                '</a>' .
                '</li>';
        }
    }

    if ($language) {
        if (count(get_site_languages(true)) > 1) {
            $html .= '<li class="dropdown language-selector">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
               aria-expanded="false"><i class="fa fa-language"></i> <span class="caret"></span></a>
            <ul class="dropdown-menu">';
            foreach (get_site_languages(true) as $lang) {
                $html .= '<li>';
                $html .= '<a href="' . \Cake\Routing\Router::url($request->getPath()) . '?lang=' . $lang . '">' .
                    locale_get_display_name($lang, $lang) . '</a>';

                $html .= '</li>';
            }
            $html .= '</ul>
        </li>';
        }
    }

    $html .= '</ul>';

    return $html;
}

function isScheduleCronRunning()
{
    $schedule_cron_last_time_run = get_option('schedule_cron_last_time_run');

    if (!$schedule_cron_last_time_run) {
        return false;
    }

    return \Cake\I18n\Time::createFromFormat('Y-m-d H:i:s', $schedule_cron_last_time_run)->addMinutes(10)->isFuture();
}

function isQueueCronRunning()
{
    $file_path = ROOT . '/tmp/queue.lock';
    if (!file_exists($file_path)) {
        if (!touch($file_path)) {
            return false;
        }
    }

    $lock_pointer = fopen($file_path, "r+");
    if (!flock($lock_pointer, LOCK_EX | LOCK_NB, $wouldBlock)) {
        if ($wouldBlock) {
            return true;
        }
    }

    return false;
}

function get_countries($campaing = false)
{
    $countries = [
        "AF" => __("Afganistan"),
        "AL" => __("Albania"),
        "DZ" => __("Algeria"),
        "AS" => __("American Samoa"),
        "AD" => __("Andorra"),
        "AO" => __("Angola"),
        "AI" => __("Anguilla"),
        "AQ" => __("Antarctica"),
        "AG" => __("Antigua and Barbuda"),
        "AR" => __("Argentina"),
        "AM" => __("Armenia"),
        "AW" => __("Aruba"),
        "AU" => __("Australia"),
        "AT" => __("Austria"),
        "AX" => __("Åland Islands"),
        "AZ" => __("Azerbaijan"),
        "BS" => __("Bahamas"),
        "BH" => __("Bahrain"),
        "BD" => __("Bangladesh"),
        "BB" => __("Barbados"),
        "BY" => __("Belarus"),
        "BE" => __("Belgium"),
        "BZ" => __("Belize"),
        "BJ" => __("Benin"),
        "BM" => __("Bermuda"),
        "BT" => __("Bhutan"),
        "BO" => __("Bolivia"),
        "BA" => __("Bosnia and Herzegowina"),
        "BW" => __("Botswana"),
        "BV" => __("Bouvet Island"),
        "BR" => __("Brazil"),
        "IO" => __("British Indian Ocean Territory"),
        "BN" => __("Brunei Darussalam"),
        "BG" => __("Bulgaria"),
        "BF" => __("Burkina Faso"),
        "BI" => __("Burundi"),
        "KH" => __("Cambodia"),
        "CM" => __("Cameroon"),
        "CA" => __("Canada"),
        "CV" => __("Cape Verde"),
        "KY" => __("Cayman Islands"),
        "CF" => __("Central African Republic"),
        "TD" => __("Chad"),
        "CL" => __("Chile"),
        "CN" => __("China"),
        "CX" => __("Christmas Island"),
        "CC" => __("Cocos (Keeling) Islands"),
        "CO" => __("Colombia"),
        "KM" => __("Comoros"),
        "CG" => __("Congo"),
        "CD" => __("Congo, the Democratic Republic of the"),
        "CK" => __("Cook Islands"),
        "CR" => __("Costa Rica"),
        "CI" => __("Cote d'Ivoire"),
        "CW" => __("Curaçao"),
        "HR" => __("Croatia (Hrvatska)"),
        "CU" => __("Cuba"),
        "CY" => __("Cyprus"),
        "CZ" => __("Czech Republic"),
        "DK" => __("Denmark"),
        "DJ" => __("Djibouti"),
        "DM" => __("Dominica"),
        "DO" => __("Dominican Republic"),
        "TP" => __("East Timor"),
        "EC" => __("Ecuador"),
        "EG" => __("Egypt"),
        "SV" => __("El Salvador"),
        "GQ" => __("Equatorial Guinea"),
        "ER" => __("Eritrea"),
        "EE" => __("Estonia"),
        "ET" => __("Ethiopia"),
        "FK" => __("Falkland Islands (Malvinas)"),
        "FO" => __("Faroe Islands"),
        "FJ" => __("Fiji"),
        "FI" => __("Finland"),
        "FR" => __("France"),
        "FX" => __("France, Metropolitan"),
        "GF" => __("French Guiana"),
        "PF" => __("French Polynesia"),
        "TF" => __("French Southern Territories"),
        "GA" => __("Gabon"),
        "GM" => __("Gambia"),
        "GE" => __("Georgia"),
        "DE" => __("Germany"),
        "GH" => __("Ghana"),
        "GI" => __("Gibraltar"),
        "GR" => __("Greece"),
        "GL" => __("Greenland"),
        "GD" => __("Grenada"),
        "GP" => __("Guadeloupe"),
        "GU" => __("Guam"),
        "GT" => __("Guatemala"),
        "GN" => __("Guinea"),
        "GW" => __("Guinea-Bissau"),
        "GY" => __("Guyana"),
        "HT" => __("Haiti"),
        "HM" => __("Heard and Mc Donald Islands"),
        "VA" => __("Holy See (Vatican City State)"),
        "HN" => __("Honduras"),
        "HK" => __("Hong Kong"),
        "HU" => __("Hungary"),
        "IS" => __("Iceland"),
        "IM" => __("Isle of Man"),
        "IN" => __("India"),
        "ID" => __("Indonesia"),
        "IR" => __("Iran (Islamic Republic of)"),
        "IQ" => __("Iraq"),
        "IE" => __("Ireland"),
        "IL" => __("Israel"),
        "IT" => __("Italy"),
        "JE" => __("Jersey"),
        "JM" => __("Jamaica"),
        "JP" => __("Japan"),
        "JO" => __("Jordan"),
        "KZ" => __("Kazakhstan"),
        "KE" => __("Kenya"),
        "KI" => __("Kiribati"),
        "KP" => __("Korea, Democratic People's Republic of"),
        "KR" => __("Korea, Republic of"),
        "XK" => __("Kosovo"),
        "KW" => __("Kuwait"),
        "KG" => __("Kyrgyzstan"),
        "LA" => __("Lao People's Democratic Republic"),
        "LV" => __("Latvia"),
        "LB" => __("Lebanon"),
        "LS" => __("Lesotho"),
        "LR" => __("Liberia"),
        "LY" => __("Libyan Arab Jamahiriya"),
        "LI" => __("Liechtenstein"),
        "LT" => __("Lithuania"),
        "LU" => __("Luxembourg"),
        "MO" => __("Macau"),
        "MK" => __("Macedonia, The Former Yugoslav Republic of"),
        "MG" => __("Madagascar"),
        "MW" => __("Malawi"),
        "MY" => __("Malaysia"),
        "MV" => __("Maldives"),
        "ML" => __("Mali"),
        "MT" => __("Malta"),
        "MH" => __("Marshall Islands"),
        "MQ" => __("Martinique"),
        "MR" => __("Mauritania"),
        "MU" => __("Mauritius"),
        "YT" => __("Mayotte"),
        "MX" => __("Mexico"),
        "FM" => __("Micronesia, Federated States of"),
        "MD" => __("Moldova, Republic of"),
        "MC" => __("Monaco"),
        "ME" => __("Montenegro"),
        "MN" => __("Mongolia"),
        "MS" => __("Montserrat"),
        "MA" => __("Morocco"),
        "MZ" => __("Mozambique"),
        "MM" => __("Myanmar"),
        "NA" => __("Namibia"),
        "NR" => __("Nauru"),
        "NP" => __("Nepal"),
        "NL" => __("Netherlands"),
        "AN" => __("Netherlands Antilles"),
        "NC" => __("New Caledonia"),
        "NZ" => __("New Zealand"),
        "NI" => __("Nicaragua"),
        "NE" => __("Niger"),
        "NG" => __("Nigeria"),
        "NU" => __("Niue"),
        "NF" => __("Norfolk Island"),
        "MP" => __("Northern Mariana Islands"),
        "NO" => __("Norway"),
        "OM" => __("Oman"),
        "PK" => __("Pakistan"),
        "PW" => __("Palau"),
        "PA" => __("Panama"),
        "PG" => __("Papua New Guinea"),
        "PY" => __("Paraguay"),
        "PE" => __("Peru"),
        "PH" => __("Philippines"),
        "PN" => __("Pitcairn"),
        "PL" => __("Poland"),
        "PT" => __("Portugal"),
        "PR" => __("Puerto Rico"),
        "PS" => __("Palestine"),
        "QA" => __("Qatar"),
        "RE" => __("Reunion"),
        "RO" => __("Romania"),
        "RS" => __("Republic of Serbia"),
        "RU" => __("Russian Federation"),
        "RW" => __("Rwanda"),
        "KN" => __("Saint Kitts and Nevis"),
        "LC" => __("Saint LUCIA"),
        "VC" => __("Saint Vincent and the Grenadines"),
        "WS" => __("Samoa"),
        "SM" => __("San Marino"),
        "ST" => __("Sao Tome and Principe"),
        "SA" => __("Saudi Arabia"),
        "SN" => __("Senegal"),
        "SC" => __("Seychelles"),
        "SL" => __("Sierra Leone"),
        "SG" => __("Singapore"),
        "SK" => __("Slovakia (Slovak Republic)"),
        "SI" => __("Slovenia"),
        "SB" => __("Solomon Islands"),
        "SO" => __("Somalia"),
        "SX" => __("Sint Maarten"),
        "ZA" => __("South Africa"),
        "GS" => __("South Georgia and the South Sandwich Islands"),
        "ES" => __("Spain"),
        "LK" => __("Sri Lanka"),
        "SH" => __("St. Helena"),
        "PM" => __("St. Pierre and Miquelon"),
        "SD" => __("Sudan"),
        "SR" => __("Suriname"),
        "SJ" => __("Svalbard and Jan Mayen Islands"),
        "SZ" => __("Swaziland"),
        "SE" => __("Sweden"),
        "CH" => __("Switzerland"),
        "SY" => __("Syrian Arab Republic"),
        "TW" => __("Taiwan, Province of China"),
        "TJ" => __("Tajikistan"),
        "TZ" => __("Tanzania, United Republic of"),
        "TH" => __("Thailand"),
        "TG" => __("Togo"),
        "TK" => __("Tokelau"),
        "TO" => __("Tonga"),
        "TT" => __("Trinidad and Tobago"),
        "TN" => __("Tunisia"),
        "TR" => __("Turkey"),
        "TM" => __("Turkmenistan"),
        "TC" => __("Turks and Caicos Islands"),
        "TV" => __("Tuvalu"),
        "UG" => __("Uganda"),
        "UA" => __("Ukraine"),
        "AE" => __("United Arab Emirates"),
        "GB" => __("United Kingdom"),
        "US" => __("United States"),
        "UM" => __("United States Minor Outlying Islands"),
        "UY" => __("Uruguay"),
        "UZ" => __("Uzbekistan"),
        "VU" => __("Vanuatu"),
        "VE" => __("Venezuela"),
        "VN" => __("Vietnam"),
        "VG" => __("Virgin Islands (British)"),
        "VI" => __("Virgin Islands (U.S.)"),
        "WF" => __("Wallis and Futuna Islands"),
        "EH" => __("Western Sahara"),
        "YE" => __("Yemen"),
        "YU" => __("Yugoslavia"),
        "ZM" => __("Zambia"),
        "ZW" => __("Zimbabwe"),
    ];

    if ($campaing) {
        $countries = ['all' => __('Worldwide Deal(All Countries)')] + $countries;
    }

    return $countries;
}
