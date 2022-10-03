<?php

namespace App\Controller;

use Cake\Event\Event;
use Cake\Http\Exception\NotFoundException;
use Cake\I18n\Time;

/**
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends FrontController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Cookie');
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['ref', 'loginAsUser']);
    }

    public function ref($username = null)
    {
        $this->autoRender = false;

        if (!$username) {
            return $this->redirect('/');
        }

        $user = $this->Users->find()->where(['username' => $username, 'status' => 1])->first();

        if (!$user) {
            return $this->redirect('/');
        }

        $this->Cookie->configKey('ref', [
            'expires' => '+3 month',
            'httpOnly' => true,
            'encryption' => false,
        ]);
        $this->Cookie->write('ref', $username);

        return $this->redirect('/');
    }

    public function loginAsUser()
    {
        $data = $this->getRequest()->getQuery('data');

        if (!$data) {
            throw new NotFoundException();
        }

        $data = data_decrypt($data);

        if ($data === false) {
            throw new NotFoundException('11');
        }

        if (Time::now()->timestamp - $data['expires'] > 5 * 60) {
            throw new NotFoundException();
        }

        $user = $this->Users->findById($data['id'])->where(['Users.status' => 1])->first();
        if (!$user) {
            throw new NotFoundException();
        }

        $this->Auth->setUser($user->toArray());

        return $this->redirect($this->Auth->redirectUrl());
    }
}
