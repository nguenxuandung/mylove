<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<?php
$this->assign('title', __('Change Password'));
$this->assign('description', '');
$this->assign('content_title', __('Change Password'));
?>

<div class="box box-primary">
    <div class="box-body">

        <?= $this->Form->create($user); ?>

        <?= $this->Form->hidden('id'); ?>

        <?=
        $this->Form->control('current_password', [
            'label' => __('Current Password'),
            'class' => 'form-control',
            'type' => 'password'
        ])

        ?>

        <?=
        $this->Form->control('password', [
            'label' => __('New Password'),
            'class' => 'form-control',
            'type' => 'password'
        ])

        ?>

        <?=
        $this->Form->control('confirm_password', [
            'label' => __('Re-enter New Password'),
            'class' => 'form-control',
            'type' => 'password'
        ])

        ?>

        <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-primary btn-lg']); ?>

        <?= $this->Form->end() ?>

    </div>
</div>
