<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * @property int $id
 * @property string|null $selector
 * @property string|null $token
 * @property int|null $user_id
 * @property \Cake\I18n\FrozenTime|null $expires
 * @property \App\Model\Entity\User|null $user
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property \Cake\I18n\FrozenTime|null $created
 */
class RememberToken extends Entity
{
}
