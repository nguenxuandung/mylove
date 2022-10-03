<?php
/**
 * @var \App\View\AppView $this
 */
$this->assign('title', __('Withdraws Export'));
$this->assign('description', '');
$this->assign('content_title', __('Withdraws Export'));
?>

<?php
$statuses = [
    1 => __('Approved'),
    2 => __('Pending'),
    3 => __('Complete'),
    4 => __('Cancelled'),
    5 => __('Returned')
];

$withdrawal_methods = array_column_polyfill(get_withdrawal_methods(), 'name', 'id');
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
                'user_id' => __('User Id'),
                'publisher_earnings' => __('Publisher Earnings'),
                'referral_earnings' => __('Referral Earnings'),
                'amount' => __('Amount'),
                'method' => __('Withdrawal Method'),
                'account' => __('Withdrawal Account'),
                'created' => __('Created')
            ],
            //'value' => unserialize($settings['site_languages']['value']),
            'class' => 'form-control'
        ]);
        ?>

        <legend><?= __("Conditions") ?></legend>

        <div class="row">
            <div class="col-sm-2"><?= __('User Id') ?></div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('conditions.user_id', [
                    'label' => false,
                    'class' => 'form-control',
                    'type' => 'number',
                    'size' => 0
                ]);
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2"><?= __('Status') ?></div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('conditions.status', [
                    'label' => false,
                    'options' => $statuses,
                    'empty' => __('Status'),
                    'class' => 'form-control'
                ]);
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2"><?= __('Withdrawal Method') ?></div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('conditions.method', [
                    'label' => false,
                    'options' => $withdrawal_methods,
                    'empty' => __('Withdrawal Method'),
                    'class' => 'form-control'
                ]);
                ?>
            </div>
        </div>

        <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-primary']); ?>

        <?= $this->Form->end(); ?>
    </div>
</div>
