<?php
/**
 * @var \App\View\AppView $this
 * @var array $a
 * @var array $b
 */
$lang = locale_get_default();
$countries = get_countries(true);
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lipis/flag-icon-css@3.3.0/css/flag-icon.min.css"/>

<?php if (get_option('earning_mode') === 'simple') : ?>
    <div class="payout-rates">

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <?php if (get_option('enable_banner', 'yes') === 'yes') : ?>
                <li role="presentation">
                    <a href="#ban-ner-ads" aria-controls="ban-ner-ads" role="tab" data-toggle="tab">
                        <?= __('Banner') ?>
                    </a>
                </li>
            <?php endif; ?>
            <?php if (get_option('enable_interstitial', 'yes') === 'yes') : ?>
                <li role="presentation">
                    <a href="#interstitial" aria-controls="interstitial" role="tab" data-toggle="tab">
                        <?= __('Interstitial') ?>
                    </a>
                </li>
            <?php endif; ?>
            <?php if (get_option('enable_popup', 'yes') === 'yes') : ?>
                <li role="presentation">
                    <a href="#popup-ads" aria-controls="popup-ads" role="tab" data-toggle="tab">
                        <?= __('Popup') ?>
                    </a>
                </li>
            <?php endif; ?>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <?php if (get_option('enable_banner', 'yes') === 'yes') : ?>
                <div role="tabpanel" class="tab-pane" id="ban-ner-ads">
                    <table class="table table-hover table-striped">
                        <tr>
                            <th rowspan="2"><?= __('Package Description / Country') ?></th>
                            <th colspan="2"><?= __('Earnings per 1000 Views') ?></th>
                        </tr>
                        <tr>
                            <td style="text-align: center; font-weight: bold">
                                <?= __("Desktop") ?>
                            </td>
                            <td style="text-align: center; font-weight: bold">
                                <?= __("Mobile / Tablet") ?>
                            </td>
                        </tr>
                        <?php
                        $banner_price = get_option('payout_rates_banner', []);
                        uasort($banner_price, function ($a, $b) {
                            if (!isset($a[3]) || !isset($b[3])) {
                                return 0;
                            }
                            if ($a[3] === $b[3]) {
                                return 0;
                            }

                            return ($a[3] < $b[3]) ? 1 : -1;
                        });
                        ?>
                        <?php foreach ($banner_price as $key => $value) : ?>
                            <?php
                            if (empty($value[2]) && empty($value[3])) {
                                continue;
                            } ?>
                            <tr>
                                <td>
                                    <span class="flag-icon flag-icon-<?= strtolower($key) ?>"></span>
                                    <?= $countries[$key] ?>
                                </td>
                                <td style="text-align: center;">
                                    <?= display_price_currency($value[2]) ?>
                                </td>
                                <td style="text-align: center;">
                                    <?= display_price_currency($value[3]) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            <?php endif; ?>
            <?php if (get_option('enable_interstitial', 'yes') === 'yes') : ?>
                <div role="tabpanel" class="tab-pane" id="interstitial">
                    <table class="table table-hover table-striped">
                        <tr>
                            <th rowspan="2"><?= __('Package Description / Country') ?></th>
                            <th colspan="2"><?= __('Earnings per 1000 Views') ?></th>
                        </tr>
                        <tr>
                            <td style="text-align: center; font-weight: bold">
                                <?= __("Desktop") ?>
                            </td>
                            <td style="text-align: center; font-weight: bold">
                                <?= __("Mobile / Tablet") ?>
                            </td>
                        </tr>
                        <?php
                        $interstitial_price = get_option('payout_rates_interstitial', []);
                        uasort($interstitial_price, function ($a, $b) {
                            if (!isset($a[3]) || !isset($b[3])) {
                                return 0;
                            }
                            if ($a[3] === $b[3]) {
                                return 0;
                            }

                            return ($a[3] < $b[3]) ? 1 : -1;
                        });
                        ?>
                        <?php foreach ($interstitial_price as $key => $value) : ?>
                            <?php
                            if (empty($value[2]) && empty($value[3])) {
                                continue;
                            } ?>
                            <tr>
                                <td>
                                    <span class="flag-icon flag-icon-<?= strtolower($key) ?>"></span>
                                    <?= $countries[$key] ?>
                                </td>
                                <td style="text-align: center;">
                                    <?= display_price_currency($value[2]) ?>
                                </td>
                                <td style="text-align: center;">
                                    <?= display_price_currency($value[3]) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            <?php endif; ?>
            <?php if (get_option('enable_popup', 'yes') === 'yes') : ?>
                <div role="tabpanel" class="tab-pane" id="popup-ads">
                    <table class="table table-hover table-striped">
                        <tr>
                            <th rowspan="2"><?= __('Package Description / Country') ?></th>
                            <th colspan="2"><?= __('Earnings per 1000 Views') ?></th>
                        </tr>
                        <tr>
                            <td style="text-align: center; font-weight: bold">
                                <?= __("Desktop") ?>
                            </td>
                            <td style="text-align: center; font-weight: bold">
                                <?= __("Mobile / Tablet") ?>
                            </td>
                        </tr>
                        <?php
                        $popup_price = get_option('payout_rates_popup', []);
                        uasort($popup_price, function ($a, $b) {
                            if (!isset($a[3]) || !isset($b[3])) {
                                return 0;
                            }
                            if ($a[3] === $b[3]) {
                                return 0;
                            }

                            return ($a[3] < $b[3]) ? 1 : -1;
                        });
                        ?>
                        <?php foreach ($popup_price as $key => $value) : ?>
                            <?php
                            if (empty($value[2]) && empty($value[3])) {
                                continue;
                            } ?>
                            <tr>
                                <td>
                                    <span class="flag-icon flag-icon-<?= strtolower($key) ?>"></span>
                                    <?= $countries[$key] ?>
                                </td>
                                <td style="text-align: center;">
                                    <?= display_price_currency($value[2]) ?>
                                </td>
                                <td style="text-align: center;">
                                    <?= display_price_currency($value[3]) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            <?php endif; ?>
        </div>

    </div>
