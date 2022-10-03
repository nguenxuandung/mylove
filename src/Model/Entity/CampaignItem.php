<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * @property int $campaign_id
 * @property int $id
 * @property string $country
 * @property float $advertiser_price
 * @property float $publisher_price
 * @property int $purchase
 * @property int $views
 * @property float $weight
 * @property \App\Model\Entity\Campaign $campaign
 */
class CampaignItem extends Entity
{
}
