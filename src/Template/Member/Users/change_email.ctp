<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<?php
$this->assign('title', __('Change Email'));
$this->assign('description', '');
$this->assign('content_title', __('Change Email'));
?>

<div class="box box-primary">
    <div class="box-body">

        <?= $this->Form->create($user); ?>

        <?= $this->Form->hidden('id'); ?>

        <?=
        $this->Form->control('email', [
            'label' => __('Current Email'),
            'class' => 'form-control',
            'disabled' => 'disabled'
        ])

        ?>

        <?=
        $this->Form->control('temp_email', [
            'label' => __('New Email'),
            'class' => 'form-control',
            'type' => 'email'
        ])

        ?>

        <?=
        $this->Form->control('confirm_email', [
            'label' => __('Re-enter New Email'),
            'class' => 'form-control',
            'type' => 'email'
        ])

        ?>

        <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-primary btn-lg']); ?>

        <?= $this->Form->end() ?>

    </div>
</div>
