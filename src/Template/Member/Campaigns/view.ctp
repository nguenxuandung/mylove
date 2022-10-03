<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Campaign $campaign
 */
$this->assign('title', __('Campaign #{0}', $campaign->id));
$this->assign('description', '');
$this->assign('content_title', __('Campaign #{0}', $campaign->id));
?>

<?php
$views_total = ['views' => 0, 'total' => 0];
foreach ($campaign->campaign_items as $campaign_item) {
    $views_total['views'] += $campaign_item->views;
    $views_total['total'] += $campaign_item->purchase * 1000;
}

$statuses = [
    1 => __('Active'),
    2 => __('Paused'),
    3 => __('Canceled'),
    4 => __('Finished'),
    5 => __('Under Review'),
    6 => __('Pending Payment'),
    7 => __('Invalid Payment'),
    8 => __('Refunded')
]

?>

<div class="box box-primary">
    <div class="box-body">

        <?php if (6 == $campaign->status) : ?>
            <div class="text-center">
                <?= $this->Form->postLink(
                    __('Pay Campaign'),
                    ['action' => 'pay', $campaign->id],
                    ['class' => 'btn btn-success btn-lg']
                ); ?></div>
        <?php endif; ?>

        <legend><?= __('Campaign Details') ?></legend>

        <table class="table table-hover table-striped">
            <tr>
                <td><?= __('Status') ?></td>
                <td><?= $statuses[$campaign->status] ?></td>
            </tr>
            <tr>
                <td><?= __('Campaign Name') ?></td>
                <td><?= h($campaign->name) ?></td>
            </tr>

            <?php if ($campaign->ad_type == 1) : ?>

                <tr>
                    <td><?= __('Website Title') ?></td>
                    <td><?= h($campaign->website_title) ?></td>
                </tr>
                <tr>
                    <td><?= __('Website URL') ?></td>
                    <td><?= h($campaign->website_url) ?></td>
                </tr>

            <?php endif; ?>

            <?php if ($campaign->ad_type == 2) : ?>

                <tr>
                    <td><?= __('Banner Name') ?></td>
                    <td><?= h($campaign->banner_name) ?></td>
                </tr>

                <tr>
                    <td><?= __('Banner Size') ?></td>
                    <td><?= h($campaign->banner_size) ?></td>
                </tr>

                <tr>
                    <td><?= __('Banner Code') ?></td>
                    <td><?= h($campaign->banner_code) ?></td>
                </tr>
            <?php endif; ?>

            <tr>
                <td><?= __('Price') ?></td>
                <td><?= display_price_currency($campaign->price); ?></td>
            </tr>
            <tr>
                <td><?= __('Payment Method') ?></td>
                <td><?= (isset(get_payment_methods()[$campaign->payment_method])) ?
                        get_payment_methods()[$campaign->payment_method] : $campaign->payment_method ?></td>
            </tr>
            <tr>
                <td><?= __('Visitors/Total') ?></td>
                <td><?= $views_total['views'] ?><?= __('/') ?><?= $views_total['total'] ?></td>
            </tr>
            <tr>
                <td><?= __('Created') ?></td>
                <td><?= display_date_timezone($campaign->created) ?></td>
            </tr>
        </table>

        <legend><?= __('Advertising Rates') ?></legend>

        <?php
        $countries = get_countries(true);

        ?>
        <table class="table table-hover table-striped">
            <thead>
            <tr>
                <th><?= __('Country') ?></th>
                <th><?= __('Price / 1,000') ?></th>
                <th><?= __('Purchase') ?></th>
                <th><?= __('Price') ?></th>
                <td><?= __('Visitors/Total') ?></td>
            </tr>
            </thead>
            <?php foreach ($campaign->campaign_items as $campaign_item) : ?>
                <tr>
                    <td><?= $countries[$campaign_item->country] ?></td>
                    <td><?= display_price_currency($campaign_item->advertiser_price); ?></td>
                    <td><?= $campaign_item->purchase ?></td>
                    <td><?= display_price_currency($campaign_item->purchase * $campaign_item->advertiser_price); ?></td>
                    <td><?= $campaign_item->views ?>/<?= $campaign_item->purchase * 1000 ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <?php if (6 == $campaign->status) : ?>
            <div class="text-center">
                <?= $this->Form->postLink(
                    __('Pay Campaign'),
                    ['action' => 'pay', $campaign->id],
                    ['class' => 'btn btn-success btn-lg']
                ); ?>
            </div>
        <?php endif; ?>
        <?php if (1 == $campaign->status) : ?>
            <?= $this->Form->postLink(
                __('Pause'),
                ['action' => 'pause', $campaign->id],
                ['confirm' => __('Are you sure?'), 'class' => 'btn btn-success']
            ); ?>
        <?php endif; ?>
        <?php if (2 == $campaign->status) : ?>
            <?= $this->Form->postLink(
                __('Resume'),
                ['action' => 'resume', $campaign->id],
                ['confirm' => __('Are you sure?'), 'class' => 'btn btn-success']
            ); ?>
        <?php endif; ?>

    </div><!-- /.box-body -->
</div>
