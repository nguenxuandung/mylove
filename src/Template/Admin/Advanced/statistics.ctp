<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Statistic[]|\Cake\Collection\CollectionInterface $statistics
 */
$this->assign('title', __('Statistics Table'));
$this->assign('description', '');
$this->assign('content_title', __('Statistics Table'));
?>

<div class="box box-solid">
    <div class="box-body">
        <?php
        $base_url = ['controller' => 'Advanced', 'action' => 'statistics'];

        echo $this->Form->create(null, [
            'url' => $base_url,
            'class' => 'form-inline',
        ]);
        ?>

        <?=
        $this->Form->control('Filter.user_id', [
            'label' => false,
            'class' => 'form-control',
            'type' => 'number',
            'min' => 1,
            'step' => 1,
            'placeholder' => __('User Id'),
        ]);
        ?>

        <?=
        $this->Form->control('Filter.reason', [
            'label' => false,
            'options' => get_statistics_reasons(),
            'empty' => __('Reason'),
            'class' => 'form-control',
        ]);
        ?>

        <?=
        $this->Form->control('Filter.link_id', [
            'label' => false,
            'class' => 'form-control',
            'type' => 'number',
            'min' => 1,
            'step' => 1,
            'placeholder' => __('Link Id'),
        ]);
        ?>

        <?=
        $this->Form->control('Filter.referral_id', [
            'label' => false,
            'class' => 'form-control',
            'type' => 'text',
            'placeholder' => __('Referral Id'),
        ]);
        ?>

        <?=
        $this->Form->control('Filter.ad_type', [
            'label' => false,
            'options' => get_allowed_ads(),
            'empty' => __('Ad Type'),
            'class' => 'form-control',
        ]);
        ?>

        <?=
        $this->Form->control('Filter.campaign_id', [
            'label' => false,
            'class' => 'form-control',
            'type' => 'number',
            'min' => 1,
            'step' => 1,
            'placeholder' => __('Campaign Id'),
        ]);
        ?>

        <?=
        $this->Form->control('Filter.ip', [
            'label' => false,
            'class' => 'form-control',
            'type' => 'text',
            'min' => 1,
            'step' => 1,
            'placeholder' => __('IP'),
        ]);
        ?>

        <?=
        $this->Form->control('Filter.country', [
            'label' => false,
            'class' => 'form-control',
            'type' => 'text',
            'placeholder' => __('Country'),
        ]);
        ?>

        <?= $this->Form->button(__('Filter'), ['class' => 'btn btn-default btn-sm']); ?>

        <?= $this->Html->link(__('Reset'), $base_url, ['class' => 'btn btn-link btn-sm']); ?>

        <?= $this->Form->end(); ?>

    </div>
</div>

<div class="box box-primary">
    <div class="box-body no-padding">
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <tr>
                    <th><?= __('Id') ?></th>
                    <th><?= __('Created') ?></th>
                    <th><?= __('Reason') ?></th>
                    <th><?= __('User') ?></th>
                    <th><?= __('Link Id') ?></th>
                    <th><?= __('Referral Id') ?></th>
                    <th><?= __('Ad Type') ?></th>
                    <th><?= __('Campaign Id') ?></th>
                    <th><?= __('Campaign User Id') ?></th>
                    <th><?= __('IP') ?></th>
                    <th><?= __('Country') ?></th>
                    <th><?= __('Owner Earn') ?></th>
                    <th><?= __('Publisher Earn') ?></th>
                    <th><?= __('Referral Earn') ?></th>
                    <th><?= __('Referer Domain') ?></th>
                </tr>
                <?php foreach ($statistics as $statistic) : ?>
                    <tr>
                        <td><?= $statistic->id ?></td>
                        <td><?= display_date_timezone($statistic->created) ?></td>
                        <td><?= isset(get_statistics_reasons()[$statistic->reason]) ? get_statistics_reasons()[$statistic->reason] : $statistic->reason ?></td>
                        <td><?= $statistic->user->username ?></td>
                        <td><?= $statistic->link_id ?></td>
                        <td><?= $statistic->referral_id ?></td>
                        <td><?= isset(get_allowed_ads()[$statistic->ad_type]) ? get_allowed_ads()[$statistic->ad_type] : $statistic->ad_type ?></td>
                        <td><?= $statistic->campaign_id ?></td>
                        <td><?= $statistic->campaign_user_id ?></td>
                        <td><?= $statistic->ip ?></td>
                        <td><?= isset(get_countries()[$statistic->country]) ? get_countries()[$statistic->country] : $statistic->country ?></td>
                        <td><?= $statistic->owner_earn ?></td>
                        <td><?= $statistic->publisher_earn ?></td>
                        <td><?= $statistic->referral_earn ?></td>
                        <td><?= $statistic->referer_domain ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
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
