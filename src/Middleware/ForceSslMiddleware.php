<?php

namespace App\Middleware;

use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Zend\Diactoros\Response\RedirectResponse;

class ForceSslMiddleware
{
    public function __invoke(ServerRequest $request, Response $response, callable $next)
    {
        if ($this->forceSSL($request)) {
            return new RedirectResponse('https://' . env('HTTP_HOST') . env('REQUEST_URI'), 301);
        }

        return $next($request, $response);
    }

    /**
     * @param \Cake\Http\ServerRequest $request
     * @return bool
     */
    protected function forceSSL($request)
    {
        if ((bool)get_option('ssl_enable', false)) {
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
                if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off") {
                    return true;
                }
            }
        }

        return false;
    }
}
