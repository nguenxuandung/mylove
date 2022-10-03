<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Http\Middleware\CsrfProtectionMiddleware;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

/*
 * The default class to use for all routes
 *
 * The following route classes are supplied with CakePHP and are appropriate
 * to set as the default:
 *
 * - Route
 * - InflectedRoute
 * - DashedRoute
 *
 * If no call is made to `Router::defaultRouteClass()`, the class used is
 * `Route` (`Cake\Routing\Route\Route`)
 *
 * Note that `Route` does not do any inflections on URLs which will result in
 * inconsistently cased URLs when used with `:plugin`, `:controller` and
 * `:action` markers.
 *
 * Cache: Routes are cached to improve performance, check the RoutingMiddleware
 * constructor in your `src/Application.php` file to change this behavior.
 *
 */
Router::defaultRouteClass(DashedRoute::class);

/*
if (!is_app_installed()) {
    Router::connect('/install/:action', ['controller' => 'Install']);
    if (strpos(env('REQUEST_URI'), 'install') === false) {
        return Router::redirect('/**', ['controller' => 'Install', 'action' => 'index'], ['status' => 307]);
    }
}
*/

Router::scope('/', function (RouteBuilder $routes) {
    // Register scoped middleware for in scopes.
    //$routes->registerMiddleware('csrf', new CsrfProtectionMiddleware([
    //    'httpOnly' => true,
    //]));

    /*
     * Apply a middleware to the current route scope.
     * Requires middleware to be registered through `Application::routes()` with `registerMiddleware()`
     */
    //$routes->applyMiddleware('csrf');

    $routes->connect('/install/:action', ['controller' => 'Install']);
    $routes->redirect('/install', ['controller' => 'Install', 'action' => 'index']);

    /*
     * Here, we are connecting '/' (base path) to a controller called 'Pages',
     * its action called 'display', and we pass a param to select the view file
     * to use (in this case, src/Template/Pages/home.ctp)...
     */
    $routes->connect('/', ['controller' => 'Pages', 'action' => 'home'], ['_name' => 'home']);

    $routes->connect('/st', ['controller' => 'Tools', 'action' => 'st']);

    $routes->connect('/api', ['controller' => 'Tools', 'action' => 'api']);

    $routes->connect('/full', ['controller' => 'Tools', 'action' => 'full']);

    $routes->connect('/bookmarklet', ['controller' => 'Tools', 'action' => 'bookmarklet']);

    $routes->connect('/payment/ipn', ['controller' => 'Invoices', 'action' => 'ipn']);

    $routes->connect('/advertising-rates', ['controller' => 'Pages', 'action' => 'view', 'advertising-rates']);

    $routes->connect('/payout-rates', ['controller' => 'Pages', 'action' => 'view', 'payout-rates']);

    $routes->connect('/contact', ['controller' => 'Pages', 'action' => 'contact']);

    $routes->connect('/pages/*', ['controller' => 'Pages', 'action' => 'view']);

    $routes->connect('/blog', ['controller' => 'Posts', 'action' => 'index']);

    $routes->connect(
        '/blog/:id-:slug',
        ['controller' => 'Posts', 'action' => 'view'],
        ['pass' => ['id', 'slug'], 'id' => '[0-9]+']
    );

    $routes->connect('/ref/*', ['controller' => 'Users', 'action' => 'ref']);

    $routes->connect('/forms/contact', ['controller' => 'Forms', 'action' => 'contact']);

    $routes->connect('/links/shorten', ['controller' => 'Links', 'action' => 'shorten']);
    $routes->connect('/links/go', ['controller' => 'Links', 'action' => 'go']);
    $routes->connect('/links/popad', ['controller' => 'Links', 'action' => 'popad']);

    $routes->connect('/sitemap', ['controller' => 'Sitemap', 'action' => 'index'], ['_name' => 'sitemap']);
    $routes->connect('/sitemap/general', ['controller' => 'Sitemap', 'action' => 'general']);
    $routes->connect('/sitemap/pages', ['controller' => 'Sitemap', 'action' => 'pages']);
    $routes->connect('/sitemap/posts', ['controller' => 'Sitemap', 'action' => 'posts']);
    $routes->connect('/sitemap/links', ['controller' => 'Sitemap', 'action' => 'links']);

    $routes->connect('/securimage/show', ['controller' => 'Securimage', 'action' => 'show']);
    $routes->connect('/securimage/play', ['controller' => 'Securimage', 'action' => 'play']);
    $routes->connect('/securimage/render/*', ['controller' => 'Securimage', 'action' => 'renderCaptcha']);

    $routes->connect('/login-as', ['controller' => 'Users', 'action' => 'loginAsUser'], ['_name' => 'login_as']);

    $routes->connect('/:alias/info', ['controller' => 'Statistics', 'action' => 'viewInfo'], ['pass' => ['alias']]);
    $routes->connect(
        '/:alias',
        ['controller' => 'Links', 'action' => 'view'],
        ['pass' => ['alias'], '_name' => 'short']
    );
});

