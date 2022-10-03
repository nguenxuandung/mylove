<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<?php
$this->assign('title', __('Profile'));
$this->assign('description', '');
$this->assign('content_title', __('Profile'));
?>

<div class="box box-primary">
    <div class="box-body">

        <?= $this->Form->create($user); ?>

        <?= $this->Form->hidden('id'); ?>

        <legend><?= __('Billing Address') ?></legend>

        <div class="row">
            <div class="col-sm-6">
                <?=
                $this->Form->control('first_name', [
                    'label' => __('First Name'),
                    'class' => 'form-control',
                ])
                ?>
            </div>
            <div class="col-sm-6">
                <?=
                $this->Form->control('last_name', [
                    'label' => __('Last Name'),
                    'class' => 'form-control',
                ])
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <?=
                $this->Form->control('address1', [
                    'label' => __('Address 1'),
                    'class' => 'form-control',
                ])
                ?>
            </div>
            <div class="col-sm-6">
                <?=
                $this->Form->control('address2', [
                    'label' => __('Address 2'),
                    'class' => 'form-control',
                ])
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <?=
                $this->Form->control('city', [
                    'label' => __('City'),
                    'class' => 'form-control',
                ])
                ?>
            </div>
            <div class="col-sm-6">
                <?=
                $this->Form->control('state', [
                    'label' => __('State'),
                    'class' => 'form-control',
                ])
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <?=
                $this->Form->control('zip', [
                    'label' => __('ZIP'),
                    'class' => 'form-control',
                ])
                ?>
            </div>
            <div class="col-sm-6">
                <?=
                $this->Form->control('country', [
                    'label' => __('Country'),
                    'options' => get_countries(),
                    'empty' => __('Choose'),
                    'class' => 'form-control',
                ]);
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <?=
                $this->Form->control('phone_number', [
                    'label' => __('Phone Number'),
                    'class' => 'form-control',
                ])
                ?>
            </div>
        </div>

        <legend><?= __('Withdrawal Info') ?></legend>

        <div class="row">
            <div class="col-sm-6">
                <?=
                $this->Form->control('withdrawal_method', [
                    'label' => __('Withdrawal Method'),
                    'options' => $withdrawal_methods = array_column_polyfill(get_withdrawal_methods(), 'name', 'id'),
                    'empty' => __('Choose'),
                    'class' => 'form-control',
                ]);
                ?>
            </div>
            <div class="col-sm-6">
                <table class="table table-hover table-striped">
                    <tr>
                        <th><?= __('Withdraw Method') ?></th>
                        <th><?= __('Minimum Withdrawal Amount') ?></th>
                    </tr>
                    <?php foreach (get_withdrawal_methods() as $method) : ?>
                        <tr>
                            <td><?= h($method['name']) ?></td>
                            <td><?= display_price_currency($method['amount']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>

        <?=
        $this->Form->control('withdrawal_account', [
            'label' => __('Withdrawal Account'),
            'class' => 'form-control',
            'type' => 'textarea',
            'required' => false,
        ])
        ?>

        <div class="help-block">
            <?php foreach (get_withdrawal_methods() as $method) : ?>
                <p><?= h($method['description']) ?></p>
            <?php endforeach; ?>
        </div>

        <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-primary btn-lg']); ?>

        <?= $this->Form->end() ?>

    </div>
</div>
