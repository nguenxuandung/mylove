<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     3.3.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */

namespace App;

use App\Middleware\ForceSslMiddleware;
use App\Middleware\InstallMiddleware;
use App\Middleware\LanguageMiddleware;
use App\Middleware\MaintenanceMiddleware;
use App\Middleware\PasswordChangeLogoutMiddleware;
use App\Middleware\RedirectMainDomainMiddleware;
use App\Middleware\RedirectShortLinkMiddleware;
use Cake\Core\Configure;
use Cake\Core\Exception\MissingPluginException;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Http\BaseApplication;
use Cake\Http\Middleware\CsrfProtectionMiddleware;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;

/**
 * Application setup class.
 *
 * This defines the bootstrapping logic and middleware layers you
 * want to use in your application.
 */
class Application extends BaseApplication
{
    /**
     * {@inheritDoc}
     */
    public function bootstrap()
    {
        // Call parent to load bootstrap from files.
        parent::bootstrap();

        if (PHP_SAPI === 'cli') {
            $this->bootstrapCli();
        }

        $this->addPlugin('Migrations');

        $this->addPlugin('ADmad/SocialAuth', ['bootstrap' => true, 'routes' => true]);

        $this->addPlugin('Queue', ['bootstrap' => true]);

        $this->addPlugin(get_option('theme', 'ClassicTheme'));
        $this->addPlugin(get_option('member_theme', 'AdminlteMemberTheme'));
        $this->addPlugin(get_option('admin_theme', 'AdminlteAdminTheme'));

        /*
         * Only try to load DebugKit in development mode
         * Debug Kit should not be installed on a production system
         */
        if (Configure::read('debug')) {
            $this->addPlugin('DebugKit');
        }

        // Load more plugins here
    }

    /**
     * Setup the middleware queue your application will use.
     *
     * @param \Cake\Http\MiddlewareQueue $middlewareQueue The middleware queue to setup.
     * @return \Cake\Http\MiddlewareQueue The updated middleware queue.
     */
    public function middleware($middlewareQueue)
    {
        $middlewareQueue
            ->add(new InstallMiddleware())
            // Catch any exceptions in the lower layers,
            // and make an error page/response
            ->add(new ErrorHandlerMiddleware(null, Configure::read('Error')))
            // Handle plugin/theme assets like CakePHP normally does.
            ->add(new AssetMiddleware([
                'cacheTime' => Configure::read('Asset.cacheTime'),
            ]))

            // Add routing middleware.
            // If you have a large number of routes connected, turning on routes
            // caching in production could improve performance. For that when
            // creating the middleware instance specify the cache config name by
            // using it's second constructor argument:
            // `new RoutingMiddleware($this, '_cake_routes_')`
            ->add(new RoutingMiddleware($this))
            ->add(new RedirectShortLinkMiddleware())
            ->add(new MaintenanceMiddleware())
            ->add(new RedirectMainDomainMiddleware())
            ->add(new ForceSslMiddleware())
            ->add(new LanguageMiddleware())
            ->add(new PasswordChangeLogoutMiddleware())
            // Add csrf middleware.
            // https://stackoverflow.com/a/47718018/1794834
            ->add(function (
                \Cake\Http\ServerRequest $request,
                \Cake\Http\Response $response,
                callable $next
            ) {
                $controller = $request->getParam('controller');
                $action = $request->getParam('action');

                $csrfProtection = true;

                if ($controller === 'Install') {
                    $csrfProtection = false;
                }

                if (($controller === 'Invoices') && ($action === 'ipn')) {
                    $csrfProtection = false;
                }

                if (($controller === 'Users') && ($action === 'multidomainsAuth')) {
                    $csrfProtection = false;
                }

                if ($csrfProtection) {
                    $csrf = new CsrfProtectionMiddleware([
                        'httpOnly' => true,
                    ]);

                    // This will invoke the CSRF middleware's `__invoke()` handler,
                    // just like it would when being registered via `add()`.
                    return $csrf($request, $response, $next);
                }

                return $next($request, $response);
            });

        $middlewareQueue->add(new \ADmad\SocialAuth\Middleware\SocialAuthMiddleware([
            // Request method type use to initiate authentication.
            'requestMethod' => 'POST',
            // Login page URL. In case of auth failure user is redirected to login
            // page with "error" query string var.
            'loginUrl' => '/auth/signin',
            // URL to redirect to after authentication (string or array).
            'loginRedirect' => '/member/dashboard',
            // Boolean indicating whether user identity should be returned as entity.
            'userEntity' => false,
            // User model.
            'userModel' => 'Users',
            // Social profile model. Default "ADmad/SocialAuth.SocialProfiles".
            'profileModel' => 'SocialProfiles',
            // Finder type.
            'finder' => 'all',
            // Fields.
            'fields' => [
                'password' => 'password',
            ],
            // Session key to which to write identity record to.
            'sessionKey' => 'Auth.User',
            // The method in user model which should be called in case of new user.
            // It should return a User entity.
            'getUserCallback' => 'getUser',
            // SocialConnect Auth service's providers config.
            // https://github.com/SocialConnect/auth/blob/master/README.md
            'serviceConfig' => [
                'provider' => [
                    'facebook' => [
                        'enabled' => (bool)get_option('social_login_facebook', false),
                        'applicationId' => get_option('social_login_facebook_app_id'),
                        'applicationSecret' => get_option('social_login_facebook_app_secret'),
                        'scope' => ['email', 'public_profile'],
                        // To get a full list of all posible values, refer to
                        // https://developers.facebook.com/docs/graph-api/reference/user
                        'fields' => ['email', 'first_name', 'last_name', 'name'],
                    ],
                    'twitter' => [
                        'enabled' => (bool)get_option('social_login_twitter', false),
                        'applicationId' => get_option('social_login_twitter_api_key'),
                        'applicationSecret' => get_option('social_login_twitter_api_secret'),
                    ],
                    'google' => [
                        'enabled' => (bool)get_option('social_login_google', false),
                        'applicationId' => get_option('social_login_google_client_id'),
                        'applicationSecret' => get_option('social_login_google_client_secret'),
                        'scope' => ['email', 'profile'],
                    ],
                ],
            ],
            // If you want to use CURL instead of CakePHP's Http Client set this to
            // '\SocialConnect\Common\Http\Client\Curl' or another client instance that
            // SocialConnect/Auth's Service class accepts.
            'httpClient' => '\SocialConnect\Common\Http\Client\Curl',
            // Whether social connect errors should be logged. Default `true`.
            'logErrors' => Configure::read('debug'),
        ]));

        return $middlewareQueue;
    }

    /**
     * @return void
     */
    protected function bootstrapCli()
    {
        try {
            $this->addPlugin('Bake');

            $this->addPlugin('IdeHelper');
        } catch (MissingPluginException $e) {
            // Do not halt if the plugin is missing
        }

        // Load more plugins here
    }
}
