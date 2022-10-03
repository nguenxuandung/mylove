<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Campaign[]|\Cake\Collection\CollectionInterface $campaigns
 */
?>
<?php
$this->assign('title', __('Manage Campaigns'));
$this->assign('description', '');
$this->assign('content_title', __('Manage Campaigns'));
?>

<div class="box box-solid">
    <div class="box-body">
        <?php
        // The base url is the url where we'll pass the filter parameters
        $base_url = ['controller' => 'Campaigns', 'action' => 'index'];

        echo $this->Form->create(null, [
            'url' => $base_url,
            'class' => 'form-inline'
        ]);
        ?>

        <?=
        $this->Form->control('Filter.id', [
            'label' => false,
            'class' => 'form-control',
            'type' => 'text',
            'placeholder' => __('Id')
        ]);
        ?>

        <?=
        $this->Form->control('Filter.user_id', [
            'label' => false,
            'class' => 'form-control',
            'type' => 'text',
            'placeholder' => __('User Id')
        ]);
        ?>

        <?=
        $this->Form->control('Filter.status', [
            'label' => false,
            'options' => campaign_statuses(),
            'empty' => __('Status'),
            'class' => 'form-control'
        ]);
        ?>

        <?=
        $this->Form->control('Filter.ad_type', [
            'label' => false,
            'options' => [
                '1' => __('Interstitial'),
                '2' => __('Banner'),
                '3' => __('Popup')
            ],
            'empty' => __('Campaign Type'),
            'class' => 'form-control'
        ]);
        ?>

        <?=
        $this->Form->control('Filter.name', [
            'label' => false,
            'class' => 'form-control',
            'type' => 'text',
            'placeholder' => __('Name')
        ]);
        ?>

        <?=
        $this->Form->control('Filter.other_fields', [
            'label' => false,
            'class' => 'form-control',
            'type' => 'text',
            'placeholder' => __('website title, url, banner name,..')
        ]);
        ?>

        <?= $this->Form->button(__('Filter'), ['class' => 'btn btn-default btn-sm']); ?>

        <?= $this->Html->link(__('Reset'), $base_url, ['class' => 'btn btn-link btn-sm']); ?>

        <?= $this->Form->end(); ?>

    </div>
</div>

<div class="box box-primary">
    <div class="box-body">

        <?php
        $ad_types = [
            1 => __('Interstitial'),
            2 => __('Banner'),
            3 => __('Popup')
        ];

        $campaign_type = [
            0 => __('Non-default'),
            1 => __('Default')
        ];
        ?>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead>
                <tr>
                    <th><?= $this->Paginator->sort('Campaigns.id', __('Reference')); ?></th>
                    <th><?= __('Username') ?></th>
                    <th><?= $this->Paginator->sort('Campaigns.ad_type', __('Campaign Type')); ?></th>
                    <th><?= $this->Paginator->sort('Campaigns.name', __('Name')); ?></th>
                    <th><?= $this->Paginator->sort('Campaigns.price', __('Price')); ?></th>
                    <th><?= __('Payment Method') ?></th>
                    <th><?= __('Visitors/Total') ?></th>
                    <th><?= $this->Paginator->sort('Campaigns.status', __('Status')); ?></th>
                    <th><?= $this->Paginator->sort('Campaigns.created', __('Created')); ?></th>
                    <th><?= __('Actions'); ?></th>
                </tr>
                </thead>
                <?php
                /**
                 * @var \Cake\ORM\ResultSet $campaigns
                 * @var \App\Model\Entity\Campaign $campaign
                 */
                ?>
                <?php
                $i = 1;
                ?>
                <?php foreach ($campaigns as $campaign) : ?>
                    <tr>
                        <td rowspan="2"><?= $this->Html->link($campaign->id,
                                ['action' => 'view', $campaign->id]); ?></td>
                        <td>
                            <?= $this->Html->link(
                                $campaign->user->username,
                                ['controller' => 'Users', 'action' => 'view', $campaign->user_id]
                            );
                            ?>
                        </td>
                        <td><?= $ad_types[$campaign->ad_type]; ?>
                            <span class="label label-default"><?= $campaign_type[$campaign->default_campaign] ?></span>
                        </td>
                        <td>
                            <?php echo $this->Html->link(
                                $campaign->name,
                                array('controller' => 'Campaigns', 'action' => 'view', $campaign->id)
                            );

                            ?>
                        </td>
                        <td><?= display_price_currency($campaign->price); ?></td>
                        <td><?= (isset(get_payment_methods()[$campaign->payment_method])) ?
                                get_payment_methods()[$campaign->payment_method] : $campaign->payment_method ?></td>
                        <td>
                            <?php
                            $views_total = ['views' => 0, 'total' => 0];
                            foreach ($campaign->campaign_items as $campaign_item) {
                                $views_total['views'] += $campaign_item->views;
                                $views_total['total'] += $campaign_item->purchase * 1000;
                            }

                            ?>
                            <?= $views_total['views'] ?>/<?= $views_total['total'] ?>
                        </td>
                        <td><?= campaign_statuses($campaign->status); ?></td>
                        <td><?= display_date_timezone($campaign->created); ?></td>
                        <td>
                            <button class="btn btn-primary" type="button" data-toggle="collapse"
                                    data-target="#collapse-<?= $i ?>"
                                    aria-expanded="false" aria-controls="collapseExample">
                                <i class="fa fa-gears"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="9" align="right" style="border: none; padding: 0;">
                            <div class="collapse" id="collapse-<?= $i ?>">
                                <?= $this->Html->link(
                                    __('View'),
                                    ['action' => 'view', $campaign->id],
                                    ['class' => 'btn btn-primary']
                                ); ?>

                                <?= $this->Html->link(
                                    __('Edit'),
                                    ['action' => 'edit', $campaign->id],
                                    ['class' => 'btn btn-info']
                                ); ?>

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

                                <?= $this->Form->postLink(
                                    __('Delete'),
                                    ['action' => 'delete', $campaign->id],
                                    ['confirm' => __('Are you sure?'), 'class' => 'btn btn-danger']
                                ); ?>

                                <?= $this->Form->postLink(
                                    __('Delete with related views'),
                                    ['action' => 'delete', $campaign->id, true],
                                    ['confirm' => __('Are you sure?'), 'class' => 'btn btn-danger']
                                ); ?>
                            </div>
                        </td>
                    </tr>
                    <?php
                    $i += 1;
                    ?>
                <?php endforeach; ?>
                <?php unset($campaign); ?>
            </table>
        </div>

    </div><!-- /.box-body -->
</div>

<ul class="pagination">
    <?php
    $this->Paginator->setTemplates([
        'ellipsis' => '<li><a href="javascript: void(0)">...</a></li>',
    ]);

    if ($this->Paginator->hasPrev()) {
        echo $this->Paginator->prev('«');
    }

    echo $this->Paginator->numbers([
        'modulus' => 4,
        'first' => 2,
        'last' => 2,
    ]);

    if ($this->Paginator->hasNext()) {
        echo $this->Paginator->next('»');
    }
    ?>
</ul>
