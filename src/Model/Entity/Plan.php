<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * @property string $title
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property \Cake\I18n\FrozenTime|null $created
 * @property int $id
 * @property bool $enable
 * @property bool $hidden
 * @property string|null $description
 * @property float $monthly_price
 * @property float $yearly_price
 * @property bool $edit_link
 * @property bool $edit_long_url
 * @property bool $multi_domains
 * @property bool $disable_ads
 * @property bool $disable_captcha
 * @property bool $onetime_captcha
 * @property bool $direct
 * @property bool $alias
 * @property bool $referral
 * @property bool $stats
 * @property bool $api_quick
 * @property bool $api_mass
 * @property bool $api_full
 * @property bool $api_developer
 * @property bool $bookmarklet
 * @property \App\Model\Entity\User[] $users
 * @property \Cake\ORM\Entity|null $title_translation
 * @property \Cake\ORM\Entity|null $description_translation
 * @property \Cake\ORM\Entity[] $_i18n
 * @property bool $visitors_remove_captcha
 * @property float $cpm_fixed
 * @property bool $link_expiration
 * @property int $url_daily_limit
 * @property int $url_monthly_limit
 * @property int $referral_percentage
 * @property int $views_hourly_limit
 * @property int $views_daily_limit
 * @property int $views_monthly_limit
 * @property int $timer
 * @property bool $direct_redirect
 * @property bool $banner_redirect
 * @property bool $interstitial_redirect
 * @property bool $random_redirect
 */
class Plan extends Entity
{
}
