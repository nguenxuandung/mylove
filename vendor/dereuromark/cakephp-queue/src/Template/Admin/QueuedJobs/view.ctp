<?php
/**
 * @var \App\View\AppView $this
 * @var \Queue\Model\Entity\QueuedJob $queuedJob
 */
?>
<nav class="actions large-3 medium-4 columns col-sm-4 col-xs-12" id="actions-sidebar">
	<ul class="side-nav nav nav-pills nav-stacked">
		<li class="heading"><?= __d('queue', 'Actions') ?></li>
		<li><?= $this->Html->link(__d('queue', 'Dashboard'), ['controller' => 'Queue', 'action' => 'index']) ?> </li>
		<li><?= $this->Html->link(__d('queue', 'Export'), ['action' => 'view', $queuedJob->id, '_ext' => 'json', '?' => ['download' => true]]) ?> </li>

		<?php if (!$queuedJob->completed) { ?>
			<li><?= $this->Html->link(__d('queue', 'Edit Queued Job'), ['action' => 'edit', $queuedJob->id]) ?> </li>
		<?php } ?>
		<li><?= $this->Form->postLink(__d('queue', 'Delete Queued Job'), ['action' => 'delete', $queuedJob->id], ['confirm' => __d('queue', 'Are you sure you want to delete # {0}?', $queuedJob->id)]) ?> </li>
		<li><?= $this->Html->link(__d('queue', 'List {0}', __d('queue', 'Queued Jobs')), ['action' => 'index']) ?> </li>
	</ul>
</nav>
<div class="content action-view view large-9 medium-8 columns col-sm-8 col-xs-12">
	<h1>ID <?= h($queuedJob->id) ?></h1>
	<table class="table vertical-table">
		<tr>
			<th><?= __d('queue', 'Job Type') ?></th>
			<td><?= h($queuedJob->job_type) ?></td>
		</tr>
		<tr>
			<th><?= __d('queue', 'Job Group') ?></th>
			<td><?= h($queuedJob->job_group) ?: '---' ?></td>
		</tr>
		<tr>
			<th><?= __d('queue', 'Reference') ?></th>
			<td><?= h($queuedJob->reference) ?: '---' ?></td>
		</tr>
		<tr>
			<th><?= __d('queue', 'Created') ?></th>
			<td><?= $this->Time->nice($queuedJob->created) ?></td>
		</tr>
		<tr>
			<th><?= __d('queue', 'Notbefore') ?></th>
			<td>
				<?= $this->Time->nice($queuedJob->notbefore) ?>
				<br>
				<?php echo $this->QueueProgress->timeoutProgressBar($queuedJob, 18); ?>
				<?php if ($queuedJob->notbefore && $queuedJob->notbefore->isFuture()) {
					echo '<div><small>';
					echo $this->Time->relLengthOfTime($queuedJob->notbefore);
					echo '</small></div>';
				} ?>
			</td>
		</tr>
		<tr>
			<th><?= __d('queue', 'Fetched') ?></th>
			<td>
				<?= $this->Time->nice($queuedJob->fetched) ?>
				<?php if ($queuedJob->fetched) {
					echo '<div><small>';
					echo __d('queue', 'Delay') . ': ' . $this->Time->duration($queuedJob->fetched->diff($queuedJob->created));
					echo '</small></div>';
				} ?>
			</td>
		</tr>
		<tr>
			<th><?= __d('queue', 'Completed') ?></th>
			<td>
				<?= $this->Format->ok($this->Time->nice($queuedJob->completed), (bool)$queuedJob->completed) ?>
				<?php if ($queuedJob->completed) {
					echo '<div><small>';
					echo __d('queue', 'Duration') . ': ' . $this->Time->duration($queuedJob->completed->diff($queuedJob->fetched));
					echo '</small></div>';
				} ?>
			</td>
		</tr>
		<tr>
			<th><?= __d('queue', 'Status') ?></th>
			<td><?= h($queuedJob->status) ?></td>
		</tr>
		<tr>
			<th><?= __d('queue', 'Progress') ?></th>
			<td>
				<?php if (!$queuedJob->completed && $queuedJob->fetched) { ?>
					<?php if (!$queuedJob->failed || !$queuedJob->failure_message) { ?>
						<?php echo $this->QueueProgress->progress($queuedJob) ?>
						<br>
						<?php
							$textProgressBar = $this->QueueProgress->progressBar($queuedJob, 18);
							echo $this->QueueProgress->htmlProgressBar($queuedJob, $textProgressBar);
						?>
					<?php } else { ?>
						<i><?php echo $this->Queue->failureStatus($queuedJob); ?></i>
					<?php } ?>
				<?php } ?>
			</td>
		</tr>
		<tr>
			<th><?= __d('queue', 'Failed') ?></th>
			<td>
				<?= $queuedJob->failed ? $this->Format->ok($this->Queue->fails($queuedJob), !$queuedJob->failed)  : '' ?>
				<?php
				if ($this->Queue->hasFailed($queuedJob)) {
					echo ' ' . $this->Form->postLink(__d('queue', 'Soft reset'), ['controller' => 'Queue', 'action' => 'resetJob', $queuedJob->id], ['confirm' => 'Sure?', 'class' => 'button button-primary btn margin btn-primary']);
				} elseif (!$queuedJob->completed && $queuedJob->fetched && $queuedJob->failed && $queuedJob->failure_message) {
					echo ' ' . $this->Form->postLink(__d('queue', 'Force reset'), ['controller' => 'Queue', 'action' => 'resetJob', $queuedJob->id], ['confirm' => 'Sure? This job is currently waiting to be re-queued.', 'class' => 'button button-primary btn margin btn-primary']);
				}
				?>
			</td>
		</tr>
		<tr>
			<th><?= __d('queue', 'Workerkey') ?></th>
			<td>
				<?= h($queuedJob->workerkey) ?>
				<?php if ($queuedJob->worker_process) { ?>
					[<?php echo $this->Html->link($queuedJob->worker_process->server ?: $queuedJob->worker_process->pid, ['controller' => 'QueueProcesses', 'action' => 'view', $queuedJob->worker_process->id]); ?>]
				<?php } ?>
			</td>
		</tr>
		<tr>
			<th><?= __d('queue', 'Priority') ?></th>
			<td><?= $this->Number->format($queuedJob->priority) ?></td>
		</tr>
	</table>
	<div class="row">
		<h3><?= __d('queue', 'Data') ?></h3>
		<?= $this->Text->autoParagraph(h($queuedJob->data)); ?>
		<?php
			if ($queuedJob->data && $this->Configure->read('debug')) {
				$data = unserialize($queuedJob->data);
				echo '<h4>Unserialized content (debug only)</h4>';
				echo '<pre>' . h(print_r($data, true)) . '</pre>';
			}
		?>
	</div>
	<div class="row">
		<h3><?= __d('queue', 'Failure Message') ?></h3>
		<?= $this->Text->autoParagraph(h($queuedJob->failure_message)); ?>
	</div>

</div>
