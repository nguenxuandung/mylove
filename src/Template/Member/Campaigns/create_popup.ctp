<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Campaign $campaign
 */
$this->assign('title', __('Create Popup Campaign'));
$this->assign('description', '');
$this->assign('content_title', __('Create Popup Campaign'));
?>

<div class="box box-primary">
    <div class="box-body">

        <?php if ($this->request->getQuery('traffic_source') &&
            in_array($this->request->getQuery('traffic_source'), [1, 2, 3])
        ) : ?>

            <?php
            $traffic_source = $this->request->getQuery('traffic_source');
            $popup_price = get_option('popup_price');
            $countries = get_countries(true);
            $i = 0;
            ?>

            <p><?= __('Please find our advertising rate table below. Each visitor you will purchase will ' .
                    'meet the following criteria:') ?></p>

            <ul>
                <li><?= __('Unique within a 24 hour time frame') ?></li>
                <li><?= __('They will have JavaScript enabled') ?></li>
                <li><?= __('They will have Cookies enabled') ?></li>
                <li>
                    <?= __('Must view your website for at least {0} seconds',
                        get_user_plan($this->request->getSession()->read('Auth.User.id'))->timer
                    ) ?>
                </li>
            </ul>

            <p><?= __('You may receive traffic that does not meet this criteria, but you will never be ' .
                    'charged for it.') ?></p>


            <?= $this->Form->create($campaign, ['id' => 'campaign-create']); ?>

            <?=
            $this->Form->hidden('traffic_source', ['value' => $traffic_source]);
            ?>

            <?=
            $this->Form->control('name', [
                'label' => __('Campaign Name'),
                'class' => 'form-control',
            ]);
            ?>

            <legend><?= __('Website Details') ?></legend>

            <?=
            $this->Form->control('website_title', [
                'label' => __('Title'),
                'class' => 'form-control',
            ]);
            ?>

            <?=
            $this->Form->control('website_url', [
                'label' => __('URL'),
                'class' => 'form-control',
                'type' => 'url',
            ]);
            ?>

            <legend><?= __('Advertising Rates') ?></legend>

            <table class="table table-hover table-striped">
                <thead>
                <tr>
                    <th><?= __('Country') ?></th>
                    <th><?= __('Price / 1,000') ?></th>
                    <th><?= __('Purchase') ?></th>
                </tr>
                </thead>
                <?php foreach ($popup_price as $key => $value) : ?>
                    <?php
                    if (empty($value[$traffic_source]['advertiser'])) {
                        continue;
                    }
                    ?>
                    <tr>
                        <td>
                            <?= $countries[$key] ?>
                        </td>
                        <td>
                            <?= display_price_currency($value[$traffic_source]['advertiser']); ?>
                        </td>
                        <td>
                            <?= $this->Form->hidden("campaign_items.$i.country", ['value' => $key]); ?>

                            <?=
                            $this->Form->control("campaign_items.$i.purchase", [
                                'label' => false,
                                'class' => 'form-control',
                                'type' => 'number',
                                'data-country' => $key,
                                'data-advertiser_price' => $value[$traffic_source]['advertiser'],
                                'data-publisher_price' => $value[$traffic_source]['publisher'],
                            ]);
                            ?>
                        </td>
                    </tr>
                    <?= (0 == $i % 2) ? '</div><div class="row">' : ''; ?>
                    <?php $i++ ?>
                <?php endforeach; ?>
            </table>

            <label>
                <?= $this->Form->checkbox('website_terms') ?>
                <?= __(
                    'I agree to the <a href="{0}" target="_blank">terms and conditions</a>',
                    $this->Url->build('/pages/terms')
                ) ?>
            </label>

            <div class="text-center">
                <p class="text-success" style="font-size: 23px;">
                    <?= __(
                        "You have ordered {0} visitors for a total of {1}",
                        "<span id='total-visitors'>0</span>",
                        get_option('currency_symbol', '$') . " <span id='total-price'>0.00</span>"
                    ) ?>
                </p>
                <?= $this->Form->button(__('Create Campaign'), ['class' => 'btn btn-success btn-lg']); ?>
            </div>

            <?= $this->Form->end(); ?>

        <?php else : ?>

            <?= $this->Form->create(null, ['type' => 'get']); ?>

            <?=
            $this->Form->control('traffic_source', [
                'label' => __('Traffic Sources/Devices'),
                'options' => [
                    '1' => __('Desktop, Mobile and Tablet'),
                    '2' => __('Desktop Only'),
                    '3' => __('Mobile / Tablet Only'),
                ],
                'empty' => __('Choose'),
                'class' => 'form-control',
            ]);
            ?>

            <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-primary btn-lg']); ?>

            <?= $this->Form->end(); ?>

        <?php endif; ?>

    </div><!-- /.box-body -->
</div>
