<?php

namespace App\Middleware;

use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Cake\ORM\TableRegistry;

class PasswordChangeLogoutMiddleware
{
    public function __invoke(ServerRequest $request, Response $response, callable $next)
    {
        $session_password = $request->getSession()->read('Auth.User.password');
        $user_id = $request->getSession()->read('Auth.User.id');

        if (!$user_id) {
            return $next($request, $response);
        }

        if (!$session_password) {
            $request->getSession()->destroy();
            return $next($request, $response);
        }

        /** @var \App\Model\Table\UsersTable $usersTable */
        $usersTable = TableRegistry::getTableLocator()->get('Users');

        $database_password = $usersTable->findById($user_id)->first()->password;

        if ($database_password !== $session_password) {
            $request->getSession()->destroy();
        }

        return $next($request, $response);
    }
}
