<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Plan $plan
 * @var mixed $plans
 */
$this->assign('title', __('Delete Plan'));
$this->assign('description', '');
$this->assign('content_title', __('Delete Plan'));
?>

<div class="box box-primary">
    <div class="box-body">

        <?= $this->Form->create($plan); ?>

        <?= $this->Form->hidden('id'); ?>

        <?=
        $this->Form->control('plan_replace', [
            'label' => __("Replace users within this plan with"),
            'options' => $plans,
            'empty' => __('Select Plan'),
            'class' => 'form-control'
        ]);

        ?>

        <?= $this->Form->button(__('Delete'),
            ['confirm' => __('Are you sure?'), 'class' => 'btn btn-danger']); ?>

        <?= $this->Form->end(); ?>
    </div>
</div>
