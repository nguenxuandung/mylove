<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Link[]|\Cake\Collection\CollectionInterface $links
 * @var \App\Model\Entity\Withdraw $withdraw
 * @var mixed $countries
 * @var mixed $ips
 * @var mixed $reasons
 * @var mixed $referrers
 */
$this->assign('title', __('Withdraw #{0}', $withdraw->id));
$this->assign('description', '');
$this->assign('content_title', __('Withdraw #{0}', $withdraw->id));
?>

<?php
$statuses = [
    1 => __('Approved'),
    2 => __('Pending'),
    3 => __('Complete'),
    4 => __('Cancelled'),
    5 => __('Returned'),
];

$withdrawal_methods = array_column_polyfill(get_withdrawal_methods(), 'name', 'id');
?>

<div class="box box-primary">
    <div class="box-body">
        <?php if (in_array($withdraw->status, [1, 2])) : ?>
            <?= $this->Form->postLink(
                __('Cancel'),
                ['action' => 'cancel', $withdraw->id],
                ['confirm' => __('Are you sure?'), 'class' => 'btn btn-danger']
            ); ?>
        <?php endif; ?>

        <?php if ($withdraw->status == 2) : ?>
            <?= $this->Form->postLink(
                __('Approve'),
                ['action' => 'approve', $withdraw->id],
                ['confirm' => __('Are you sure?'), 'class' => 'btn btn-success']
            ); ?>
        <?php endif; ?>

        <?php if ($withdraw->status == 1) : ?>
            <?= $this->Form->postLink(
                __('Complete'),
                ['action' => 'complete', $withdraw->id],
                ['confirm' => __('Are you sure?'), 'class' => 'btn btn-success']
            ); ?>
        <?php endif; ?>

        <table class="table table-hover table-striped">
            <tr>
                <td><?= __('ID') ?></td>
                <td><?= $withdraw->id ?></td>
            </tr>
            <tr>
                <td><?= __('Status') ?></td>
                <td><?= $statuses[$withdraw->status] ?></td>
            </tr>
            <tr>
                <td><?= __('Publisher Earnings') ?></td>
                <td><?= display_price_currency($withdraw->publisher_earnings); ?></td>
            </tr>
            <tr>
                <td><?= __('Referral Earnings') ?></td>
                <td><?= display_price_currency($withdraw->referral_earnings); ?></td>
            </tr>
            <tr>
                <td><?= __('Amount') ?></td>
                <td><?= display_price_currency($withdraw->amount) ?></td>
            </tr>
            <tr>
                <td><?= __('Withdrawal Method') ?></td>
                <td><?= (isset($withdrawal_methods[$withdraw->method])) ?
                        $withdrawal_methods[$withdraw->method] : $withdraw->method ?></td>
            </tr>
            <tr>
                <td><?= __('Withdrawal Account') ?></td>
                <td><?= nl2br(h($withdraw->account)); ?></td>
            </tr>
            <tr>
                <td><?= __('Username') ?></td>
                <td>
                    <?= $this->Html->link(
                        $withdraw->user->username,
                        ['controller' => 'Users', 'action' => 'view', $withdraw->user->id]
                    );
                    ?>
                </td>
            </tr>
            <tr>
                <td><?= __('Created Date') ?></td>
                <td><?= display_date_timezone($withdraw->created); ?></td>
            </tr>
        </table>
    </div><!-- /.box-body -->
</div>

<?php
$countries_list = get_countries(true) + ['Others' => 'Others'];
?>

<div class="box box-solid box-primary">
    <div class="box-header with-border">
        <i class="fa fa-list"></i>
        <h3 class="box-title"><?= __("Reasons") ?></h3>
    </div>
    <div class="box-body" style="height: 300px; overflow: auto;">
        <table class="table table-hover table-striped">
            <thead>
            <tr>
                <th><?= __('Reason') ?></th>
                <th><?= __('Count') ?></th>
            </tr>
            </thead>
            <?php foreach ($reasons as $reason) : ?>
                <tr>
                    <td><?= get_statistics_reasons()[$reason['reason']] ?></td>
                    <td><?= $reason['count'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

<div class="box box-solid box-primary">
    <div class="box-header with-border">
        <i class="fa fa-globe"></i>
        <h3 class="box-title"><?= __("Countries") ?></h3>
    </div>
    <div class="box-body" style="height: 300px; overflow: auto;">
        <table class="table table-hover table-striped">
            <thead>
            <tr>
                <th><?= __('Country') ?></th>
                <th><?= __('Count') ?></th>
                <th><?= __('Publisher Earnings') ?></th>
            </tr>
            </thead>
            <?php foreach ($countries as $country) : ?>
                <tr>
                    <td><?= $countries_list[$country['country']] ?></td>
                    <td><?= $country['count'] ?></td>
                    <td><?= display_price_currency($country['publisher_earnings']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

<div class="box box-solid box-primary">
    <div class="box-header with-border">
        <i class="fa fa-hand-pointer-o"></i>
        <h3 class="box-title"><?= __("IPs") ?></h3>
    </div>
    <div class="box-body" style="height: 300px; overflow: auto;">
        <table class="table table-hover table-striped">
            <thead>
            <tr>
                <th><?= __('IP') ?></th>
                <th><?= __('Count') ?></th>
                <th><?= __('Publisher Earnings') ?></th>
            </tr>
            </thead>
            <?php foreach ($ips as $ip) : ?>
                <tr>
                    <td><?= $ip['ip'] ?></td>
                    <td><?= $ip['count'] ?></td>
                    <td><?= display_price_currency($ip['publisher_earnings']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

<div class="box box-solid box-primary">
    <div class="box-header with-border">
        <i class="fa fa-share"></i>
        <h3 class="box-title"><?= __("Referrers") ?></h3>
    </div>
    <div class="box-body" style="height: 300px; overflow: auto;">
        <table class="table table-hover table-striped">
            <thead>
            <tr>
                <th><?= __('Domain') ?></th>
                <th><?= __('Count') ?></th>
                <th><?= __('Publisher Earnings') ?></th>
            </tr>
            </thead>
            <?php foreach ($referrers as $referrer) : ?>
                <tr>
                    <td><?= $referrer['referer_domain'] ?></td>
                    <td><?= $referrer['count'] ?></td>
                    <td><?= display_price_currency($referrer['publisher_earnings']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>

<div class="box box-solid box-primary">
    <div class="box-header with-border">
        <i class="fa fa-link"></i>
        <h3 class="box-title"><?= __("Links") ?></h3>
    </div>
    <div class="box-body" style="height: 300px; overflow: auto;">
        <table class="table table-hover table-striped">
            <thead>
            <tr>
                <th><?= __('Link') ?></th>
                <th><?= __('Count') ?></th>
                <th><?= __('Link Earnings') ?></th>
            </tr>
            </thead>
            <?php foreach ($links as $link) : ?>
                <?php
                $short_url = get_short_url($link['link']['alias'], $link['link']['domain']);

                $title = $link['link']['alias'];
                if (!empty($link['link']['title'])) {
                    $title = $link['link']['title'];
                }
                ?>
                <tr>
                    <td><a href="<?= $short_url ?>" target="_blank" rel="nofollow noopener noreferrer">
                            <span class="glyphicon glyphicon-link"></span> <?= h($title) ?></a></td>
                    <td><?= $link['count'] ?></td>
                    <td><?= display_price_currency($link['publisher_earnings']); ?></td>
                </tr>
            <?php endforeach; ?>
            <?php unset($link) ?>
        </table>
    </div>
</div>
