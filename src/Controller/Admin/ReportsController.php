<?php

namespace App\Controller\Admin;

use Cake\I18n\Time;

/**
 * @property \App\Model\Table\UsersTable $Users
 * @property \Cake\ORM\Table $Reports
 */
class ReportsController extends AppAdminController
{
    public function campaigns()
    {
        $this->loadModel('Users');

        $first_statistic = $this->Users->Statistics->find()
            ->select('created')
            ->order(['id' => 'ASC'])
            ->first();
        $this->set('first_statistic', $first_statistic);

        if ($this->getRequest()->getQuery('Filter')) {
            $where = [];

            if ($this->getRequest()->getQuery('Filter.campaign_id')) {
                $where['campaign_id'] = (int)$this->getRequest()->getQuery('Filter.campaign_id');
            }

            if ($this->getRequest()->getQuery('Filter.user_id')) {
                $where['user_id'] = (int)$this->getRequest()->getQuery('Filter.user_id');
            }

            $date_from = $this->getDate('Filter.date_from');
            $date_to = $this->getDate('Filter.date_to');

            $campaign_earnings = $this->Users->Statistics->find()
                ->select([
                    'reason',
                    'count' => 'COUNT(reason)',
                    'earnings' => 'SUM(publisher_earn)',
                ])
                ->where($where);

            if ($date_from && $date_to) {
                $db_date_from = $date_from->startOfDay()->format('Y-m-d H:i:s');
                $db_date_to = $date_to->endOfDay()->format('Y-m-d H:i:s');

                $campaign_earnings = $campaign_earnings
                    ->where([
                        'created BETWEEN :start AND :end',
                    ])
                    ->bind(':start', $db_date_from, 'datetime')
                    ->bind(':end', $db_date_to, 'datetime');
            } elseif ($date_from) {
                $db_date_from = $date_from->startOfDay()->format('Y-m-d H:i:s');

                $campaign_earnings = $campaign_earnings
                    ->where([
                        'created >= :start',
                    ])
                    ->bind(':start', $db_date_from, 'datetime');
            } elseif ($date_to) {
                $db_date_to = $date_to->endOfDay()->format('Y-m-d H:i:s');

                $campaign_earnings = $campaign_earnings
                    ->where([
                        'created <= :end',
                    ])
                    ->bind(':end', $db_date_to, 'datetime');
            }

            $campaign_earnings = $campaign_earnings->order(['earnings' => 'DESC'])
                ->group(['reason'])
                ->toArray();

            $this->set('campaign_earnings', $campaign_earnings);

            $campaign_countries = $this->Users->Statistics->find()
                ->select([
                    'country',
                    'count' => 'COUNT(country)',
                    'earnings' => 'SUM(publisher_earn)',
                ])
                ->where($where);

            if ($date_from && $date_to) {
                $db_date_from = $date_from->startOfDay()->format('Y-m-d H:i:s');
                $db_date_to = $date_to->endOfDay()->format('Y-m-d H:i:s');

                $campaign_countries = $campaign_countries
                    ->where([
                        'created BETWEEN :start AND :end',
                    ])
                    ->bind(':start', $db_date_from, 'datetime')
                    ->bind(':end', $db_date_to, 'datetime');
            } elseif ($date_from) {
                $db_date_from = $date_from->startOfDay()->format('Y-m-d H:i:s');

                $campaign_countries = $campaign_countries
                    ->where([
                        'created >= :start',
                    ])
                    ->bind(':start', $db_date_from, 'datetime');
            } elseif ($date_to) {
                $db_date_to = $date_to->endOfDay()->format('Y-m-d H:i:s');

                $campaign_countries = $campaign_countries
                    ->where([
                        'created <= :end',
                    ])
                    ->bind(':end', $db_date_to, 'datetime');
            }

            $campaign_countries->order(['earnings' => 'DESC'])
                ->group(['country'])
                ->toArray();

            $this->set('campaign_countries', $campaign_countries);

            $campaign_referers = $this->Users->Statistics->find()
                ->select([
                    'referer_domain',
                    'count' => 'COUNT(referer_domain)',
                    'earnings' => 'SUM(publisher_earn)',
                ])
                ->where($where);

            if ($date_from && $date_to) {
                $db_date_from = $date_from->startOfDay()->format('Y-m-d H:i:s');
                $db_date_to = $date_to->endOfDay()->format('Y-m-d H:i:s');

                $campaign_referers = $campaign_referers
                    ->where([
                        'created BETWEEN :start AND :end',
                    ])
                    ->bind(':start', $db_date_from, 'datetime')
                    ->bind(':end', $db_date_to, 'datetime');
            } elseif ($date_from) {
                $db_date_from = $date_from->startOfDay()->format('Y-m-d H:i:s');

                $campaign_referers = $campaign_referers
                    ->where([
                        'created >= :start',
                    ])
                    ->bind(':start', $db_date_from, 'datetime');
            } elseif ($date_to) {
                $db_date_to = $date_to->endOfDay()->format('Y-m-d H:i:s');

                $campaign_referers = $campaign_referers
                    ->where([
                        'created <= :end',
                    ])
                    ->bind(':end', $db_date_to, 'datetime');
            }

            $campaign_referers->order(['earnings' => 'DESC'])
                ->group(['referer_domain'])
                ->toArray();

            $this->set('campaign_referers', $campaign_referers);
        }
    }

    /**
     * @param $name
     * @return \Cake\I18n\Time|null
     */
    protected function getDate($name)
    {
        $date = $this->getRequest()->getQuery($name, ['year' => null, 'month' => null, 'day' => null]);

        if (empty($date['year']) && empty($date['month']) && empty($date['day'])) {
            return null;
        }

        $date_time = Time::now();

        if (!empty($date['year'])) {
            $date_time = $date_time->year($date['year']);
        }

        if (!empty($date['month'])) {
            $date_time = $date_time->month($date['month']);
        } else {
            $date_time = $date_time->month(01);
        }

        if (!empty($date['day'])) {
            $date_time = $date_time->day($date['day']);
        } else {
            $date_time = $date_time->day(01);
        }

        return $date_time;
    }
}
