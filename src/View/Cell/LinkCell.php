<?php

namespace App\View\Cell;

use Cake\View\Cell;

class LinkCell extends Cell
{
    public function shortenMember()
    {
        $this->set('plan', get_user_plan($this->request->getSession()->read('Auth.User.id')));
    }
}
