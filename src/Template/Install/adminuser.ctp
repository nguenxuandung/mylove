<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
$this->assign('title', __('Step 3: Create Admin User'));

?>

<div class="install">
    <?php
    echo $this->Form->create($user, [
        'url' => [
            'controller' => 'Install',
            'action' => 'adminuser'
        ]
    ]);

    ?>

    <?=
    $this->Form->control('email', [
        'label' => __('Email'),
        'class' => 'form-control',
        'type' => 'email',
        'required' => 'required'
    ]);

    ?>

    <?=
    $this->Form->control('username', [
        'label' => __('Username'),
        'class' => 'form-control',
        'type' => 'text',
        'required' => 'required'
    ]);

    ?>

    <?=
    $this->Form->control('password', [
        'label' => __('Password'),
        'class' => 'form-control',
        'type' => 'password',
        'required' => 'required'
    ]);

    ?>

    <?=
    $this->Form->control('password_compare', [
        'label' => __('Confirm Password'),
        'class' => 'form-control',
        'type' => 'password',
        'required' => 'required'
    ]);

    ?>

    <div class="form-actions">
        <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-primary']); ?>
    </div>
    <?= $this->Form->end(); ?>


</div>

