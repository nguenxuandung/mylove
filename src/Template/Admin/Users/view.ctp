<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var mixed $pending_withdrawn
 * @var mixed $referrals
 * @var mixed $total_links
 * @var mixed $total_withdrawn
 */
$this->assign('title', __('View User #{0}', $user->id));
$this->assign('description', '');
$this->assign('content_title', __('View User #{0}', $user->id));
?>

<?php
$statuses = [
    1 => __('Active'),
    2 => __('Pending'),
    3 => __('Inactive'),
];

$withdrawal_methods = array_column_polyfill(get_withdrawal_methods(), 'name', 'id');
?>

<div class="row">
    <div class="col-sm-3">
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3><?= display_price_currency($user->publisher_earnings + $user->referral_earnings); ?></h3>
                <p><?= __('Available Balance') ?></p>
            </div>
            <div class="icon"><i class="fa fa-money"></i></div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3><?= $total_links ?></h3>
                <p><?= __('Total Links') ?></p>
            </div>
            <div class="icon"><i class="fa fa-link"></i></div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="small-box bg-green">
            <div class="inner">
                <h3><?= display_price_currency($pending_withdrawn); ?></h3>
                <p><?= __('Pending Withdrawn') ?></p>
            </div>
            <div class="icon"><i class="fa fa-share"></i></div>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3><?= display_price_currency($total_withdrawn); ?></h3>
                <p><?= __('Total Withdraw') ?></p>
            </div>
            <div class="icon"><i class="fa fa-usd"></i></div>
        </div>
    </div>
</div>

<div class="box box-primary">
    <div class="box-body">

        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingOne">
                    <h4 class="panel-title">
                        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne"
                           aria-expanded="true" aria-controls="collapseOne">
                            <?= __('Account Info.') ?>
                        </a>
                    </h4>
                </div>
                <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <tr>
                                    <td><?= __('Id') ?></td>
                                    <td><?= $user->id ?></td>
                                </tr>
                                <tr>
                                    <td><?= __('Status') ?></td>
                                    <td><?= $statuses[$user->status] ?></td>
                                </tr>
                                <tr>
                                    <td><?= __('Username') ?></td>
                                    <td><?= h($user->username) ?></td>
                                </tr>
                                <tr>
                                    <td><?= __('User Plan') ?></td>
                                    <td><?= h($user->plan->title) ?></td>
                                </tr>
                                <tr>
                                    <td><?= __('Plan Expiration Date') ?></td>
                                    <td><?= display_date_timezone($user->expiration) ?></td>
                                </tr>
                                <tr>
                                    <td><?= __('Role') ?></td>
                                    <td><?= h($user->role) ?></td>
                                </tr>
                                <tr>
                                    <td><?= __('Email') ?></td>
                                    <td><?= h($user->email) ?></td>
                                </tr>
                                <tr>
                                    <td><?= __('Temp Email') ?></td>
                                    <td><?= h($user->tempEmail) ?></td>
                                </tr>
                                <tr>
                                    <td><?= __('Api Token') ?></td>
                                    <td><?= h($user->api_token) ?></td>
                                </tr>
                                <tr>
                                    <td><?= __('Current Money Wallet') ?></td>
                                    <td><?= display_price_currency($user->wallet_money) ?></td>
                                </tr>
                                <tr>
                                    <td><?= __('Current Publisher Earnings') ?></td>
                                    <td><?= display_price_currency($user->publisher_earnings) ?></td>
                                </tr>
                                <tr>
                                    <td><?= __('Current Referral Earnings') ?></td>
                                    <td><?= display_price_currency($user->referral_earnings) ?></td>
                                </tr>
                                <tr>
                                    <td><?= __('Modified') ?></td>
                                    <td><?= display_date_timezone($user->modified) ?></td>
                                </tr>
                                <tr>
                                    <td><?= __('Created') ?></td>
                                    <td><?= display_date_timezone($user->created) ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingTwo">
                    <h4 class="panel-title">
                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion"
                           href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            <?= __('Withdrawal Info.') ?>
                        </a>
                    </h4>
                </div>
                <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <tr>
                                    <td><?= __('Withdrawal Method') ?></td>
                                    <td>
                                        <?=
                                        (isset($withdrawal_methods[$user->withdrawal_method])) ?
                                            h($withdrawal_methods[$user->withdrawal_method]) : h($user->withdrawal_method)
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?= __('Withdrawal Account') ?></td>
                                    <td><?= nl2br(h($user->withdrawal_account)) ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingThree">
                    <h4 class="panel-title">
                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion"
                           href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            <?= __('Billing Info.') ?>
                        </a>
                    </h4>
                </div>
                <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <tr>
                                    <td><?= __('First Name') ?></td>
                                    <td><?= h($user->first_name) ?></td>
                                </tr>
                                <tr>
                                    <td><?= __('Last Name') ?></td>
                                    <td><?= h($user->last_name) ?></td>
                                </tr>
                                <tr>
                                    <td><?= __('Address 1') ?></td>
                                    <td><?= h($user->address1) ?></td>
                                </tr>
                                <tr>
                                    <td><?= __('Address 2') ?></td>
                                    <td><?= h($user->address2) ?></td>
                                </tr>
                                <tr>
                                    <td><?= __('City') ?></td>
                                    <td><?= h($user->city) ?></td>
                                </tr>
                                <tr>
                                    <td><?= __('State') ?></td>
                                    <td><?= h($user->state) ?></td>
                                </tr>
                                <tr>
                                    <td><?= __('ZIP') ?></td>
                                    <td><?= h($user->zip) ?></td>
                                </tr>
                                <tr>
                                    <td><?= __('Country') ?></td>
                                    <td><?= h($user->country) ?></td>
                                </tr>
                                <tr>
                                    <td><?= __('Phone Number') ?></td>
                                    <td><?= h($user->phone_number) ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="heading4">
                    <h4 class="panel-title">
                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion"
                           href="#collapse4" aria-expanded="false" aria-controls="collapse4">
                            <?= __('Referrals') ?>
                        </a>
                    </h4>
                </div>
                <div id="collapse4" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading4">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <tr>
                                    <th><?= __('Username'); ?></th>
                                    <th><?= __('Date'); ?></th>
                                </tr>
                                <!-- Here is where we loop through our $posts array, printing out post info -->
                                <?php foreach ($referrals as $referral) : ?>
                                    <tr>
                                        <td><?= h($referral->username); ?></td>
                                        <td><?= display_date_timezone($referral->created) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php unset($referral); ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $user->id], ['class' => 'btn btn-primary']); ?>

        <?= $this->Html->link(
            __('Deactivate'),
            [
                'action' => 'deactivate',
                $user->id,
                'token' => $this->request->getParam('_csrfToken'),
            ],
            ['confirm' => __('Are you sure?'), 'class' => 'btn btn-danger']
        );
        ?>

    </div>
</div>
