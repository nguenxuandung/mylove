<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var mixed $message
 */
$this->assign('title', __('Message User'));
$this->assign('description', '');
$this->assign('content_title', __('Message User'));

?>

<div class="box box-primary">
    <div class="box-body">

        <?= $this->Form->create($message); ?>

        <?=
        $this->Form->control('email', [
            'label' => __('User Email'),
            'class' => 'form-control',
            'value' => $user->email,
            'type' => 'email'
        ]);
        ?>

        <?=
        $this->Form->control('subject', [
            'label' => __('Subject'),
            'class' => 'form-control',
            'type' => 'text'
        ]);
        ?>

        <?=
        $this->Form->control('message', [
            'label' => __('Message'),
            'class' => 'form-control',
            'type' => 'textarea'
        ]);
        ?>

        <?= $this->Form->button(__('Message'), ['class' => 'btn btn-primary']); ?>

        <?= $this->Form->end(); ?>

    </div>
</div>
