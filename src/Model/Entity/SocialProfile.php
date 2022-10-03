<?php

namespace App\Model\Entity;

use ADmad\SocialAuth\Model\Entity\SocialProfile as SocialProfilePlugin;

/**
 * @property int $id
 * @property int $user_id
 * @property string $provider
 * @property string $identifier
 * @property string|null $username
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $full_name
 * @property string|null $email
 * @property string|null $email_verified
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property \App\Model\Entity\User $user
 */
class SocialProfile extends SocialProfilePlugin
{
}