/*
 * Auth routes
 */
Router::prefix('auth', function (RouteBuilder $routes) {
    // All routes here will be prefixed with ‘/auth‘
    // And have the prefix => auth route element added.
    $routes->connect('/signin', ['controller' => 'Users', 'action' => 'signin'], ['_name' => 'signin']);

    $routes->connect('/signup', ['controller' => 'Users', 'action' => 'signup'], ['_name' => 'signup']);

    $routes->connect('/logout', ['controller' => 'Users', 'action' => 'logout'], ['_name' => 'logout']);

    $routes->connect('/forgot-password', ['controller' => 'Users', 'action' => 'forgotPassword']);

    /*
     * Connect catchall routes for all controllers.
     *
     * Using the argument `DashedRoute`, the `fallbacks` method is a shortcut for
     *
     * ```
     * $routes->connect('/:controller', ['action' => 'index'], ['routeClass' => 'DashedRoute']);
     * $routes->connect('/:controller/:action/*', [], ['routeClass' => 'DashedRoute']);
     * ```
     *
     * Any route class can be used with this method, such as:
     * - DashedRoute
     * - InflectedRoute
     * - Route
     * - Or your own route class
     *
     * You can remove these routes once you've connected the
     * routes you want in your application.
     */
    $routes->fallbacks('DashedRoute');
});

/*
 * Member routes
 */
Router::prefix('member', function (RouteBuilder $routes) {
    // All routes here will be prefixed with ‘/member‘
    // And have the prefix => member route element added.
    $routes->connect(
        '/dashboard',
        ['controller' => 'Users', 'action' => 'dashboard'],
        ['_name' => 'member_dashboard']
    );

    /*
     * Connect catchall routes for all controllers.
     *
     * Using the argument `DashedRoute`, the `fallbacks` method is a shortcut for
     *
     * ```
     * $routes->connect('/:controller', ['action' => 'index'], ['routeClass' => 'DashedRoute']);
     * $routes->connect('/:controller/:action/*', [], ['routeClass' => 'DashedRoute']);
     * ```
     *
     * Any route class can be used with this method, such as:
     * - DashedRoute
     * - InflectedRoute
     * - Route
     * - Or your own route class
     *
     * You can remove these routes once you've connected the
     * routes you want in your application.
     */
    $routes->fallbacks(DashedRoute::class);
});


/*
 * Admin routes
 */
Router::prefix('admin', function (RouteBuilder $routes) {
    // All routes here will be prefixed with ‘/admin‘
    // And have the prefix => admin route element added.
    $routes->connect(
        '/dashboard',
        ['controller' => 'Users', 'action' => 'dashboard'],
        ['_name' => 'admin_dashboard']
    );

    $routes->fallbacks(DashedRoute::class);
});

/*
 * If you need a different set of middleware or none at all,
 * open new scope and define routes there.
 *
 * ```
 * Router::scope('/api', function (RouteBuilder $routes) {
 *     // No $routes->applyMiddleware() here.
 *     // Connect API actions here.
 * });
 * ```
 */
