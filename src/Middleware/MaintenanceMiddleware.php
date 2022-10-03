<?php

namespace App\Middleware;

use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Cake\View\View;

class MaintenanceMiddleware
{
    public function __invoke(ServerRequest $request, Response $response, callable $next)
    {
        if ((bool)get_option('maintenance_mode', false) === false) {
            return $next($request, $response);
        }

        if ($request->getParam('_name') === 'short') {
            return $next($request, $response);
        }

        if ($request->getSession()->read('Auth.User.role') === 'admin') {
            return $next($request, $response);
        }

        $controller = $request->getParam('controller');
        $action = $request->getParam('action');

        if (in_array($controller, ['Securimage'])) {
            return $next($request, $response);
        }

        if (in_array($action, ['signin', 'multidomainsAuth', 'authDone'])) {
            return $next($request, $response);
        }

        /** @var \Cake\Http\Response $response */
        $response = $response->withStatus(503);
        $response = $response->withType('html');

        $view = new View($request, $response);
        $response = $response->withStringBody($view->element('maintenance'));

        return $response;
    }
}
