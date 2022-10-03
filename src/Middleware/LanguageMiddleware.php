<?php

namespace App\Middleware;

use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Cake\I18n\I18n;
use Zend\Diactoros\Response\RedirectResponse;

class LanguageMiddleware
{
    public function __invoke(ServerRequest $request, Response $response, callable $next)
    {

        if ($request->getParam('prefix') === 'admin') {
            return $next($request, $response);
        }

        if ($this->setLanguage($request) && isset($_COOKIE['lang'])) {
            $protocol = (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off") ? "http://" : "https://";
            $request_url = str_replace('lang=' . $_COOKIE['lang'], '', env('REQUEST_URI'));

            return new RedirectResponse($protocol . env('HTTP_HOST') . $request_url, 307);
        }

        if (isset($_COOKIE['lang']) && in_array($_COOKIE['lang'], get_site_languages(true))) {
            I18n::setLocale($_COOKIE['lang']);
        }

        return $next($request, $response);
    }

    /**
     * @param \Cake\Http\ServerRequest $request
     * @return bool
     */
    protected function setLanguage($request)
    {
        if (empty(get_option('site_languages'))) {
            return false;
        }

        $controller = $request->getParam('controller');
        $action = $request->getParam('action');

        if (
            (in_array($controller, ['Securimage'])) ||
            (in_array($controller, ['Links']) && in_array($action, ['go', 'popad'])) ||
            (in_array($controller, ['Tools']) && in_array($action, ['st', 'api', 'full', 'bookmarklet'])) ||
            (in_array($controller, ['Invoices']) && in_array($action, ['ipn'])) ||
            (in_array($controller, ['Users']) && in_array($action, ['multidomainsAuth', 'authDone']))
        ) {
            return false;
        }

        if (in_array($request->getQuery('lang'), get_site_languages(true))) {
            if (isset($_COOKIE['lang']) && $_COOKIE['lang'] === $request->getQuery('lang')) {
                return false;
            }
            setcookie('lang', $request->getQuery('lang'), time() + (86400 * 30 * 12), '/');

            return true;
        }

        if ((bool)get_option('language_auto_redirect', false)) {
            if (!isset($_COOKIE['lang']) && isset($request->acceptLanguage()[0])) {
                $lang = substr($request->acceptLanguage()[0], 0, 2);
                $lang = preg_quote($lang, '/');

                $langs = get_site_languages(true);

                $valid_langs = [];
                foreach ($langs as $key => $value) {
                    if (preg_match('/^' . $lang . '/', $value)) {
                        $valid_langs[] = $value;
                    }
                }

                if (isset($valid_langs[0])) {
                    setcookie('lang', $valid_langs[0], time() + (86400 * 30 * 12), '/');

                    return true;
                }
            }
        }

        return false;
    }
}
