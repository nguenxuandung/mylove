<?php

namespace App\Controller;

use Cake\Event\Event;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;

/**
 * @property \App\Model\Table\LinksTable $Links
 * @property \App\Controller\Component\CaptchaComponent $Captcha
 */
class LinksController extends FrontController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Cookie');
        $this->loadComponent('Captcha');
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->viewBuilder()->setLayout('front');
        $this->Auth->allow(['shorten', 'view', 'go', 'popad']);
    }

    public function view($alias = null)
    {
        $this->setResponse(
            $this->getResponse()
                ->withHeader('X-Frame-Options', 'SAMEORIGIN')
                ->withHeader('X-Robots-Tag', 'noindex, nofollow')
        );

        if (!$alias) {
            throw new NotFoundException(__('404 Not Found'));
        }

        /**
         * @var \App\Model\Entity\Link $link
         */
        $link = $this->Links->find()
            //->contain(['Users'])
            ->contain([
                'Users' => [
                    'fields' => ['id', 'username', 'status', 'disable_earnings'],
                ],
            ])
            ->where([
                'Links.alias' => $alias,
                'Links.status <>' => 3,
                'Users.status' => 1,
            ])
            ->first();

        if (!$link) {
            throw new NotFoundException(__('404 Not Found'));
        }
        $this->set('link', $link);

        if ((bool)get_option('maintenance_mode', false)) {
            return $this->redirect($link->url, 307);
        }

        $link_user_plan = get_user_plan($link->user_id);
        $this->set('link_user_plan', $link_user_plan);

        if ($link_user_plan->link_expiration && !empty($link->expiration) && $link->expiration->isPast()) {
            throw new ForbiddenException(__('The link has been expired'));
        }

        $detector = new \Detection\MobileDetect();
        if ((bool)$detector->is("Bot")) {
            if ((bool)validCrawler()) {
                return $this->redirect($link->url, 301);
            }
        }

        $plan_disable_ads = $plan_disable_captcha = $plan_onetime_captcha = $plan_direct = false;
        if ($this->Auth->user()) {
            $auth_user_plan = get_user_plan($this->Auth->user('id'));

            if ($auth_user_plan->disable_ads) {
                $plan_disable_ads = true;
            }
            if ($auth_user_plan->disable_captcha) {
                $plan_disable_captcha = true;
            }
            if ($auth_user_plan->onetime_captcha) {
                $plan_onetime_captcha = true;
            }
            if ($auth_user_plan->direct) {
                $plan_direct = true;
            }
        }

        if ($link_user_plan->visitors_remove_captcha) {
            $plan_disable_captcha = true;
        }

        $this->set('plan_disable_ads', $plan_disable_ads);

        $ad_type = $link->ad_type;
        if (!array_key_exists($ad_type, get_allowed_ads())) {
            $ad_type = get_option('member_default_advert', 1);
        }
        if ($link->user_id == 1) {
            $ad_type = get_option('anonymous_default_advert', 1);
        }

        if ($ad_type == 3) {
            $types = [1, 2];
            $ad_type = $types[array_rand($types, 1)];
        }

        $this->set('ad_type', $ad_type);

        $this->setRefererCookie($link->alias);

        // No Ads
        if ($plan_direct || $ad_type == 0) {
            $this->updateLinkHits($link);
            $this->addNormalStatisticEntry($link, $ad_type, [
                'ci' => 0,
                'cui' => 0,
                'cii' => 0,
            ], get_ip(), 10);

            return $this->redirect($link->url, 301);
        }

        $ad_captcha_above = get_option('ad_captcha_above', '');
        $ad_captcha_below = get_option('ad_captcha_below', '');
        if ($plan_disable_ads) {
            $ad_captcha_above = '';
            $ad_captcha_below = '';
        }

        $this->set('ad_captcha_above', $ad_captcha_above);
        $this->set('ad_captcha_below', $ad_captcha_below);

        $display_blog_post_shortlink = get_option('display_blog_post_shortlink', 'none');
        $post = '';

        if (in_array($display_blog_post_shortlink, ['latest', 'random'])) {
            $order = ['RAND()'];
            if ('latest' === $display_blog_post_shortlink) {
                $order = ['Posts.id' => 'DESC'];
            }

            $posts = TableRegistry::getTableLocator()->get('Posts');
            $post = $posts->find()
                ->where(['Posts.published' => 1])
                ->order($order)
                ->first();
        }
        $this->set('post', $post);

        $displayCaptchaShortlink = $this->displayCaptchaShortlink($plan_disable_captcha, $plan_onetime_captcha);
        $this->set('displayCaptchaShortlink', $displayCaptchaShortlink);

        if ($this->getRequest()->getData('action') !== 'captcha') {
            $pagesNumber = (int)\get_option('continue_pages_number', 0);
            if ($pagesNumber > 0) {
                $page = (int)$this->getRequest()->getData('page', 1);

                if ($page <= $pagesNumber) {
                    $this->set('page', $page);
                    $this->viewBuilder()->setLayout('captcha');
                    return $this->render('page');
                }
            }
        }

        $this->viewBuilder()->setLayout('captcha');
        $this->render('captcha');

        if (
            !$displayCaptchaShortlink ||
            ($this->getRequest()->is('post') && $this->getRequest()->getData('action') !== 'continue')
        ) {
            if ($displayCaptchaShortlink && !$this->Captcha->verify($this->getRequest()->getData())) {
                $this->Flash->error(__('The CAPTCHA was incorrect. Try again'));

                return $this->redirect($this->getRequest()->getRequestTarget());
            }

            $this->setVisitorCookie();

            $country = $this->Links->Statistics->get_country(get_ip());
            $this->set('country', $country);

            if ($detector->isMobile()) {
                $traffic_source = 3; // Mobile & Tablet
            } else {
                $traffic_source = 2; // Desktop
            }

            $paidAds = (object)$this->getPaidAds($ad_type, $traffic_source, $country);
            $this->set('paidAds', $paidAds);

            if (get_option('enable_popup', 'yes') == 'yes') {
                $popupPaidAds = (object)$this->getPaidAds(3, $traffic_source, $country);
                $show_pop_ad = false;
                $pop_ad = [];
                if ($popupPaidAds) {
                    $pop_ad = [
                        'mode' => $popupPaidAds->mode,
                        'link' => $link,
                        'website_url' => $popupPaidAds->website_url,
                        'alias' => $link->alias,
                        'ci' => $popupPaidAds->ci,
                        'cui' => $popupPaidAds->cui,
                        'cii' => $popupPaidAds->cii,
                        'country' => $country,
                        'advertiser_price' => $popupPaidAds->advertiser_price,
                        'publisher_price' => $popupPaidAds->publisher_price,
                    ];
                    $show_pop_ad = true;
                }
                $this->set('show_pop_ad', $show_pop_ad);
                $this->set('pop_ad', data_encrypt($pop_ad));
            }

            $ad_form_data = [
                'mode' => $paidAds->mode,
                'alias' => $link->alias,
                'ci' => $paidAds->ci,
                'cui' => $paidAds->cui,
                'cii' => $paidAds->cii,
                'country' => $country,
                'advertiser_price' => $paidAds->advertiser_price,
                'publisher_price' => $paidAds->publisher_price,
                'ad_type' => $ad_type,
                'timer' => $link_user_plan->timer ?? 5,
                't' => time(),
            ];

            $this->set('ad_form_data', data_encrypt($ad_form_data));

            // Interstitial Ads
            if ($ad_type == 1) {
                $interstitial_banner_ad = get_option('interstitial_banner_ad', '');
                $interstitial_ad_url = $paidAds->website_url;
                if ($plan_disable_ads) {
                    $interstitial_banner_ad = '';
                    $interstitial_ad_url = '';
                }
                $this->set('interstitial_banner_ad', $interstitial_banner_ad);
                $this->set('interstitial_ad_url', $interstitial_ad_url);

                $this->viewBuilder()->setLayout('go_interstitial');
                $this->render('view_interstitial');
            }

            // Banner Ads
            if ($ad_type == 2) {
                $banner_728x90 = get_option('banner_728x90', '');
                $banner_468x60 = get_option('banner_468x60', '');
                $banner_336x280 = get_option('banner_336x280', '');

                if ($paidAds->mode === 'campaign') {
                    if ('728x90' == $paidAds->banner_size) {
                        $banner_728x90 = $paidAds->banner_code;
                    }

                    if ('468x60' == $paidAds->banner_size) {
                        $banner_468x60 = $paidAds->banner_code;
                    }

                    if ('336x280' == $paidAds->banner_size) {
                        $banner_336x280 = $paidAds->banner_code;
                    }
                }

                if ($plan_disable_ads) {
                    $banner_728x90 = '';
                    $banner_468x60 = '';
                    $banner_336x280 = '';
                }

                $this->set('banner_728x90', $banner_728x90);
                $this->set('banner_468x60', $banner_468x60);
                $this->set('banner_336x280', $banner_336x280);

                $this->viewBuilder()->setLayout('go_banner');
                $this->render('view_banner');
            }
        }
    }

    public function popad()
    {
        $this->autoRender = false;

        if ($this->getRequest()->is('post')) {
            $pop_ad_data = data_decrypt($this->getRequest()->getData('pop_ad'));

            $this->calcEarnings($pop_ad_data, $pop_ad_data['link'], 3);

            return $this->redirect($pop_ad_data['website_url'], 301);
        }
    }

    public function go()
    {
        $this->autoRender = false;
        $this->setResponse($this->getResponse()->withType('json'));

        $ad_form_data = data_decrypt($this->getRequest()->getData('ad_form_data'));

        $t = (int)$ad_form_data['t'];
        $diff_seconds = (int)(time() - $t);
        $counter_value = (int)$ad_form_data['timer'];

        if ($diff_seconds < $counter_value) {
            $content = [
                'status' => 'error',
                'message' => 'Bad Request.',
                'url' => '',
            ];
            $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

            return $this->getResponse();
        }

        if (!$this->getRequest()->is('ajax')) {
            $content = [
                'status' => 'error',
                'message' => 'Bad Request.',
                'url' => '',
            ];
            $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

            return $this->getResponse();
        }

        /**
         * @var \App\Model\Entity\Link $link
         */
        $link = $this->Links->find()
            //->contain(['Users'])
            ->contain([
                'Users' => [
                    'fields' => ['id', 'username', 'status', 'disable_earnings'],
                ],
            ])
            ->where([
                'Links.alias' => $ad_form_data['alias'],
                'Links.status <>' => 3,
            ])
            ->first();
        if (!$link) {
            $content = [
                'status' => 'error',
                'message' => '404 Not Found.',
                'url' => '',
            ];
            $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

            return $this->getResponse();
        }

        $content = $this->calcEarnings($ad_form_data, $link, $ad_form_data['ad_type']);

        //$content['url'] = $content['url'];

        $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

        return $this->getResponse();
    }

    protected function setRefererCookie($alias)
    {
        if (isset($_COOKIE['ref' . $alias])) {
            return;
        }

        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

        \setcookie('ref' . $alias, \data_encrypt($referer), \time() + 5 * 60, '/', '', false, true);
    }

    protected function getRefererCookie($alias)
    {
        $referer_url = '';
        if (isset($_COOKIE['ref' . $alias])) {
            $referer_url = \data_decrypt($_COOKIE['ref' . $alias]);
        }

        return $referer_url;
    }

    protected function getPaidAds($ad_type, $traffic_source, $country)
    {
        $paidAds = new \stdClass();

        if (get_option('earning_mode', 'campaign') === 'simple') {
            $prices = [];
            if ($ad_type === 1) {
                $prices = get_option('payout_rates_interstitial', []);
                $paidAds->website_url = get_option('interstitial_ad_url', '');
            }

            if ($ad_type === 2) {
                $prices = get_option('payout_rates_banner', []);
                $paidAds->banner_size = '';
                $paidAds->banner_code = '';
            }

            if ($ad_type === 3) {
                $prices = get_option('payout_rates_popup', []);
                $paidAds->website_url = get_option('popup_ad_url', '');
            }

            $publisher_price = 0;
            if (!empty($prices[$country][$traffic_source])) {
                $publisher_price = $prices[$country][$traffic_source];
            } elseif (!empty($prices['all'][$traffic_source])) {
                $publisher_price = $prices['all'][$traffic_source];
            }

            $paidAds->mode = 'simple';
            $paidAds->advertiser_price = 0;
            $paidAds->publisher_price = $publisher_price;
            $paidAds->ci = 0;
            $paidAds->cui = 0;
            $paidAds->cii = 0;

            return $paidAds;
        }

        $CampaignItems = TableRegistry::getTableLocator()->get('CampaignItems');

        $campaign_items = $CampaignItems->find()
            ->contain(['Campaigns'])
            ->where([
                'Campaigns.default_campaign' => 0,
                'Campaigns.ad_type' => $ad_type,
                'Campaigns.status' => 1,
                "Campaigns.traffic_source IN (1, :traffic_source)",
                'CampaignItems.weight <' => 100,
                'CampaignItems.country' => $country,
            ])
            ->order(['CampaignItems.weight' => 'ASC'])
            ->bind(':traffic_source', $traffic_source, 'integer')
            ->limit(10)
            ->toArray();

        if (count($campaign_items) == 0) {
            $campaign_items = $CampaignItems->find()
                ->contain(['Campaigns'])
                ->where([
                    'Campaigns.default_campaign' => 0,
                    'Campaigns.ad_type' => $ad_type,
                    'Campaigns.status' => 1,
                    "Campaigns.traffic_source IN (1, :traffic_source)",
                    'CampaignItems.weight <' => 100,
                    'CampaignItems.country' => 'all',
                ])
                ->order(['CampaignItems.weight' => 'ASC'])
                ->bind(':traffic_source', $traffic_source, 'integer')
                ->limit(10)
                ->toArray();
        }

        if (count($campaign_items) == 0) {
            $campaign_items = $CampaignItems->find()
                ->contain(['Campaigns'])
                ->where([
                    'Campaigns.default_campaign' => 1,
                    'Campaigns.ad_type' => $ad_type,
                    'Campaigns.status' => 1,
                    "Campaigns.traffic_source IN (1, :traffic_source)",
                    'CampaignItems.weight <' => 100,
                    "CampaignItems.country IN ( 'all', :country)",
                ])
                ->order(['CampaignItems.weight' => 'ASC'])
                ->bind(':traffic_source', $traffic_source, 'integer')
                ->bind(':country', $country, 'string')
                ->limit(10)
                ->toArray();
        }

        shuffle($campaign_items);

        $campaign_item = array_values($campaign_items)[0];

        $paidAds->mode = 'campaign';
        $paidAds->advertiser_price = $campaign_item->advertiser_price;
        $paidAds->publisher_price = $campaign_item->publisher_price;
        $paidAds->ci = $campaign_item->campaign_id;
        $paidAds->cui = $campaign_item->campaign->user_id;
        $paidAds->cii = $campaign_item->id;
        $paidAds->website_url = $campaign_item->campaign->website_url;
        $paidAds->banner_size = $campaign_item->campaign->banner_size;
        $paidAds->banner_code = $campaign_item->campaign->banner_code;

        return $paidAds;
    }

    /**
     * @param array|object $data
     * @param \App\Model\Entity\Link $link
     * @param int $ad_type
     * @return array
     */
    protected function calcEarnings($data, $link, $ad_type)
    {
        /**
         * Views reasons
         * 1- Earn
         * 2- Disabled cookie
         * 3- Anonymous user
         * 4- Adblock
         * 5- Proxy
         * 6- IP changed
         * 7- Not unique
         * 8- Full weight
         * 9- Default campaign
         * 10- Direct
         * 11- Invalid Country
         * 12- Earnings disabled
         * 13- User disabled earnings
         */

        $referer_url = $this->getRefererCookie($link->alias);

        /**
         * Check if user disabled earnings
         */
        if ($link->user->disable_earnings) {
            // Update link hits
            $this->updateLinkHits($link);
            $this->addNormalStatisticEntry($link, $ad_type, $data, get_ip(), 13);
            $content = [
                'status' => 'success',
                //'message' => 'Go without Earn because User disabled earnings',
                'message' => '',
                'url' => $link->url,
            ];

            return $content;
        }

        /**
         * Check if earnings are disabled
         */
        if (!(bool)get_option('enable_publisher_earnings', 1)) {
            // Update link hits
            $this->updateLinkHits($link);
            $this->addNormalStatisticEntry($link, $ad_type, $data, get_ip(), 12);
            $content = [
                'status' => 'success',
                //'message' => 'Go without Earn because earnings are disabled',
                'message' => '',
                'url' => $link->url,
            ];

            return $content;
        }

        /**
         * Check if valid country
         */
        if ($data['country'] == 'Others') {
            // Update link hits
            $this->updateLinkHits($link);
            $this->addNormalStatisticEntry($link, $ad_type, $data, get_ip(), 11);
            $content = [
                'status' => 'success',
                //'message' => 'Go without Earn because invalid country',
                'message' => '',
                'url' => $link->url,
            ];

            return $content;
        }

        /**
         * Check if cookie valid
         */
        $cookie = $this->Cookie->read('app_visitor');
        if (!is_array($cookie)) {
            // Update link hits
            $this->updateLinkHits($link);
            $this->addNormalStatisticEntry($link, $ad_type, $data, get_ip(), 2);
            $content = [
                'status' => 'success',
                //'message' => 'Go without Earn because no cookie',
                'message' => '',
                'url' => $link->url,
            ];

            return $content;
        }

        /**
         * Check if anonymous user
         */
        if ('anonymous' == $link->user->username) {
            // Update link hits
            $this->updateLinkHits($link);
            $this->addNormalStatisticEntry($link, $ad_type, $data, $cookie['ip'], 3);
            $content = [
                'status' => 'success',
                //'message' => 'Go without Earn because anonymous user',
                'message' => '',
                'url' => $link->url,
            ];

            return $content;
        }

        /**
         * Check if IP changed
         */
        if ($cookie['ip'] != get_ip()) {
            // Update link hits
            $this->updateLinkHits($link);
            $this->addNormalStatisticEntry($link, $ad_type, $data, $cookie['ip'], 6);
            $content = [
                'status' => 'success',
                //'message' => 'Go without Earn because IP changed',
                'message' => '',
                'url' => $link->url,
            ];

            return $content;
        }

        /**
         * Check for Adblock
         */
        if (!isset($_COOKIE['ab']) || in_array($_COOKIE['ab'], [0, 1])) {
            // Update link hits
            $this->updateLinkHits($link);
            $this->addNormalStatisticEntry($link, $ad_type, $data, $cookie['ip'], 4);
            $content = [
                'status' => 'success',
                //'message' => 'Go without Earn because Adblock',
                'message' => '',
                'url' => $link->url,
            ];

            return $content;
        }

        /**
         * Check if blocked referer domain
         */
        if ($this->isRefererBlocked($referer_url)) {
            // Update link hits
            $this->updateLinkHits($link);
            $this->addNormalStatisticEntry($link, $ad_type, $data, get_ip(), 14);
            $content = [
                'status' => 'success',
                //'message' => 'Go without Earn because blocked referer',
                'message' => '',
                'url' => $link->url,
            ];

            return $content;
        }


        // Campaign mode checks
        if ($data['mode'] === 'campaign') {
            /**
             * Check Campaign Item weight
             */
            $CampaignItems = TableRegistry::getTableLocator()->get('CampaignItems');

            $campaign_item = $CampaignItems->find()
                ->contain(['Campaigns'])
                ->where(['CampaignItems.id' => $data['cii']])
                ->where(['CampaignItems.weight <' => 100])
                ->where(['Campaigns.status' => 1])
                ->first();

            if (!$campaign_item) {
                // Update link hits
                $this->updateLinkHits($link);
                $this->addNormalStatisticEntry($link, $ad_type, $data, $cookie['ip'], 8);
                $content = [
                    'status' => 'success',
                    //'message' => 'Go without Earn because Campaign Item weight is full.',
                    'message' => '',
                    'url' => $link->url,
                ];

                return $content;
            }

            /**
             * Check if default campaign
             */
            if ($campaign_item->campaign->default_campaign) {
                // Update link hits
                $this->updateLinkHits($link);
                $this->addNormalStatisticEntry($link, $ad_type, $data, $cookie['ip'], 9);
                $content = [
                    'status' => 'success',
                    //'message' => 'Go without Earn because Default Campaign.',
                    'message' => '',
                    'url' => $link->url,
                ];

                return $content;
            }
        }

        /**
         * Check if proxy
         */
        if ($this->isProxy()) {
            // Update link hits
            $this->updateLinkHits($link);
            $this->addNormalStatisticEntry($link, $ad_type, $data, get_ip(), 5);
            $content = [
                'status' => 'success',
                //'message' => 'Go without Earn because proxy',
                'message' => '',
                'url' => $link->url,
            ];

            return $content;
        }

        $link_user_plan = get_user_plan($link->user_id);

        $views_hourly_limit = (int)$link_user_plan->views_hourly_limit;

        if ($views_hourly_limit > 0) {
            $hour = Time::now()->hour;
            $startOfHour = Time::now()->setTimeFromTimeString($hour . ':00:00')->format('Y-m-d H:i:s');
            $endOfHour = Time::now()->setTimeFromTimeString($hour . ':59:59')->format('Y-m-d H:i:s');

            $hourly_count = $this->Links->Statistics->find()
                ->where([
                    'Statistics.user_id' => $link->user_id,
                    'Statistics.reason' => 1,
                    'Statistics.created BETWEEN :startOfHour AND :endOfHour',
                ])
                ->bind(':startOfHour', $startOfHour, 'datetime')
                ->bind(':endOfHour', $endOfHour, 'datetime')
                ->count();

            if ($hourly_count >= $views_hourly_limit) {
                // Update link hits
                $this->updateLinkHits($link);
                $this->addNormalStatisticEntry($link, $ad_type, $data, $cookie['ip'], 15);
                $content = [
                    'status' => 'success',
                    //'message' => 'Go without Earn, reached the hourly limit',
                    'message' => '',
                    'url' => $link->url,
                ];

                return $content;
            }
        }

        $views_daily_limit = (int)$link_user_plan->views_daily_limit;

        if ($views_daily_limit > 0) {
            $startOfDay = Time::now()->startOfDay()->format('Y-m-d H:i:s');
            $endOfDay = Time::now()->endOfDay()->format('Y-m-d H:i:s');

            $daily_count = $this->Links->Statistics->find()
                ->where([
                    'Statistics.user_id' => $link->user_id,
                    'Statistics.reason' => 1,
                    'Statistics.created BETWEEN :startOfDay AND :endOfDay',
                ])
                ->bind(':startOfDay', $startOfDay, 'datetime')
                ->bind(':endOfDay', $endOfDay, 'datetime')
                ->count();

            if ($daily_count >= $views_daily_limit) {
                // Update link hits
                $this->updateLinkHits($link);
                $this->addNormalStatisticEntry($link, $ad_type, $data, $cookie['ip'], 16);
                $content = [
                    'status' => 'success',
                    //'message' => 'Go without Earn, reached the daily limit',
                    'message' => '',
                    'url' => $link->url,
                ];

                return $content;
            }
        }

        $views_monthly_limit = (int)$link_user_plan->views_monthly_limit;

        if ($views_monthly_limit > 0) {
            $startOfMonth = Time::now()->startOfMonth()->format('Y-m-d H:i:s');
            $endOfMonth = Time::now()->endOfMonth()->format('Y-m-d H:i:s');

            $monthly_count = $this->Links->Statistics->find()
                ->where([
                    'Statistics.user_id' => $link->user_id,
                    'Statistics.reason' => 1,
                    'Statistics.created BETWEEN :startOfMonth AND :endOfMonth',
                ])
                ->bind(':startOfMonth', $startOfMonth, 'datetime')
                ->bind(':endOfMonth', $endOfMonth, 'datetime')
                ->count();

            if ($monthly_count >= $views_monthly_limit) {
                // Update link hits
                $this->updateLinkHits($link);
                $this->addNormalStatisticEntry($link, $ad_type, $data, $cookie['ip'], 17);
                $content = [
                    'status' => 'success',
                    //'message' => 'Go without Earn, reached the monthly limit',
                    'message' => '',
                    'url' => $link->url,
                ];

                return $content;
            }
        }

        /**
         * Check for unique visit within last 24 hour
         */
        $startOfToday = Time::today()->format('Y-m-d H:i:s');
        $endOfToday = Time::now()->endOfDay()->format('Y-m-d H:i:s');

        $unique_where = [
            'Statistics.ip' => $cookie['ip'],
            'Statistics.publisher_earn >' => 0,
            'Statistics.created BETWEEN :startOfToday AND :endOfToday',
        ];

        if ($data['mode'] === 'campaign') {
            if (get_option('unique_visitor_per', 'campaign') == 'campaign') {
                $unique_where['Statistics.campaign_id'] = $data['ci'];
            }
        }

        $statistics = $this->Links->Statistics->find()
            ->where($unique_where)
            ->bind(':startOfToday', $startOfToday, 'datetime')
            ->bind(':endOfToday', $endOfToday, 'datetime')
            ->count();

        if ($statistics >= get_option('paid_views_day', 1)) {
            // Update link hits
            $this->updateLinkHits($link);
            $this->addNormalStatisticEntry($link, $ad_type, $data, $cookie['ip'], 7);
            $content = [
                'status' => 'success',
                //'message' => 'Go without Earn because Not unique.',
                'message' => '',
                'url' => $link->url,
            ];

            return $content;
        }

        /**
         * Add statistic record
         */
        $owner_earn = 0;
        if ($data['mode'] === 'campaign') {
            $owner_earn = ($data['advertiser_price'] - $data['publisher_price']) / 1000;
        }

        $publisher_earn = $data['publisher_price'] / 1000;
        if (!empty($link_user_plan->cpm_fixed)) {
            $publisher_earn = $link_user_plan->cpm_fixed / 1000;
        }

        $user_update = $this->Links->Users->find()->where(['Users.id' => $link->user_id])->first();

        $publisher_user_earnings = true;
        if ($this->Auth->user()) {
            if (get_user_plan($this->Auth->user('id'))->disable_ads) {
                $publisher_user_earnings = false;
            }
        }

        if ($publisher_user_earnings) {
            $user_update->publisher_earnings = price_database_format($user_update->publisher_earnings +
                $publisher_earn);
            $this->Links->Users->save($user_update);
        }

        $referral_id = $referral_earn = 0;
        $enable_referrals = (bool)get_option('enable_referrals', 1);

        if ($enable_referrals && $publisher_user_earnings && !empty($user_update->referred_by)) {
            $user_referred_by = $this->Links->Users->find()
                ->where([
                    'Users.id' => $user_update->referred_by,
                    'Users.status' => 1,
                    'Users.disable_earnings' => 0,
                ])
                ->first();

            if ($user_referred_by) {
                $plan_referral = true;
                if (!get_user_plan($user_referred_by->id)->referral) {
                    $plan_referral = false;
                }

                if (!(float)get_user_plan($user_referred_by->id)->referral_percentage) {
                    $plan_referral = false;
                }

                if ($plan_referral) {
                    $referral_percentage = ((float)get_user_plan($user_referred_by->id)->referral_percentage) / 100;
                    $referral_value = $publisher_earn * $referral_percentage;

                    $user_referred_by->referral_earnings = price_database_format($user_referred_by->referral_earnings +
                        $referral_value);

                    $this->Links->Users->save($user_referred_by);

                    $referral_id = $user_update->referred_by;
                    $referral_earn = $referral_value;
                }
            }
        }

        $country = $this->Links->Statistics->get_country($cookie['ip']);

        $statistic = $this->Links->Statistics->newEntity();

        $statistic->link_id = $link->id;
        $statistic->user_id = $link->user_id;
        $statistic->ad_type = $ad_type;
        $statistic->campaign_id = $data['ci'];
        $statistic->campaign_user_id = $data['cui'];
        $statistic->campaign_item_id = $data['cii'];
        $statistic->ip = $cookie['ip'];
        $statistic->country = $country;
        $statistic->owner_earn = price_database_format($owner_earn - $referral_earn);
        $statistic->publisher_earn = price_database_format($publisher_earn);
        $statistic->referral_id = $referral_id;
        $statistic->referral_earn = price_database_format($referral_earn);
        $statistic->referer_domain = (parse_url($referer_url, PHP_URL_HOST) ?: 'Direct');
        $statistic->referer = $referer_url;
        $statistic->user_agent = env('HTTP_USER_AGENT');
        $statistic->reason = 1;
        $this->Links->Statistics->save($statistic);

        if ($data['mode'] === 'campaign') {
            /**
             * Update campaign item views and weight
             */
            $campaign_item_update = $CampaignItems->newEntity();
            $campaign_item_update->id = $campaign_item['id'];
            $campaign_item_update->views = $campaign_item['views'] + 1;
            $campaign_item_update->weight = (($campaign_item['views'] + 1) / ($campaign_item['purchase'] * 1000)) * 100;
            $CampaignItems->save($campaign_item_update);

            /**
             * Finish Campaign
             */
            if ($campaign_item_update->weight >= 100) {
                $campaign_weight_items = $CampaignItems->find()
                    ->where([
                        'campaign_id' => $data['ci'],
                        'weight <' => 100,
                    ])
                    ->count();

                if ($campaign_weight_items === 0) {
                    $Campaigns = TableRegistry::getTableLocator()->get('Campaigns');
                    $campaign_complete = $Campaigns->newEntity();
                    $campaign_complete->id = $data['ci'];
                    $campaign_complete->status = 4;
                    $Campaigns->save($campaign_complete);
                }
            }
        }

        // Update link hits
        $this->updateLinkHits($link);
        $content = [
            'status' => 'success',
            //'message' => 'Go With earning :)',
            'message' => '',
            'url' => $link->url,
        ];

        return $content;
    }

    protected function addNormalStatisticEntry($link, $ad_type, $data, $ip, $reason = 0)
    {
        if ((bool)get_option('store_only_paid_clicks_statistics', 0)) {
            return;
        }

        $referer_url = $this->getRefererCookie($link->alias);

        if (!$ip) {
            $ip = get_ip();
        }

        if (empty($data['country'])) {
            $data['country'] = $this->Links->Statistics->get_country(get_ip());
        }

        $statistic = $this->Links->Statistics->newEntity();

        $statistic->link_id = $link->id;
        $statistic->user_id = $link->user_id;
        $statistic->ad_type = $ad_type;
        $statistic->campaign_id = $data['ci'];
        $statistic->campaign_user_id = $data['cui'];
        $statistic->campaign_item_id = $data['cii'];
        $statistic->ip = $ip;
        $statistic->country = $data['country'];
        $statistic->owner_earn = 0;
        $statistic->publisher_earn = 0;
        $statistic->referer_domain = (parse_url($referer_url, PHP_URL_HOST) ?: 'Direct');
        $statistic->referer = $referer_url;
        $statistic->user_agent = env('HTTP_USER_AGENT');
        $statistic->reason = $reason;
        $this->Links->Statistics->save($statistic);
    }

    protected function isRefererBlocked($referer_url)
    {
        $domains = explode(',', get_option('block_referers_domains'));
        $domains = array_map('trim', $domains);
        $domains = array_map('strtolower', $domains);
        $domains = array_filter($domains);

        if (empty($domains)) {
            return false;
        }

        $url_main_domain = strtolower(parse_url($referer_url, PHP_URL_HOST));

        if (in_array($url_main_domain, $domains)) {
            return true;
        }

        $domains = array_filter($domains, function ($value) {
            return substr($value, 0, 2) === "*.";
        });

        if (empty($domains)) {
            return false;
        }

        $domains = array_map(function ($value) {
            return substr($value, 1);
        }, $domains);

        foreach ($domains as $domain) {
            if (preg_match("/" . preg_quote($domain, '/') . "$/", $url_main_domain)) {
                return true;
            }
        }

        return false;
    }

    protected function setVisitorCookie()
    {
        $cookie = $this->Cookie->read('app_visitor');

        if (isset($cookie)) {
            return true;
        }

        $cookie_data = [
            'ip' => get_ip(),
            'date' => (new Time())->toDateTimeString(),
        ];
        $this->Cookie->configKey('app_visitor', [
            'expires' => '+1 day',
            'httpOnly' => true,
        ]);
        $this->Cookie->write('app_visitor', $cookie_data);

        return true;
    }

    /**
     * @param \App\Model\Entity\Link $link
     * @return null
     */
    protected function updateLinkHits($link = null)
    {
        if (!$link) {
            return null;
        }
        $link->hits += 1;
        $link->last_activity = Time::now();
        $link->setDirty('modified', true);
        $this->Links->save($link);

        return null;
    }

    /**
     * @return bool
     */
    protected function isProxy()
    {
        if (!empty($_SERVER["HTTP_CF_IPCOUNTRY"])) {
            if ($_SERVER["HTTP_CF_IPCOUNTRY"] === 'T1') {
                return true;
            }
        }

        $ip = get_ip();

        $proxy_service = get_option('proxy_service', 'disabled');

        if ($proxy_service === 'disabled') {
            return false;
        }

        if ($proxy_service === 'free') {
            $url = 'https://blackbox.ipinfo.app/lookup/' . urlencode($ip);

            $options = [
                CURLOPT_CONNECTTIMEOUT => 2,
                CURLOPT_TIMEOUT => 2,
                CURLOPT_ENCODING => 'gzip,deflate',
            ];

            $proxy_check = curlRequest($url, 'GET', [], [], $options)->body;

            if (strcasecmp($proxy_check, "Y") === 0) {
                return true;
            }
        }

        if ($proxy_service === 'isproxyip') {
            if (empty(get_option('isproxyip_key'))) {
                return false;
            }

            $url = 'https://api.isproxyip.com/v1/check.php?key=' . urlencode(get_option('isproxyip_key')) . '&ip=' . urlencode($ip);

            $options = [
                CURLOPT_CONNECTTIMEOUT => 2,
                CURLOPT_TIMEOUT => 2,
                CURLOPT_ENCODING => 'gzip,deflate',
            ];

            $proxy_check = curlRequest($url, 'GET', [], [], $options)->body;

            if (strcasecmp($proxy_check, "Y") === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    protected function verifyOnetimeCaptcha()
    {
        if (!isset($_SESSION['onetime_captcha'])) {
            return false;
        }

        $salt = \Cake\Utility\Security::getSalt();
        $onetime_captcha = sha1($salt . get_ip() . $_SERVER['HTTP_USER_AGENT']);

        if ($onetime_captcha === $_SESSION['onetime_captcha']) {
            return true;
        }

        return false;
    }

    protected function displayCaptchaShortlink($plan_disable_captcha, $plan_onetime_captcha)
    {
        if (!isset_captcha()) {
            return false;
        }

        if (get_option('enable_captcha_shortlink') !== 'yes') {
            return false;
        }

        if ($plan_disable_captcha) {
            return false;
        }

        if ($plan_onetime_captcha && $this->verifyOnetimeCaptcha()) {
            return false;
        }

        return true;
    }

    public function shorten()
    {
        $this->autoRender = false;

        $this->setResponse($this->getResponse()->withType('json'));

        if (!$this->getRequest()->is('ajax')) {
            $content = [
                'status' => 'error',
                'message' => __('Bad Request.'),
                'url' => '',
            ];
            $this->getResponse()->body(json_encode($content));

            return $this->response;
        }

        $user_id = 1;
        if (null !== $this->Auth->user('id')) {
            $user_id = $this->Auth->user('id');
        }

        if ($user_id === 1 &&
            (bool)get_option('enable_captcha_shortlink_anonymous', false) &&
            isset_captcha() &&
            !$this->Captcha->verify($this->getRequest()->getData())
        ) {
            $content = [
                'status' => 'error',
                'message' => __('The CAPTCHA was incorrect. Try again'),
                'url' => '',
            ];
            $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

            return $this->response;
        }

        if ($user_id == 1 && get_option('home_shortening_register') === 'yes') {
            $content = [
                'status' => 'error',
                'message' => __('Bad Request.'),
                'url' => '',
            ];
            $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

            return $this->response;
        }

        $user = $this->Links->Users->find()->where(['status' => 1, 'id' => $user_id])->first();

        if (!$user) {
            $content = [
                'status' => 'error',
                'message' => __('Invalid user'),
                'url' => '',
            ];
            $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

            return $this->response;
        }

        $url = trim($this->getRequest()->getData('url'));
        $url = str_replace(" ", "%20", $url);
        $url = parse_url($url, PHP_URL_SCHEME) === null ? 'http://' . $url : $url;
        $this->setRequest($this->getRequest()->withData('url', $url));

        $domain = '';
        if ($this->getRequest()->getData('domain')) {
            $domain = $this->getRequest()->getData('domain');
        }
        if (!in_array($domain, get_multi_domains_list())) {
            $domain = '';
        }

        $linkWhere = [
            'url_hash' => sha1($this->getRequest()->getData('url')),
            'user_id' => $user->id,
            'status' => 1,
            'ad_type' => $this->getRequest()->getData('ad_type'),
            'url' => $this->getRequest()->getData('url'),
        ];

        if ($this->getRequest()->getData('alias') && strlen($this->getRequest()->getData('alias')) > 0) {
            $linkWhere['alias'] = $this->getRequest()->getData('alias');
        }

        $link = $this->Links->find()->where($linkWhere)->first();

        if ($link) {
            $content = [
                'status' => 'success',
                'message' => '',
                'url' => get_short_url($link->alias, $domain),
            ];
            $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

            return $this->response;
        }

        $user_plan = get_user_plan($user->id);

        if ($user_plan->url_daily_limit) {
            $start = Time::now()->startOfDay()->format('Y-m-d H:i:s');
            $end = Time::now()->endOfDay()->format('Y-m-d H:i:s');

            $links_daily_count = $this->Links->find()
                ->where([
                    'user_id' => $user_id,
                    "created BETWEEN :date1 AND :date2",
                ])
                ->bind(':date1', $start, 'datetime')
                ->bind(':date2', $end, 'datetime')
                ->count();

            if ($links_daily_count >= $user_plan->url_daily_limit) {
                $content = [
                    'status' => 'error',
                    'message' => __('Your account has exceeded its daily created short links limit.'),
                    'url' => '',
                ];
                $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

                return $this->response;
            }
        }

        if ($user_plan->url_monthly_limit) {
            $start = Time::now()->startOfMonth()->format('Y-m-d H:i:s');
            $end = Time::now()->endOfMonth()->format('Y-m-d H:i:s');

            $links_monthly_count = $this->Links->find()
                ->where([
                    'user_id' => $user_id,
                    "created BETWEEN :date1 AND :date2",
                ])
                ->bind(':date1', $start, 'datetime')
                ->bind(':date2', $end, 'datetime')
                ->count();

            if ($links_monthly_count >= $user_plan->url_monthly_limit) {
                $content = [
                    'status' => 'error',
                    'message' => __('Your account has exceeded its monthly created short links limit.'),
                    'url' => '',
                ];
                $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

                return $this->response;
            }
        }

        $link = $this->Links->newEntity();
        $data = [];

        $data['user_id'] = $user->id;
        $data['url'] = $this->getRequest()->getData('url');
        $data['url_hash'] = sha1($this->getRequest()->getData('url'));

        $data['domain'] = $domain;

        if ($user_plan->alias && !empty($this->getRequest()->getData('alias'))) {
            $data['alias'] = $this->getRequest()->getData('alias');
        } else {
            $data['alias'] = $this->Links->geturl();
        }

        $data['ad_type'] = $this->getRequest()->getData('ad_type');
        $link->status = 1;
        $link->hits = 0;
        $link->method = 1;
        $link->last_activity = Time::now();

        $linkMeta = [
            'title' => '',
            'description' => '',
            'image' => '',
        ];

        if ($user_id === 1 && get_option('disable_meta_home') === 'no') {
            $linkMeta = $this->Links->getLinkMeta($this->getRequest()->getData('url'));
        }

        if ($user_id !== 1 && get_option('disable_meta_member') === 'no') {
            $linkMeta = $this->Links->getLinkMeta($this->getRequest()->getData('url'));
        }

        $data['title'] = $linkMeta['title'];
        $data['description'] = $linkMeta['description'];
        $link->image = $linkMeta['image'];

        $link = $this->Links->patchEntity($link, $data);
        if ($this->Links->save($link)) {
            $content = [
                'status' => 'success',
                'message' => '',
                'url' => get_short_url($link->alias, $domain),
            ];
            $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

            return $this->response;
        }

        $message = __('Invalid URL.');
        if ($link->getErrors()) {
            $error_msg = [];
            foreach ($link->getErrors() as $errors) {
                if (is_array($errors)) {
                    foreach ($errors as $error) {
                        $error_msg[] = $error;
                    }
                } else {
                    $error_msg[] = $errors;
                }
            }

            if (!empty($error_msg)) {
                $message = implode("<br>", $error_msg);
            }
        }

        $content = [
            'status' => 'error',
            'message' => $message,
            'url' => '',
        ];
        $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

        return $this->response;
    }
}
