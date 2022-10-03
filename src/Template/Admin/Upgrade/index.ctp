<?php
/**
 * @var \App\View\AppView $this
 */
$this->assign('title', __('Upgrade'));
$this->assign('description', '');
$this->assign('content_title', __('Upgrade'));

?>

<div class="box box-primary">
    <div class="box-body">

        <?= $this->Form->create('null',
            ['onSubmit' => "run_upgrade.disabled=true; run_upgrade.innerHTML='" . __('Upgrading ...') . "'; return true;"]); ?>

        <?= $this->Form->button(__('Run Upgrade Process'),
            ['name' => 'run_upgrade', 'class' => 'btn btn-danger btn-lg']); ?>
        <?= $this->Form->end(); ?>

    </div><!-- /.box-body -->
</div>
