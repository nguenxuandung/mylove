<?php
/**
 * @var \App\View\AppView $this
 */
$this->assign('title', __('Users Export'));
$this->assign('description', '');
$this->assign('content_title', __('Users Export'));
?>

<?php
$statuses = [
    1 => __('Active'),
    2 => __('Pending'),
    3 => __('Inactive')
]
?>

<div class="box box-primary">
    <div class="box-body">

        <?= $this->Form->create(null); ?>

        <?=
        $this->Form->control('fields', [
            'label' => __('Select fields to export'),
            'type' => 'select',
            'multiple' => true,
            'size' => 9,
            'options' => [
                'id' => __('Id'),
                'status' => __('Status'),
                'username' => __('Username'),
                'email' => __('Email'),
                'first_name' => __('First Name'),
                'last_name' => __('Last Name'),
                'login_ip' => __('Login IP'),
                'register_ip' => __('Register IP'),
                'created' => __('Created')
            ],
            //'value' => unserialize($settings['site_languages']['value']),
            'class' => 'form-control'
        ]);
        ?>



        <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-primary']); ?>

        <?= $this->Form->end(); ?>
    </div>
</div>
