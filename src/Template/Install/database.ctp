<?php
/**
 * @var \App\View\AppView $this
 */
$this->assign('title', __('Step 1: Database'));

?>


<div class="install">

    <?php
    echo $this->Form->create(null, [
        'url' => [
            'controller' => 'Install',
            'action' => 'database'
        ]
    ]);
    ?>

    <?=
    $this->Form->control('host', [
        'label' => __('Database Host URL'),
        'class' => 'form-control',
        'type' => 'text',
        'default' => 'localhost',
        'required' => 'required'
    ]);
    ?>

    <?=
    $this->Form->control('port', [
        'label' => __('Database Port'),
        'class' => 'form-control',
        'type' => 'text',
        'default' => '3306',
        'required' => 'required'
    ]);
    ?>

    <?=
    $this->Form->control('username', [
        'label' => __('Database Username'),
        'class' => 'form-control',
        'type' => 'text',
        'required' => 'required'
    ]);
    ?>

    <?=
    $this->Form->control('password', [
        'label' => __('Database Username Password'),
        'class' => 'form-control',
        'type' => 'password',
        //'required' => 'required'
    ]);
    ?>

    <?=
    $this->Form->control('database', [
        'label' => __('Database Name'),
        'class' => 'form-control',
        'type' => 'text',
        'required' => 'required'
    ]);
    ?>


    <div class="form-actions">
        <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-primary']); ?>
    </div>
    <?= $this->Form->end(); ?>
</div>