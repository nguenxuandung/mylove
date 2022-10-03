<?php

namespace App\Middleware;

use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Cake\View\View;
use Zend\Diactoros\Response\RedirectResponse;

class RedirectMainDomainMiddleware
{
    public function __invoke(ServerRequest $request, Response $response, callable $next)
    {
        if ($this->redirectMainDomain($request)) {
            $protocol = (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off") ? "http://" : "https://";
            $redirect_url = $protocol . get_option('main_domain') . env('REQUEST_URI');

            if ((bool)get_option('prevent_direct_access_multi_domains')) {
                $http_host = env("HTTP_HOST", "");
                $segments = explode('.', $http_host);

                if (isset($segments[0]) && ($segments[0] === 'www')) {
                    if ($segments[0] . '.' . get_option('main_domain') === $http_host) {
                        return new RedirectResponse($redirect_url, 301);
                    }
                }

                if (env('REQUEST_URI') === $request->getAttribute("base") . '/') {
                    $view = new View($request, $response);
                    $view = $view->setTheme(get_option('theme', 'ClassicTheme'));
                    $response = $response->withStringBody($view->render('/Element/domain', 'blank'));

                    return $response;
                }
            }

            return new RedirectResponse($redirect_url, 301);
        }

        return $next($request, $response);
    }

    /**
     * @param \Cake\Http\ServerRequest $request
     * @return bool
     */
    protected function redirectMainDomain($request)
    {
        $main_domain = get_option('main_domain');

        if (empty($main_domain)) {
            return false;
        }

        $controller = $request->getParam('controller');
        $action = $request->getParam('action');

        if (!(
            (in_array($controller, ['Securimage'])) ||
            (in_array($controller, ['Links']) && in_array($action, ['view', 'go', 'popad'])) ||
            (in_array($controller, ['Tools']) && in_array($action, ['st', 'api', 'full', 'bookmarklet'])) ||
            (in_array($controller, ['Invoices']) && in_array($action, ['ipn'])) ||
            (in_array($controller, ['Users']) && in_array($action, ['multidomainsAuth', 'authDone']))
        )
        ) {
            if (strcasecmp(env("HTTP_HOST", ""), $main_domain) !== 0) {
                return true;
            }
        }

        return false;
    }
}
