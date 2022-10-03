<?php

namespace App\Middleware;

use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Zend\Diactoros\Response\RedirectResponse;

class RedirectShortLinkMiddleware
{
    public function __invoke(ServerRequest $request, Response $response, callable $next)
    {
        if ($request->getParam('_name') !== 'short') {
            return $next($request, $response);
        }

        $default_domain = get_option('default_short_domain');

        if (!empty($default_domain)) {
            $domains = array_map('mb_strtolower', get_all_multi_domains_list());

            if (!in_array(mb_strtolower($_SERVER['HTTP_HOST']), $domains)) {
                $protocol = (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off") ? "http://" : "https://";

                return new RedirectResponse($protocol . $default_domain . $_SERVER['REQUEST_URI'], 301);
            }
        }

        return $next($request, $response);
    }
}
