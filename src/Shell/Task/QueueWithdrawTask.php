<?php

namespace App\Shell\Task;

use Cake\ORM\TableRegistry;
use Queue\Shell\Task\QueueTask;
use Queue\Shell\Task\QueueTaskInterface;

class QueueWithdrawTask extends QueueTask implements QueueTaskInterface
{
    /**
     * @var int
     */
    public $timeout = 20;

    /**
     * @var int
     */
    public $retries = 1;

    /**
     * @param array $data The array passed to QueuedJobsTable::createJob()
     * @param int $jobId The id of the QueuedJob entity
     * @return void
     */
    public function run(array $data, $jobId)
    {
        $withdrawsTable = TableRegistry::getTableLocator()->get('Withdraws');
        $statisticsTable = TableRegistry::getTableLocator()->get('Statistics');

        /** @var \App\Model\Entity\Withdraw $withdraw */
        $withdraw = $withdrawsTable->get($data['id']);

        /** @var \App\Model\Entity\Withdraw $pre_withdraw */
        $pre_withdraw = $withdrawsTable->find()
            ->where([
                'created <' => $withdraw->created,
                'user_id' => $withdraw->user_id,
                'status !=' => 5,
            ])
            ->order(['created' => 'DESC'])
            ->first();

        $date1 = (!$pre_withdraw) ? '0000-00-00 00:00:00' : $pre_withdraw->created;
        $date2 = $withdraw->created;

        $countries = $statisticsTable->find()
            ->select([
                'country',
                'count' => 'COUNT(country)',
                'publisher_earnings' => 'SUM(publisher_earn)',
                //'referral_earnings' => 'SUM(referral_earn)'
            ])
            ->where([
                "Statistics.created BETWEEN :date1 AND :date2",
                'Statistics.publisher_earn >' => 0,
                'Statistics.user_id' => $withdraw->user_id,
            ])
            ->bind(':date1', $date1, 'datetime')
            ->bind(':date2', $date2, 'datetime')
            ->order(['count' => 'DESC'])
            ->group(['country'])
            ->toArray();

        $reasons = $statisticsTable->find()
            ->select([
                'reason',
                'count' => 'COUNT(reason)',
            ])
            ->where([
                "Statistics.created BETWEEN :date1 AND :date2",
                'Statistics.user_id' => $withdraw->user_id,
            ])
            ->bind(':date1', $date1, 'datetime')
            ->bind(':date2', $date2, 'datetime')
            ->order(['count' => 'DESC'])
            ->group(['reason'])
            ->toArray();

        $ips = $statisticsTable->find()
            ->select([
                'ip',
                'count' => 'COUNT(ip)',
                'publisher_earnings' => 'SUM(publisher_earn)',
                //'referral_earnings' => 'SUM(referral_earn)'
            ])
            ->where([
                "Statistics.created BETWEEN :date1 AND :date2",
                'Statistics.publisher_earn >' => 0,
                'Statistics.user_id' => $withdraw->user_id,
            ])
            ->bind(':date1', $date1, 'datetime')
            ->bind(':date2', $date2, 'datetime')
            ->order(['count' => 'DESC'])
            ->group(['ip'])
            ->toArray();

        $referrers = $statisticsTable->find()
            ->select([
                'referer_domain',
                'count' => 'COUNT(referer_domain)',
                'publisher_earnings' => 'SUM(publisher_earn)',
                //'referral_earnings' => 'SUM(referral_earn)'
            ])
            ->where([
                "Statistics.created BETWEEN :date1 AND :date2",
                'Statistics.publisher_earn >' => 0,
                'Statistics.user_id' => $withdraw->user_id,
            ])
            ->bind(':date1', $date1, 'datetime')
            ->bind(':date2', $date2, 'datetime')
            ->order(['count' => 'DESC'])
            ->group(['referer_domain'])
            ->toArray();

        $links = $statisticsTable->find()
            ->contain(['Links'])
            ->select([
                'Links.alias',
                'Links.url',
                'Links.title',
                'Links.domain',
                'count' => 'COUNT(Statistics.link_id)',
                'publisher_earnings' => 'SUM(Statistics.publisher_earn)',
            ])
            ->where([
                "Statistics.created BETWEEN :date1 AND :date2",
                'Statistics.publisher_earn >' => 0,
                'Statistics.user_id' => $withdraw->user_id,
            ])
            ->order(['count' => 'DESC'])
            ->bind(':date1', $date1, 'datetime')
            ->bind(':date2', $date2, 'datetime')
            ->group('Statistics.link_id')
            ->toArray();

        $withdraw->json_data = \json_encode([
            'countries' => $countries,
            'reasons' => $reasons,
            'ips' => $ips,
            'referrers' => $referrers,
            'links' => $links,
        ]);
        $withdrawsTable->save($withdraw);


        //throw new \Queue\Model\QueueException('Couldn\'t do that.');
    }
}
