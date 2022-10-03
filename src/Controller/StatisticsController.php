<?php

namespace App\Controller;

use Cake\Event\Event;
use Cake\I18n\Time;
use Cake\Cache\Cache;
use Cake\Http\Exception\NotFoundException;

/**
 * @property \App\Model\Table\StatisticsTable $Statistics
 */
class StatisticsController extends FrontController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->viewBuilder()->setLayout('go_banner');
        $this->Auth->allow(['viewInfo']);
    }

    public function viewInfo($alias = null)
    {
        if (!$alias) {
            throw new NotFoundException(__('404 Not Found'));
        }

        /**
         * @var \App\Model\Entity\Link $link
         */
        $link = $this->Statistics->Links->find()->where(['alias' => $alias, 'status <>' => 3])->first();
        if (!$link) {
            throw new NotFoundException(__('404 Not Found'));
        }
        $this->set('link', $link);

        /**
         * @var \App\Model\Entity\User $user
         */
        $user = $this->Statistics->Links->Users->find()
            ->contain('Plans')
            ->where([
                'Users.id' => $link->user_id,
                'Users.status' => 1,
            ])
            ->first();
        if (!$user) {
            throw new NotFoundException(__('404 Not Found'));
        }

        if (null !== $this->Auth->user('id')) {
            if ($this->Auth->user('role') === 'member') {
                if (get_option('link_info_member', 'yes') === 'no') {
                    throw new NotFoundException(__('404 Not Found'));
                }

                if ($this->Auth->user('id') !== $user->id) {
                    throw new NotFoundException(__('404 Not Found'));
                }
            }
        } else {
            if (get_option('link_info_public', 'yes') === 'no') {
                throw new NotFoundException(__('404 Not Found'));
            }

            if (1 !== $user->id) {
                throw new NotFoundException(__('404 Not Found'));
            }
        }

        if (!get_user_plan($user->id)->stats) {
            if ($this->Auth->user()) {
                $this->Flash->error(__('You must upgrade your plan so you can see the statistics.'));

                return $this->redirect(['_name' => 'member_dashboard']);
            } else {
                throw new NotFoundException(__('404 Not Found'));
            }
        }

        $time_zone = get_option('timezone', 'UTC');
        $now = Time::now($time_zone)
            ->i18nFormat('yyyy-MM-dd HH:mm:ss', 'UTC', 'en');
        $last30 = Time::now($time_zone)
            ->modify('-30 day')
            ->i18nFormat('yyyy-MM-dd HH:mm:ss', 'UTC', 'en');

        $time_zone_offset = Time::now($time_zone)->format('P');

        if (($stats = Cache::read('info_stats_' . $alias, '1hour')) === false) {
            $stats = $this->Statistics->find()
                ->select([
                    'statDate' => "DATE_FORMAT(CONVERT_TZ(created,'+00:00','" . $time_zone_offset . "'), '%Y-%m-%d')",
                    'statDateCount' => "COUNT(DATE_FORMAT(CONVERT_TZ(created,'+00:00','" . $time_zone_offset . "'), '%Y-%m-%d'))",
                ])
                ->where([
                    'link_id' => $link->id,
                    'user_id' => $link->user_id,
                    'created BETWEEN :last30 AND :now',
                ])
                ->bind(':last30', $last30, 'datetime')
                ->bind(':now', $now, 'datetime')
                ->order(['created' => 'DESC'])
                ->group('statDate')
                ->toArray();
            Cache::write('info_stats_' . $alias, $stats, '1hour');
        }

        $this->set('stats', $stats);

        if (($countries = Cache::read('info_countries_' . $alias, '1hour')) === false) {
            $countries = $this->Statistics->find()
                ->select([
                    'country',
                    'clicks' => 'COUNT(country)',
                ])
                ->where([
                    'link_id' => $link->id,
                    'user_id' => $link->user_id,
                    'created BETWEEN :last30 AND :now',
                ])
                ->bind(':last30', $last30, 'datetime')
                ->bind(':now', $now, 'datetime')
                ->order(['clicks' => 'DESC'])
                ->group('country')
                ->toArray();
            Cache::write('info_countries_' . $alias, $countries, '1hour');
        }

        $this->set('countries', $countries);

        if (($referrers = Cache::read('info_referrers_' . $alias, '1hour')) === false) {
            $referrers = $this->Statistics->find()
                ->select([
                    'referer_domain',
                    'clicks' => 'COUNT(referer)',
                ])
                ->where([
                    'link_id' => $link->id,
                    'user_id' => $link->user_id,
                    'created BETWEEN :last30 AND :now',
                ])
                ->bind(':last30', $last30, 'datetime')
                ->bind(':now', $now, 'datetime')
                ->order(['clicks' => 'DESC'])
                ->group('referer_domain')
                ->toArray();
            Cache::write('info_referrers_' . $alias, $referrers, '1hour');
        }

        $this->set('referrers', $referrers);
    }
}
