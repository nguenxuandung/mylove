<?php

namespace App\Shell\Task;

use Cake\I18n\Time;
use Cake\ORM\TableRegistry;
use Queue\Shell\Task\QueueTask;
use Queue\Shell\Task\QueueTaskInterface;

class QueueDeletePendingUsersTask extends QueueTask implements QueueTaskInterface
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
        /** @var \App\Model\Table\LinksTable $linksTable */
        $usersTable = TableRegistry::getTableLocator()->get('Users');

        $months = (int)get_option('delete_pending_users_months', 0);

        if ($months <= 0) {
            return;
        }

        /** @var \App\Model\Entity\User[] $links */
        $users = $usersTable->find()
            ->select(['id', 'status', 'created'])
            ->where([
                'status' => 2,
                'created <' => Time::now()->subMonths($months)->toDateTimeString(),
            ]);

        foreach ($users as $user) {
            $usersTable->delete($user);
        }

        //throw new \Queue\Model\QueueException('Could not do that.');
    }
}
