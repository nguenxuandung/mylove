<?php

namespace App\Middleware;

use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Zend\Diactoros\Response\RedirectResponse;

class InstallMiddleware
{
    public function __invoke(ServerRequest $request, Response $response, callable $next)
    {
        $is_install_url = strpos($_SERVER['REQUEST_URI'], '/install');

        if (is_app_installed() === false) {
            if (!file_exists(ROOT . DS . '.htaccess') &&
                file_exists(ROOT . DS . 'htaccess.txt') &&
                is_writable(ROOT)
            ) {
                copy(ROOT . DS . 'htaccess.txt', ROOT . DS . '.htaccess');
            }

            if ($is_install_url === false) {
                return new RedirectResponse($request->getAttribute("base") . '/install', 307);
            }
        } elseif ($is_install_url !== false) {
            return new RedirectResponse($request->getAttribute("base") . '/');
        }

        return $next($request, $response);
    }
}
