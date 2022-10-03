<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var mixed $plans
 */
$this->assign('title', __('Add User'));
$this->assign('description', '');
$this->assign('content_title', __('Add User'));
?>

<div class="box box-primary">
    <div class="box-body">

        <?= $this->Form->create($user); ?>

        <?=
        $this->Form->control('role', [
            'label' => __('Role'),
            'options' => [
                'member' => __('Member'),
                'admin' => __('Admin'),
            ],
            'class' => 'form-control',
        ]);
        ?>

        <?=
        $this->Form->control('status', [
            'label' => __('Status'),
            'options' => [
                1 => __('Active'),
                2 => __('Pending'),
                3 => __('Inactive'),
            ],
            'class' => 'form-control',
        ]);
        ?>

        <?=
        $this->Form->control('username', [
            'label' => __('Username'),
            'class' => 'form-control',
        ])
        ?>

        <?=
        $this->Form->control('email', [
            'label' => __('Email'),
            'type' => 'email',
            'class' => 'form-control',
        ])
        ?>


        <?=
        $this->Form->control('password', [
            'label' => __('Password'),
            'class' => 'form-control',
        ])
        ?>

        <div class="row">
            <div class="col-sm-6">
                <?=
                $this->Form->control('plan_id', [
                    'label' => __('Plan'),
                    'options' => $plans,
                    'empty' => __('Choose Plan'),
                    'class' => 'form-control',
                    'default' => 1,
                    'required' => true,
                ]);
                ?>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label><?= __('Plan Expiration Date') ?></label>
                    <div>
                        <?= $this->Form->date('expiration'); ?>
                        <span class="help-block"><?= __('Leave empty for infinity') ?></span>
                    </div>
                </div>
            </div>
        </div>

        <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-primary']); ?>

        <?= $this->Form->end(); ?>
    </div>
</div>
