<?php

namespace App\Shell\Task;

use Cake\I18n\Time;
use Cake\ORM\TableRegistry;
use Queue\Shell\Task\QueueTask;
use Queue\Shell\Task\QueueTaskInterface;

class QueueDeleteLessActivityLinksTask extends QueueTask implements QueueTaskInterface
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
        $linksTable = TableRegistry::getTableLocator()->get('Links');

        $months = (int)get_option('delete_links_without_activity_months', 0);
        $views = (bool)get_option('delete_links_without_activity_views', 0);

        if ($months <= 0) {
            return;
        }

        /** @var \App\Model\Entity\Link[] $links */
        $links = $linksTable->find()
            ->select(['id', 'last_activity'])
            ->where([
                'last_activity <' => Time::now()->subMonths($months)->toDateTimeString(),
                'last_activity IS NOT NULL',
            ]);

        foreach ($links as $link) {
            $linksTable->delete($link);

            if ($views) {
                $linksTable->Statistics->deleteAll(['link_id' => $link->id]);
            }
        }

        //throw new \Queue\Model\QueueException('Could not do that.');
    }
}
