<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Campaign $campaign
 * @var mixed $campaign_countries
 * @var mixed $campaign_earnings
 * @var mixed $campaign_referers
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

            <?php if ($campaign->ad_type == 1 || $campaign->ad_type == 3) : ?>

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
        <?php if ('Pending Payment' == $campaign->status) : ?>
            <div class="text-center">
                <?= $this->Form->postLink(__('Pay Campaign'), ['action' => 'pay', $campaign->id],
                    ['confirm' => __('Are you sure?'), 'class' => 'btn btn-success btn-lg']); ?>
            </div>
        <?php endif; ?>
        <?php if ('Active' == $campaign->status) : ?>
            <?= $this->Form->postLink(__('Pause'), ['action' => 'pause', $campaign->id],
                ['confirm' => __('Are you sure?'), 'class' => 'btn btn-success']); ?>
        <?php endif; ?>
        <?php if ('Paused' == $campaign->status) : ?>
            <?= $this->Form->postLink(__('Resume'), ['action' => 'resume', $campaign->id],
                ['confirm' => __('Are you sure?'), 'class' => 'btn btn-success']); ?>
        <?php endif; ?>

        <?php
        //pr( $campaign_statistics );
        ?>
    </div><!-- /.box-body -->
</div>

<?php
$reasons = get_statistics_reasons();
?>
<div class="box box-solid box-success">
    <div class="box-header with-border">
        <i class="fa fa-bar-chart"></i>
        <h3 class="box-title"><?= __("Campaign Clicks Details") ?></h3>
    </div>
    <div class="box-body">
        <?php if (count($campaign_earnings) > 0) : ?>
            <table class="table table-hover table-striped">
                <thead>
                <tr>
                    <th><?= __('Click Type') ?></th>
                    <th><?= __('Count') ?></th>
                    <th><?= __('Publisher Earnings') ?></th>
                </tr>
                </thead>
                <?php foreach ($campaign_earnings as $campaign_earning): ?>
                    <tr>
                        <td><?= $reasons[$campaign_earning->reason] ?></td>
                        <td><?= $campaign_earning->count ?></td>
                        <td><?= display_price_currency($campaign_earning->earnings); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else : ?>
            <p><?= __("No available data.") ?></p>
        <?php endif; ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <?php
        $cam_countries = ['Others' => 'Others'] + $countries;
        ?>
        <div class="box box-solid box-primary">
            <div class="box-header with-border">
                <i class="fa fa-globe"></i>
                <h3 class="box-title"><?= __("Countries") ?></h3>
            </div>
            <div class="box-body" style="height: 300px; overflow: auto;">
                <?php if (count($campaign_countries)) : ?>
                    <table class="table table-hover table-striped">
                        <thead>
                        <tr>
                            <th><?= __('Country') ?></th>
                            <th><?= __('Count') ?></th>
                            <th><?= __('Publisher Earnings') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($campaign_countries as $campaign_country): ?>
                            <tr>
                                <td><?= $cam_countries[$campaign_country->country] ?></td>
                                <td><?= $campaign_country->count ?></td>
                                <td><?= display_price_currency($campaign_country->earnings); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else : ?>
                    <p><?= __("No available data.") ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="box box-solid box-primary">
            <div class="box-header with-border">
                <i class="fa fa-share"></i>
                <h3 class="box-title"><?= __("Referers") ?></h3>
            </div>
            <div class="box-body" style="height: 300px; overflow: auto;">
                <?php if (count($campaign_referers)) : ?>
                    <table class="table table-hover table-striped">
                        <thead>
                        <tr>
                            <th><?= __('Referer') ?></th>
                            <th><?= __('Count') ?></th>
                            <th><?= __('Publisher Earnings') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($campaign_referers as $campaign_referer): ?>
                            <tr>
                                <td><?= $campaign_referer->referer_domain ?></td>
                                <td><?= $campaign_referer->count ?></td>
                                <td><?= display_price_currency($campaign_referer->earnings); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else : ?>
                    <p><?= __("No available data.") ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
