<?php

namespace App\Controller\Member;

use Cake\I18n\Time;

/**
 * @property \App\Model\Table\LinksTable $Links
 * @property \Cake\ORM\Table $Tools
 */
class ToolsController extends AppMemberController
{
    public function quick()
    {
        if (!$this->logged_user_plan->api_quick) {
            $this->Flash->error(__('You must upgrade your plan so you can use this tool.'));

            return $this->redirect(['_name' => 'member_dashboard']);
        }
    }

    public function massShrinker()
    {
        if (!$this->logged_user_plan->api_mass) {
            $this->Flash->error(__('You must upgrade your plan so you can use this tool.'));

            return $this->redirect(['_name' => 'member_dashboard']);
        }

        $this->loadModel('Links');

        $link = $this->Links->newEntity();
        if ($this->getRequest()->is('post')) {
            $urls = explode("\n", str_replace("\r", "\n", $this->getRequest()->data['urls']));
            $urls = array_unique(array_filter($urls));
            $urls = array_slice($urls, 0, get_option('mass_shrinker_limit', 20));
            $urls = array_map('trim', $urls);

            $ad_type = get_option('member_default_advert', 1);
            if (isset($this->getRequest()->data['ad_type'])) {
                if (array_key_exists($this->getRequest()->data['ad_type'], get_allowed_ads())) {
                    $ad_type = $this->getRequest()->data['ad_type'];
                }
            }

            $results = [];
            foreach ($urls as $url) {
                $results[] = $this->addMassShrinker($url, $ad_type);
            }

            $this->set('results', $results);
        }
        $this->set('link', $link);
    }

    protected function addMassShrinker($url, $ad_type = 1)
    {
        $this->loadModel('Links');

        $url = parse_url($url, PHP_URL_SCHEME) === null ? 'http://' . $url : $url;

        $link = $this->Links->find()->where([
            'url_hash' => sha1($url),
            'user_id' => $this->Auth->user('id'),
            'status' => 1,
            'ad_type' => $ad_type,
            'url' => $url,
        ])->first();

        if ($link) {
            return ['url' => $url, 'short' => $link->alias, 'domain' => $link->domain];
        }

        /**
         * @var \App\Model\Entity\Plan $user_plan
         */
        $user_plan = $this->logged_user_plan;

        if ($user_plan->url_daily_limit) {
            $start = Time::now()->startOfDay()->format('Y-m-d H:i:s');
            $end = Time::now()->endOfDay()->format('Y-m-d H:i:s');

            $links_daily_count = $this->Links->find()
                ->where([
                    'user_id' => $this->Auth->user('id'),
                    "created BETWEEN :date1 AND :date2",
                ])
                ->bind(':date1', $start, 'datetime')
                ->bind(':date2', $end, 'datetime')
                ->count();

            if ($links_daily_count >= $user_plan->url_daily_limit) {
                return [
                    'url' => $url,
                    'short' => 'error',
                    'domain' => '',
                    'message' => __('Your account has exceeded its daily short links limit.'),
                ];
            }
        }

        if ($user_plan->url_monthly_limit) {
            $start = Time::now()->startOfMonth()->format('Y-m-d H:i:s');
            $end = Time::now()->endOfMonth()->format('Y-m-d H:i:s');

            $links_monthly_count = $this->Links->find()
                ->where([
                    'user_id' => $this->Auth->user('id'),
                    "created BETWEEN :date1 AND :date2",
                ])
                ->bind(':date1', $start, 'datetime')
                ->bind(':date2', $end, 'datetime')
                ->count();

            if ($links_monthly_count >= $user_plan->url_monthly_limit) {
                return [
                    'url' => $url,
                    'short' => 'error',
                    'domain' => '',
                    'message' => __('Your account has exceeded its monthly short links limit.'),
                ];
            }
        }


        $link = $this->Links->newEntity();
        $data = [];

        $data['user_id'] = $this->Auth->user('id');
        $data['url'] = $url;
        $data['url_hash'] = sha1($url);
        $data['alias'] = $this->Links->geturl();
        $data['ad_type'] = $ad_type;
        $link->status = 1;
        $link->hits = 0;
        $link->method = 3;

        $linkMeta = [
            'title' => '',
            'description' => '',
            'image' => '',
        ];

        if (get_option('disable_meta_api') === 'no') {
            $linkMeta = $this->Links->getLinkMeta($url);
        }

        $link->title = $linkMeta['title'];
        $link->description = $linkMeta['description'];
        $link->image = $linkMeta['image'];

        $link = $this->Links->patchEntity($link, $data);
        if ($this->Links->save($link)) {
            return ['url' => $url, 'short' => $link->alias, 'domain' => $link->domain];
        }

        return ['url' => $url, 'short' => 'error', 'domain' => ''];
    }

    public function api()
    {
        if (!$this->logged_user_plan->api_developer) {
            $this->Flash->error(__('You must upgrade your plan so you can use this tool.'));

            return $this->redirect(['_name' => 'member_dashboard']);
        }
    }

    public function full()
    {
        if (!$this->logged_user_plan->api_full) {
            $this->Flash->error(__('You must upgrade your plan so you can use this tool.'));

            return $this->redirect(['_name' => 'member_dashboard']);
        }
    }

    public function bookmarklet()
    {
        if (!$this->logged_user_plan->bookmarklet) {
            $this->Flash->error(__('You must upgrade your plan so you can use this tool.'));

            return $this->redirect(['_name' => 'member_dashboard']);
        }
    }
}
