<?php

namespace App\Controller\Auth;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Mailer\MailerAwareTrait;
use Cake\I18n\Time;

/**
 * @property \App\Model\Table\UsersTable $Users
 * @property \App\Controller\Component\CaptchaComponent $Captcha
 */
class UsersController extends AppController
{
    use MailerAwareTrait;

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Captcha');
        $this->loadComponent('Cookie');

        if (in_array($this->getRequest()->getParam('action'), ['multidomainsAuth', 'authDone'])) {
            //$this->getEventManager()->off($this->Csrf);
            $this->getEventManager()->off($this->Security);
        }
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['multidomainsAuth', 'authDone', 'signup', 'logout', 'activateAccount', 'forgotPassword']);
        $this->viewBuilder()->setLayout('auth');
    }

    public function signin()
    {
        if ($this->Auth->user('id')) {
            return $this->redirect('/');
        }

        $user = $this->Users->newEntity();
        $this->set('user', $user);

        if ($this->getRequest()->is('post')) {
            if ((get_option('enable_captcha_signin', 'no') == 'yes') &&
                isset_captcha() &&
                !$this->Captcha->verify($this->getRequest()->getData())
            ) {
                $this->Flash->error(__('The CAPTCHA was incorrect. Try again'));

                return null;
            }
        }

        if ($this->getRequest()->is('post') || $this->getRequest()->getQuery('provider')) {
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);

                $multi_domains = get_all_domains_list();
                unset($multi_domains[$_SERVER['HTTP_HOST']]);
                if (count($multi_domains)) {
                    $_SESSION['Auth']['AppAuth']['Domains'] = $multi_domains;
                    $_SESSION['Auth']['AppAuth']['DomainsData'] = urlencode(data_encrypt([
                        'session_name' => session_name(),
                        'session_id' => session_id(),
                        'time' => time(),
                    ]));
                }

                $this->_setUserCookie($user);

                $this->Cookie->delete('ref');

                return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Flash->error(__('Invalid username or password, try again'));
        }
    }

    /**
     * @param array $user
     * @return bool
     * @throws \Exception
     */
    protected function _setUserCookie($user)
    {
        if (!$this->request->getData('remember_me')) {
            return false;
        }

        $selector = base64_encode(random_bytes(9));
        $authenticator = random_bytes(33);
        $expire = Time::now()->addYear();

        setcookie(
            'RememberMe',
            $selector . ':' . base64_encode($authenticator),
            $expire->timestamp,
            '/',
            '',
            false, // TLS-only
            true // http-only
        );

        $rememberToken = $this->Users->RememberTokens->newEntity();
        $rememberToken->selector = $selector;
        $rememberToken->token = hash('sha256', $authenticator);
        $rememberToken->user_id = $user['id'];
        $rememberToken->expires = $expire->toDateTimeString();
        $this->Users->RememberTokens->save($rememberToken);

        $_SESSION['Auth']['AppAuth']['Cookie'] = urlencode(data_encrypt([
            'name' => 'RememberMe',
            'value' => $selector . ':' . base64_encode($authenticator),
            'expire' => $expire->timestamp,
        ]));

        return true;
    }


    public function multidomainsAuth()
    {
        $this->autoRender = false;

        $response = $this->getResponse();
        $response = $response->withType('gif');
        $response = $response->withStringBody(base64_decode('R0lGODlhAQABAIAAAP///wAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw=='));

        $this->setResponse($response);

        if (!$this->getRequest()->is('get')) {
            return $this->getResponse();
        }

        try {
            if ($this->getRequest()->getQuery('auth')) {
                $auth = data_decrypt($this->getRequest()->getQuery('auth'));

                if ((time() - $auth['time']) > 60) {
                    return $this->getResponse();
                }

                session_write_close();

                session_name($auth['session_name']);
                session_id($auth['session_id']);

                session_start();

                if ($this->getRequest()->getQuery('cookie')) {
                    $cookie = data_decrypt($this->getRequest()->getQuery('cookie'));

                    if ($cookie) {
                        setcookie(
                            $cookie['name'],
                            $cookie['value'],
                            $cookie['expire'],
                            '/',
                            '',
                            false, // TLS-only
                            true // http-only
                        );
                    }
                }
            }
        } catch (\Exception $ex) {
        }

        return $this->getResponse();
    }

    public function authDone()
    {
        $this->autoRender = false;

        $response = $this->getResponse();
        $response = $response->withType('gif');
        $response = $response->withStringBody(base64_decode('R0lGODlhAQABAIAAAP///wAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw=='));

        $this->setResponse($response);

        if (!$this->getRequest()->is('get')) {
            return $this->getResponse();
        }

        try {
            $this->getRequest()->getSession()->delete('Auth.AppAuth');
            //unset($_SESSION['Auth']['AppAuth']);
        } catch (\Exception $ex) {
        }

        return $this->getResponse();
    }

    public function signup()
    {
        if ($this->Auth->user('id')) {
            return $this->redirect('/');
        }

        if ((bool)get_option('close_registration', false)) {
            return $this->redirect('/');
        }

        $user = $this->Users->newEntity();

        $this->set('user', $user);

        if ($this->getRequest()->is('post')) {
            if ((get_option('enable_captcha_signup') == 'yes') &&
                isset_captcha() &&
                !$this->Captcha->verify($this->getRequest()->getData())
            ) {
                $this->Flash->error(__('The CAPTCHA was incorrect. Try again'));

                return null;
            }

            $user = $this->Users->patchEntity($user, $this->getRequest()->getData());

            $referred_by_id = 0;
            if (isset($_COOKIE['ref']) && !empty($_COOKIE['ref'])) {
                $user_referred_by = $this->Users->find()
                    ->where([
                        'username' => $_COOKIE['ref'],
                        'status' => 1,
                    ])
                    ->first();

                if ($user_referred_by) {
                    $referred_by_id = $user_referred_by->id;
                }
            }
            $user->referred_by = $referred_by_id;

            $user->api_token = \Cake\Utility\Security::hash(\Cake\Utility\Text::uuid(), 'sha1', true);
            $user->activation_key = \Cake\Utility\Security::hash(\Cake\Utility\Text::uuid(), 'sha1', true);

            $user->plan_id = 1;
            $user->role = 'member';
            $user->status = 1;
            $user->register_ip = get_ip();

            $user->publisher_earnings = price_database_format(get_option('signup_bonus', 0));

            $trial_plan = (int)get_option('trial_plan', '');
            if ($trial_plan > 1) {
                $plan_expiration = Time::now();

                if (get_option('trial_plan_period', 'm') === 'm') {
                    $expiration = $plan_expiration->addMonth();
                } else {
                    $expiration = $plan_expiration->addYear();
                }
                $user->plan_id = $trial_plan;
                $user->expiration = $expiration;
            }

            if (get_option('account_activate_email', 'yes') == 'yes') {
                $user->status = 2;
            }

            if ($this->Users->save($user)) {
                if ((bool)get_option('alert_admin_new_user_register', 0)) {
                    try {
                        $this->getMailer('Notification')->send('newRegistration', [$user]);
                    } catch (\Exception $exception) {
                        \Cake\Log\Log::write('error', $exception->getMessage());
                    }
                }

                if (get_option('account_activate_email', 'yes') == 'yes') {
                    // Send activation email
                    try {
                        $this->getMailer('User')->send('activation', [$user]);
                    } catch (\Exception $exception) {
                        \Cake\Log\Log::write('error', $exception->getMessage());
                    }

                    $this->Flash->success(__('Your account has been created. Please check your email inbox ' .
                        'or spam folder to activate your account.'));

                    return $this->redirect(['action' => 'signin']);
                }

                $this->Flash->success(__('Your account has been created.'));

                return $this->redirect(['action' => 'signin']);
            }
            $this->Flash->error(__('Unable to add the user.'));
        }
        $this->set('user', $user);
    }

    public function logout()
    {
        if (isset($_COOKIE['RememberMe']) && strpos($_COOKIE['RememberMe'], ":") !== false) {
            list($selector, $authenticator) = explode(':', $_COOKIE['RememberMe']);

            $rememberToken = $this->Users->RememberTokens->find()
                ->where([
                    'selector' => $selector,
                ])
                ->limit(1)
                ->first();

            if ($rememberToken) {
                $this->Users->RememberTokens->delete($rememberToken);
            }

            unset($_COOKIE['RememberMe']);
            setcookie('RememberMe', null, -1, '/');
        }

        return $this->redirect($this->Auth->logout());
    }

    public function activateAccount($username = null, $key = null)
    {
        if (!$username && !$key) {
            $this->Flash->error(__('Invalid Activation.'));

            return $this->redirect(['action' => 'signin']);
        }
        $user = $this->Users->find()
            ->contain(['Plans'])
            ->where([
                'Users.status' => 2,
                'Users.username' => $username,
                'Users.activation_key' => $key,
            ])
            ->first();

        if (!$user) {
            $this->Flash->error(__('Invalid Activation.'));

            return $this->redirect(['action' => 'signin']);
        }

        $user->status = 1;
        $user->activation_key = '';

        if ($this->Users->save($user)) {
            $this->Flash->success(__('Your account has been activated.'));

            return $this->redirect(['action' => 'signin']);
        } else {
            $this->Flash->error(__('Unable to activate your account.'));

            return $this->redirect(['action' => 'signin', 'prefix' => 'auth']);
        }
    }

    public function forgotPassword($username = null, $key = null)
    {
        if ($this->Auth->user('id')) {
            return $this->redirect('/');
        }

        if (!$username && !$key) {
            $user = $this->Users->newEntity();
            $this->set('user', $user);

            if ($this->getRequest()->is(['post', 'put'])) {
                if ((get_option('enable_captcha_forgot_password') == 'yes') &&
                    isset_captcha() &&
                    !$this->Captcha->verify($this->getRequest()->getData())
                ) {
                    $this->Flash->error(__('The CAPTCHA was incorrect. Try again'));

                    return null;
                }

                $user = $this->Users->findByEmail($this->getRequest()->getData('email'))->first();

                if (!$user) {
                    $this->Flash->error(__('Invalid User.'));

                    return $this->redirect(['action' => 'forgotPassword', 'prefix' => 'auth']);
                }

                $user->activation_key = \Cake\Utility\Security::hash(\Cake\Utility\Text::uuid(), 'sha1', true);

                $user = $this->Users->patchEntity($user, $this->getRequest()->getData(),
                    ['validate' => 'forgotPassword']);

                if ($this->Users->save($user)) {
                    // Send reset email
                    try {
                        $this->getMailer('User')->send('forgotPassword', [$user]);
                    } catch (\Exception $exception) {
                        \Cake\Log\Log::write('error', $exception->getMessage());
                    }

                    $this->Flash->success(__('Kindly check your email for reset password link.'));

                    return $this->redirect(['action' => 'forgotPassword', 'prefix' => 'auth']);
                } else {
                    $this->Flash->error(__('Unable to reset password.'));

                    return $this->redirect(['action' => 'forgotPassword', 'prefix' => 'auth']);
                }
            }
        } else {
            $user = $this->Users->find('all')
                ->where([
                    'status' => 1,
                    'username' => $username,
                    'activation_key' => $key,
                ])
                ->first();
            if (!$user) {
                $this->Flash->error(__('Invalid Request.'));

                return $this->redirect(['action' => 'forgotPassword', 'prefix' => 'auth']);
            }

            if ($this->getRequest()->is(['post', 'put'])) {
                $user->activation_key = '';

                $user = $this->Users->patchEntity($user, $this->getRequest()->getData(),
                    ['validate' => 'forgotPassword']);

                if ($this->Users->save($user)) {
                    $this->Users->RememberTokens->deleteAll(['user_id' => $user->id]);

                    $this->Flash->success(__('Your password has been changed.'));

                    return $this->redirect(['action' => 'signin', 'prefix' => 'auth']);
                } else {
                    $this->Flash->error(__('Unable to change your password.'));
                }
            }

            unset($user->password);

            $this->set('user', $user);
        }
    }
}
