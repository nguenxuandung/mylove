<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Statistic $first_statistic
 * @var mixed $campaign_countries
 * @var mixed $campaign_earnings
 * @var mixed $campaign_referers
 */
$this->assign('title', __('Campaigns Report'));
$this->assign('description', '');
$this->assign('content_title', __('Campaigns Report'));
?>

<div class="box box-primary">
    <div class="box-body">
        <?php
        // The base url is the url where we'll pass the filter parameters
        $base_url = ['controller' => 'Reports', 'action' => 'campaigns'];

        echo $this->Form->create(null, [
            'url' => $base_url,
            'type' => 'get',
            'class' => 'form-inline',
        ]);
        ?>

        <?=
        $this->Form->control('Filter.user_id', [
            'label' => false,
            'placeholder' => __('User Id'),
            'class' => 'form-control',
            'type' => 'text',
            'value' => $this->request->getQuery('Filter.user_id', ''),
        ]);
        ?>

        <?=
        $this->Form->control('Filter.campaign_id', [
            'label' => false,
            'placeholder' => __('Campaign Id'),
            'class' => 'form-control',
            'type' => 'text',
            'value' => $this->request->getQuery('Filter.campaign_id', ''),
        ]);
        ?>

        <?php
        ?>

        <?=
        $this->Form->control('Filter.date_from', [
            'type' => 'date',
            'placeholder' => __('From'),
            'minYear' => $first_statistic->created->year,
            'maxYear' => date('Y'),
            'year' => [
                'class' => 'form-control',
            ],
            'month' => [
                'class' => 'form-control',
            ],
            'day' => [
                'class' => 'form-control',
            ],
            'empty' => true,
            'value' => $this->request->getQuery('Filter.date_from', ''),
        ]);
        ?>

        <?=
        $this->Form->control('Filter.date_to', [
            'type' => 'date',
            'placeholder' => __('To'),
            'minYear' => $first_statistic->created->year,
            'maxYear' => date('Y'),
            'year' => [
                'class' => 'form-control',
            ],
            'month' => [
                'class' => 'form-control',
            ],
            'day' => [
                'class' => 'form-control',
            ],
            'empty' => true,
            'value' => $this->request->getQuery('Filter.date_to', ''),
        ]);
        ?>

        <?= $this->Form->button(__('Filter'), ['class' => 'btn btn-default btn-sm']); ?>

        <?= $this->Html->link(__('Reset'), $base_url, ['class' => 'btn btn-link btn-sm']); ?>

        <?= $this->Form->end(); ?>

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
        <?php if (isset($campaign_earnings)) : ?>
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
        $countries = get_countries(true);
        $cam_countries = ['Others' => 'Others'] + $countries;
        ?>
        <div class="box box-solid box-primary">
            <div class="box-header with-border">
                <i class="fa fa-globe"></i>
                <h3 class="box-title"><?= __("Countries") ?></h3>
            </div>
            <div class="box-body" style="height: 300px; overflow: auto;">
                <?php if (isset($campaign_countries)) : ?>
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
                <?php if (isset($campaign_referers)) : ?>
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
