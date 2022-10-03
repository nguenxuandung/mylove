<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * @property int $user_id
 * @property int $ad_type
 * @property int $status
 * @property string|null $url
 * @property string $domain
 * @property string $alias
 * @property string $title
 * @property string description
 * @property string|null $image
 * @property int $hits
 * @property int $method
 * @property \Cake\I18n\FrozenTime $modified
 * @property \Cake\I18n\FrozenTime $created
 * @property int $id
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Statistic[] $statistics
 * @property string|null $url_hash
 * @property \Cake\I18n\FrozenTime|null $expiration
 * @property \Cake\I18n\FrozenTime|null $last_activity
 */
class Link extends Entity
{
}
