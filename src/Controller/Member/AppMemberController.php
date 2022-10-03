<?php

namespace App\Controller\Member;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * @property \App\Model\Table\UsersTable $Users
 * @property \Cake\ORM\Table $AppMember
 */
class AppMemberController extends AppController
{
    public $logged_user;

    public $logged_user_plan;

    public $paginate = [
        'limit' => 10,
        'order' => ['id' => 'DESC'],
    ];

    public function isAuthorized($user)
    {
        // Admin can access every action
        if (in_array($user['role'], ['member', 'admin'])) {
            return true;
        }

        // Default deny
        return false;
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $this->viewBuilder()->setLayout('member');

        if ($this->Auth->user('id')) {
            $this->loadModel('Users');
            $user = $this->Users->find()
                ->where(['id' => $this->Auth->user('id')])
                ->first();

            $this->logged_user = $user;
            $this->set('logged_user', $user);

            $user_plan = get_user_plan($this->Auth->user('id'));
            $this->logged_user_plan = $user_plan;
            $this->set('logged_user_plan', $user_plan);
        }
    }
}
