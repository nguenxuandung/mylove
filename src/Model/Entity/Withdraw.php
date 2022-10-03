<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * @property int $id
 * @property int $user_id
 * @property int $status
 * @property float $publisher_earnings
 * @property float $referral_earnings
 * @property float $amount
 * @property string|null $json_data
 * @property string $method
 * @property string|null $account
 * @property string $transaction_id
 * @property \Cake\I18n\FrozenTime $created
 * @property \App\Model\Entity\User $user
 * @property string|null $user_note
 */
class Withdraw extends Entity
{
}