<?php endif; ?>

<?php if (get_option('earning_mode') === 'campaign') : ?>
    <div class="payout-rates">

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <?php if (get_option('enable_banner', 'yes') === 'yes') : ?>
                <li role="presentation">
                    <a href="#ban-ner-ads" aria-controls="ban-ner-ads" role="tab" data-toggle="tab">
                        <?= __('Banner') ?>
                    </a>
                </li>
            <?php endif; ?>
            <?php if (get_option('enable_interstitial', 'yes') === 'yes') : ?>
                <li role="presentation">
                    <a href="#interstitial" aria-controls="interstitial" role="tab" data-toggle="tab">
                        <?= __('Interstitial') ?>
                    </a>
                </li>
            <?php endif; ?>
            <?php if (get_option('enable_popup', 'yes') === 'yes') : ?>
                <li role="presentation">
                    <a href="#popup-ads" aria-controls="popup-ads" role="tab" data-toggle="tab">
                        <?= __('Popup') ?>
                    </a>
                </li>
            <?php endif; ?>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <?php if (get_option('enable_banner', 'yes') === 'yes') : ?>
                <div role="tabpanel" class="tab-pane" id="ban-ner-ads">
                    <table class="table table-hover table-striped">
                        <tr>
                            <th rowspan="2"><?= __('Package Description / Country') ?></th>
                            <th colspan="2"><?= __('Earnings per 1000 Views') ?></th>
                        </tr>
                        <tr>
                            <td style="text-align: center; font-weight: bold">
                                <?= __("Desktop") ?>
                            </td>
                            <td style="text-align: center; font-weight: bold">
                                <?= __("Mobile / Tablet") ?>
                            </td>
                        </tr>
                        <?php
                        $banner_price = get_option('banner_price');
                        uasort($banner_price, function ($a, $b) {
                            if (!isset($a[3]['publisher']) || !isset($b[3]['publisher'])) {
                                return 0;
                            }
                            if ($a[3]['publisher'] == $b[3]['publisher']) {
                                return 0;
                            }

                            return ($a[3]['publisher'] < $b[3]['publisher']) ? 1 : -1;
                        });
                        ?>
                        <?php foreach ($banner_price as $key => $value) : ?>
                            <?php
                            if (empty($value[2]['publisher']) &&
                                empty($value[3]['publisher'])
                            ) {
                                continue;
                            } ?>
                            <tr>
                                <td>
                                    <span class="flag-icon flag-icon-<?= strtolower($key) ?>"></span>
                                    <?= $countries[$key] ?>
                                </td>
                                <td style="text-align: center;">
                                    <?= display_price_currency($value[2]['publisher']) ?>
                                </td>
                                <td style="text-align: center;">
                                    <?= display_price_currency($value[3]['publisher']) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            <?php endif; ?>
            <?php if (get_option('enable_interstitial', 'yes') === 'yes') : ?>
                <div role="tabpanel" class="tab-pane" id="interstitial">
                    <table class="table table-hover table-striped">
                        <tr>
                            <th rowspan="2"><?= __('Package Description / Country') ?></th>
                            <th colspan="2"><?= __('Earnings per 1000 Views') ?></th>
                        </tr>
                        <tr>
                            <td style="text-align: center; font-weight: bold">
                                <?= __("Desktop") ?>
                            </td>
                            <td style="text-align: center; font-weight: bold">
                                <?= __("Mobile / Tablet") ?>
                            </td>
                        </tr>
                        <?php
                        $interstitial_price = get_option('interstitial_price', []);
                        uasort($interstitial_price, function ($a, $b) {
                            if (!isset($a[3]['publisher']) || !isset($b[3]['publisher'])) {
                                return 0;
                            }
                            if ($a[3]['publisher'] == $b[3]['publisher']) {
                                return 0;
                            }

                            return ($a[3]['publisher'] < $b[3]['publisher']) ? 1 : -1;
                        });
                        ?>
                        <?php foreach ($interstitial_price as $key => $value) : ?>
                            <?php
                            if (empty($value[2]['publisher']) &&
                                empty($value[3]['publisher'])
                            ) {
                                continue;
                            } ?>
                            <tr>
                                <td>
                                    <span class="flag-icon flag-icon-<?= strtolower($key) ?>"></span>
                                    <?= $countries[$key] ?>
                                </td>
                                <td style="text-align: center;">
                                    <?= display_price_currency($value[2]['publisher']) ?>
                                </td>
                                <td style="text-align: center;">
                                    <?= display_price_currency($value[3]['publisher']) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            <?php endif; ?>
            <?php if (get_option('enable_popup', 'yes') === 'yes') : ?>
                <div role="tabpanel" class="tab-pane" id="popup-ads">
                    <table class="table table-hover table-striped">
                        <tr>
                            <th rowspan="2"><?= __('Package Description / Country') ?></th>
                            <th colspan="2"><?= __('Earnings per 1000 Views') ?></th>
                        </tr>
                        <tr>
                            <td style="text-align: center; font-weight: bold">
                                <?= __("Desktop") ?>
                            </td>
                            <td style="text-align: center; font-weight: bold">
                                <?= __("Mobile / Tablet") ?>
                            </td>
                        </tr>
                        <?php
                        $popup_price = get_option('popup_price');
                        uasort($popup_price, function ($a, $b) {
                            if (!isset($a[3]['publisher']) || !isset($b[3]['publisher'])) {
                                return 0;
                            }
                            if ($a[3]['publisher'] == $b[3]['publisher']) {
                                return 0;
                            }

                            return ($a[3]['publisher'] < $b[3]['publisher']) ? 1 : -1;
                        });
                        ?>
                        <?php foreach ($popup_price as $key => $value) : ?>
                            <?php
                            if (empty($value[2]['publisher']) &&
                                empty($value[3]['publisher'])
                            ) {
                                continue;
                            } ?>
                            <tr>
                                <td>
                                    <span class="flag-icon flag-icon-<?= strtolower($key) ?>"></span>
                                    <?= $countries[$key] ?>
                                </td>
                                <td style="text-align: center;">
                                    <?= display_price_currency($value[2]['publisher']) ?>
                                </td>
                                <td style="text-align: center;">
                                    <?= display_price_currency($value[3]['publisher']) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            <?php endif; ?>
        </div>

    </div>
<?php endif; ?>
