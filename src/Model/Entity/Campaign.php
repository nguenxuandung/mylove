<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * @property int $user_id
 * @property int $id
 * @property bool $default_campaign
 * @property int $ad_type
 * @property int $status
 * @property string $payment_method
 * @property string|null $name
 * @property string $website_title
 * @property string|null $website_url
 * @property string|null $banner_name
 * @property string|null $banner_size
 * @property string|null $banner_code
 * @property float $price
 * @property int $traffic_source
 * @property string $transaction_id
 * @property string $transaction_details
 * @property \Cake\I18n\FrozenTime $started
 * @property \Cake\I18n\FrozenTime $completed
 * @property \Cake\I18n\FrozenTime $modified
 * @property \Cake\I18n\FrozenTime $created
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\CampaignItem[] $campaign_items
 * @property \App\Model\Entity\Statistic[] $statistics
 * @property \App\Model\Entity\Invoice|null $invoice
 */
class Campaign extends Entity
{
}
