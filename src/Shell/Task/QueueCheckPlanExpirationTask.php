<?php

namespace App\Shell\Task;

use Cake\I18n\Time;
use Cake\ORM\TableRegistry;
use Queue\Shell\Task\QueueTask;
use Queue\Shell\Task\QueueTaskInterface;

class QueueCheckPlanExpirationTask extends QueueTask implements QueueTaskInterface
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
        /** @var \App\Model\Table\UsersTable $usersTable */
        $usersTable = TableRegistry::getTableLocator()->get('Users');

        /** @var \App\Model\Entity\User[] $users */
        $users = $usersTable->find()
            ->where(
                [
                    'expiration <' => Time::now()->toDateTimeString(),
                    'expiration IS NOT NULL',
                ]
            );

        foreach ($users as $user) {
            $user->plan_id = 1;
            $user->expiration = null;
            $user->setDirty('modified', true);
            $usersTable->save($user);
        }

        //throw new \Queue\Model\QueueException('Couldn\'t do that.');
    }
}
