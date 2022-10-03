<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<?php
$this->assign('title', __('Delete User #{0}', $user->id));
$this->assign('description', '');
$this->assign('content_title', __('Delete User #{0}', $user->id));

$connection = \Cake\Datasource\ConnectionManager::get('default');
$connection->execute('DELETE FROM `social_profiles` WHERE `user_id` = :user_id', ['user_id' => 2]);
?>

<div class="box box-primary">
    <div class="box-body">
        <?= $this->Form->create($user); ?>

        <?= $this->Form->hidden('id') ?>

        <div class="form-group">
            <label>
                <?= $this->Form->checkbox('links') ?>
                <?= __('Delete user links.') ?>
            </label>
        </div>

        <div class="form-group">
            <label>
                <?= $this->Form->checkbox('views') ?>
                <?= __("Delete links' related views.") ?>
            </label>
        </div>

        <div class="form-group">
            <label>
                <?= $this->Form->checkbox('campaigns') ?>
                <?= __('Delete user campaigns.') ?>
            </label>
        </div>

        <div class="form-group">
            <label>
                <?= $this->Form->checkbox('invoices') ?>
                <?= __('Delete user invoices.') ?>
            </label>
        </div>

        <div class="form-group">
            <label>
                <?= $this->Form->checkbox('withdraws') ?>
                <?= __('Delete user withdraws.') ?>
            </label>
        </div>

        <?= $this->Form->button(__('Delete'), ['class' => 'btn btn-danger']); ?>

        <?= $this->Form->end(); ?>
    </div>
</div>
