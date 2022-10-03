<?php

namespace App\Controller;

use Cake\Event\Event;

/**
 * @property \Cake\ORM\Table $Front
 */
class FrontController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
    }
}
