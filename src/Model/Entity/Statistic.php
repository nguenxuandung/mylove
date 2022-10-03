<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * @property int $link_id
 * @property int $user_id
 * @property int $referral_id
 * @property int $ad_type
 * @property int $campaign_id
 * @property int $campaign_user_id
 * @property int $campaign_item_id
 * @property string $ip
 * @property string $country
 * @property float $owner_earn
 * @property float $publisher_earn
 * @property float $referral_earn
 * @property string $referer_domain
 * @property string $referer
 * @property string|null $user_agent
 * @property int $reason
 * @property \Cake\I18n\FrozenTime $created
 * @property int $id
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Link $link
 * @property \App\Model\Entity\Campaign $campaign
 */
class Statistic extends Entity
{
}
