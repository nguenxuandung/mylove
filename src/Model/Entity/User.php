<?php

namespace App\Model\Entity;

use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Entity;

/**
 * @property int $id
 * @property int $status
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $temp_email
 * @property bool $disable_earnings
 * @property string $role
 * @property int $plan_id
 * @property string $api_token
 * @property string $activation_key
 * @property float $wallet_money
 * @property float $advertiser_balance
 * @property float $publisher_earnings
 * @property float $referral_earnings
 * @property int $referred_by
 * @property string $first_name
 * @property string $last_name
 * @property string $address1
 * @property string $address2
 * @property string $city
 * @property string $state
 * @property string $zip
 * @property string $country
 * @property string $phone_number
 * @property string $withdrawal_method
 * @property string|null $withdrawal_account
 * @property \Cake\I18n\FrozenTime|null $expiration
 * @property string|null $login_ip
 * @property string|null $register_ip
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \App\Model\Entity\Campaign[] $campaigns
 * @property \App\Model\Entity\Link[] $links
 * @property \App\Model\Entity\Statistic[] $statistics
 * @property \App\Model\Entity\Withdraw[] $withdraws
 * @property \App\Model\Entity\Plan $plan
 * @property \App\Model\Entity\Invoice[] $invoices
 * @property \App\Model\Entity\SocialProfile[] $social_profiles
 * @property \App\Model\Entity\RememberToken[] $remember_tokens
 */
class User extends Entity
{
    // Make all fields mass assignable for now.
    protected $_accessible = ['*' => true];

    protected function _setPassword($password)
    {
        if (strlen($password) > 0) {
            return (new DefaultPasswordHasher)->hash($password);
        }
    }
}
