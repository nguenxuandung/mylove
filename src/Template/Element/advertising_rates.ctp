<?php
/**
 * @var \App\View\AppView $this
 */
$lang = locale_get_default();
$countries = get_countries(true);
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lipis/flag-icon-css@3.3.0/css/flag-icon.min.css"/>

<div class="advertising-rates">

    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <?php if (get_option('enable_banner', 'yes') == 'yes') : ?>
            <li role="presentation">
                <a href="#ban-ner-ads" aria-controls="ban-ner-ads" role="tab" data-toggle="tab">
                    <?= __('Banner') ?>
                </a>
            </li>
        <?php endif; ?>
        <?php if (get_option('enable_interstitial', 'yes') == 'yes') : ?>
            <li role="presentation">
                <a href="#interstitial" aria-controls="interstitial" role="tab"
                   data-toggle="tab">
                    <?= __('Interstitial') ?>
                </a>
            </li>
        <?php endif; ?>
        <?php if (get_option('enable_popup', 'yes') == 'yes') : ?>
            <li role="presentation">
                <a href="#popup-ads" aria-controls="popup-ads" role="tab" data-toggle="tab">
                    <?= __('Popup') ?>
                </a>
            </li>
        <?php endif; ?>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <?php if (get_option('enable_banner', 'yes') == 'yes') : ?>
            <div role="tabpanel" class="tab-pane" id="ban-ner-ads">
                <table class="table table-hover table-striped">
                    <tr>
                        <th rowspan="2"><?= __('Package Description / Country') ?></th>
                        <th colspan="3"><?= __('Cost per 1000 Views') ?></th>
                    </tr>
                    <tr>
                        <td style="text-align: center; font-weight: bold">
                            <?= __("Desktop") ?>
                        </td>
                        <td style="text-align: center; font-weight: bold">
                            <?= __("Mobile / Tablet") ?>
                        </td>
                        <td style="text-align: center; font-weight: bold">
                            <?= __("Both") ?>
                        </td>
                    </tr>
                    <?php
                    $banner_price = get_option('banner_price'); ?>
                    <?php foreach ($banner_price as $key => $value) : ?>
                        <?php
                        if (empty($value[1]['advertiser']) &&
                            empty($value[2]['advertiser']) &&
                            empty($value[3]['advertiser'])
                        ) {
                            continue;
                        } ?>
                        <tr>
                            <td>
                                <span class="flag-icon flag-icon-<?= strtolower($key) ?>"></span>
                                <?= $countries[$key] ?>
                            </td>
                            <td style="text-align: center;">
                                <?= display_price_currency($value[2]['advertiser']) ?>
                            </td>
                            <td style="text-align: center;">
                                <?= display_price_currency($value[3]['advertiser']) ?>
                            </td>
                            <td style="text-align: center;">
                                <?= display_price_currency($value[1]['advertiser']) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        <?php endif; ?>
        <?php if (get_option('enable_interstitial', 'yes') == 'yes') : ?>
            <div role="tabpanel" class="tab-pane" id="interstitial">
                <table class="table table-hover table-striped">
                    <tr>
                        <th rowspan="2"><?= __('Package Description / Country') ?></th>
                        <th colspan="3"><?= __('Cost per 1000 Views') ?></th>
                    </tr>
                    <tr>
                        <td style="text-align: center; font-weight: bold">
                            <?= __("Desktop") ?>
                        </td>
                        <td style="text-align: center; font-weight: bold">
                            <?= __("Mobile / Tablet") ?>
                        </td>
                        <td style="text-align: center; font-weight: bold">
                            <?= __("Both") ?>
                        </td>
                    </tr>
                    <?php
                    $interstitial_price = get_option('interstitial_price', []); ?>
                    <?php foreach ($interstitial_price as $key => $value) : ?>
                        <?php
                        if (empty($value[1]['advertiser']) &&
                            empty($value[2]['advertiser']) &&
                            empty($value[3]['advertiser'])
                        ) {
                            continue;
                        } ?>
                        <tr>
                            <td>
                                <span class="flag-icon flag-icon-<?= strtolower($key) ?>"></span>
                                <?= $countries[$key] ?>
                            </td>
                            <td style="text-align: center;">
                                <?= display_price_currency($value[2]['advertiser']) ?>
                            </td>
                            <td style="text-align: center;">
                                <?= display_price_currency($value[3]['advertiser']) ?>
                            </td>
                            <td style="text-align: center;">
                                <?= display_price_currency($value[1]['advertiser']) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        <?php endif; ?>
        <?php if (get_option('enable_popup', 'yes') == 'yes') : ?>
            <div role="tabpanel" class="tab-pane" id="popup-ads">
                <table class="table table-hover table-striped">
                    <tr>
                        <th rowspan="2"><?= __('Package Description / Country') ?></th>
                        <th colspan="3"><?= __('Cost per 1000 Views') ?></th>
                    </tr>
                    <tr>
                        <td style="text-align: center; font-weight: bold">
                            <?= __("Desktop") ?>
                        </td>
                        <td style="text-align: center; font-weight: bold">
                            <?= __("Mobile / Tablet") ?>
                        </td>
                        <td style="text-align: center; font-weight: bold">
                            <?= __("Both") ?>
                        </td>
                    </tr>
                    <?php
                    $popup_price = get_option('popup_price'); ?>
                    <?php foreach ($popup_price as $key => $value) : ?>
                        <?php
                        if (empty($value[1]['advertiser']) &&
                            empty($value[2]['advertiser']) &&
                            empty($value[3]['advertiser'])
                        ) {
                            continue;
                        } ?>
                        <tr>
                            <td>
                                <span class="flag-icon flag-icon-<?= strtolower($key) ?>"></span>
                                <?= $countries[$key] ?>
                            </td>
                            <td style="text-align: center;">
                                <?= display_price_currency($value[2]['advertiser']) ?>
                            </td>
                            <td style="text-align: center;">
                                <?= display_price_currency($value[3]['advertiser']) ?>
                            </td>
                            <td style="text-align: center;">
                                <?= display_price_currency($value[1]['advertiser']) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        <?php endif; ?>
    </div>

</div>
