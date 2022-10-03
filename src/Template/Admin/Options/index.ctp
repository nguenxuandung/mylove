<?php
/**
 * @var \App\View\AppView $this
 * @var mixed $options
 * @var mixed $plans
 * @var array $settings
 */
?>
<?php
$this->assign('title', __('Settings'));
$this->assign('description', '');
$this->assign('content_title', __('Settings'));
?>

<?= $this->Form->create($options, [
    'id' => 'form-settings',
    'onSubmit' => "save_settings.disabled=true; save_settings.innerHTML='" . __('Saving ...') . "'; return true;",
]); ?>

<div class="nav-tabs-custom">

    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation"><a href="#general" aria-controls="general" role="tab"
                                   data-toggle="tab"><?= __('General') ?></a></li>
        <li role="presentation"><a href="#currency" aria-controls="currency" role="tab"
                                   data-toggle="tab"><?= __('Currency') ?></a></li>
        <li role="presentation"><a href="#language" aria-controls="language" role="tab"
                                   data-toggle="tab"><?= __('Language') ?></a></li>
        <li role="presentation"><a href="#design" aria-controls="design" role="tab"
                                   data-toggle="tab"><?= __('Design') ?></a></li>
        <li role="presentation"><a href="#links" aria-controls="links" role="tab"
                                   data-toggle="tab"><?= __('Links') ?></a></li>
        <li role="presentation"><a href="#earnings" aria-controls="earnings" role="tab"
                                   data-toggle="tab"><?= __('Earnings') ?></a></li>
        <li role="presentation"><a href="#users" aria-controls="users" role="tab"
                                   data-toggle="tab"><?= __('Users') ?></a></li>
        <li role="presentation"><a href="#integration" aria-controls="integration" role="tab"
                                   data-toggle="tab"><?= __('Integration') ?></a></li>
        <li role="presentation"><a href="#captcha" aria-controls="captcha" role="tab"
                                   data-toggle="tab"><?= __('Captcha') ?></a></li>
        <li role="presentation"><a href="#security" aria-controls="security" role="tab"
                                   data-toggle="tab"><?= __('Security') ?></a></li>
        <li role="presentation"><a href="#blog" aria-controls="blog" role="tab"
                                   data-toggle="tab"><?= __('Blog') ?></a></li>
        <li role="presentation"><a href="#social" aria-controls="Social Media" role="tab"
                                   data-toggle="tab"><?= __('Social Media') ?></a></li>
        <li role="presentation"><a href="#cronjob" aria-controls="Cron Job" role="tab"
                                   data-toggle="tab"><?= __('Cron Job') ?></a></li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" id="general" class="tab-pane fade in active">
            <p></p>
            <div class="row">
                <div class="col-sm-2"><?= __('Site Name') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['site_name']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'text',
                        'value' => $settings['site_name']['value'],
                    ]);
                    ?>
                    <span class="help-block"><?= __('This is your site name.') ?></span>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('SEO Site Meta Title') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['site_meta_title']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'text',
                        'value' => $settings['site_meta_title']['value'],
                    ]);
                    ?>
                    <span class="help-block"><?= __('This is your site meta title. The recommended length is 50-60 ' .
                            'characters.') ?></span>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('SEO Keywords') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['seo_keywords']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'textarea',
                        'value' => $settings['seo_keywords']['value'],
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Site Description') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['description']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'textarea',
                        'value' => $settings['description']['value'],
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Maintenance Mode') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['maintenance_mode']['id'] . '.value',
                        [
                            'label' => false,
                            'options' => [
                                1 => __('Enable'),
                                0 => __('Disable'),
                            ],
                            'value' => $settings['maintenance_mode']['value'],
                            'class' => 'form-control',
                        ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Maintenance Message') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['maintenance_message']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'textarea',
                        'value' => $settings['maintenance_message']['value'],
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Main Domain') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['main_domain']['id'] . '.value', [
                        'label' => false,
                        'placeholder' => env("HTTP_HOST", ""),
                        'required' => 'required',
                        'class' => 'form-control',
                        'type' => 'text',
                        'value' => $settings['main_domain']['value'],
                    ]);
                    ?>
                    <span class="help-block">
                        <?= __('Main domain used for all pages expect the short link page. Make sure to ' .
                            'remove the "http" or "https" and the trailing slash (/)!. Example: <b>domain.com</b>') ?>
                    </span>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Default Short URL Domain') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['default_short_domain']['id'] . '.value', [
                        'label' => false,
                        'placeholder' => __("Ex. domian.com"),
                        'class' => 'form-control',
                        'type' => 'text',
                        'value' => $settings['default_short_domain']['value'],
                    ]);
                    ?>
                    <span class="help-block">
                        <?= __('Add the default domain used for the short links. If it is empty, the main ' .
                            'domain will be used. Make sure to remove the "http" or "https" and the trailing slash ' .
                            '(/)!. Example: <b>domain.com</b>') ?>
                    </span>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Multi Domains') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['multi_domains']['id'] . '.value', [
                        'label' => false,
                        'placeholder' => 'domain1.com,domain2.com',
                        'class' => 'form-control',
                        'type' => 'textarea',
                        'value' => $settings['multi_domains']['value'],
                    ]);
                    ?>
                    <span class="help-block">
                        <?= __("Add the other domains(don't add the default short URL domain or the main " .
                            "domain) you want users to select between when short links. ex. " .
                            "<b>domain1.com,domain2.com</b> These domains should be parked/aliased to the main " .
                            "domain. Separate by comma, no spaces. Make sure to remove the 'http' or 'https' and " .
                            "the trailing slash (/)!") ?>
                    </span>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Prevent direct access to the multi domains') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['prevent_direct_access_multi_domains']['id'] . '.value',
                        [
                            'label' => false,
                            'options' => [
                                1 => __('Yes'),
                                0 => __('No'),
                            ],
                            'value' => $settings['prevent_direct_access_multi_domains']['value'],
                            'class' => 'form-control',
                        ]);
                    ?>
                    <span class="help-block">
                        <?= __("Display a warning message when directly access the multi domains.") ?>
                    </span>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Time Zone') ?></div>
                <div class="col-sm-10">
                    <?php
                    $DateTimeZone = \DateTimeZone::listIdentifiers(DateTimeZone::ALL);
                    echo $this->Form->control('Options.' . $settings['timezone']['id'] . '.value', [
                        'label' => false,
                        'options' => array_combine($DateTimeZone, $DateTimeZone),
                        'value' => $settings['timezone']['value'],
                        //'empty'   => __( 'Choose' ),
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Cache Administration Area Statistics') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['cache_admin_statistics']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            1 => __('Enable'),
                            0 => __('Disable'),
                        ],
                        'value' => $settings['cache_admin_statistics']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                    <span class="help-block">
                        <?= __("It's recommended to keep it disabled If you are starting new website. " .
                            "In the future, it is highly recommended to enable it.") ?>
                    </span>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Cache Member Area Statistics') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['cache_member_statistics']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            1 => __('Enable'),
                            0 => __('Disable'),
                        ],
                        'value' => $settings['cache_member_statistics']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                    <span class="help-block">
                        <?= __("It's recommended to keep it disabled If you are starting new website. " .
                            "In the future, it is highly recommended to enable it.") ?>
                    </span>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Cache Homepage Counters') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['cache_home_counters']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            1 => __('Enable'),
                            0 => __('Disable'),
                        ],
                        'value' => $settings['cache_home_counters']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                    <span class="help-block">
                        <?= __("It's recommended to keep it disabled If you are starting new website. " .
                            "In the future, it is highly recommended to enable it.") ?>
                    </span>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Display Cookie Notification Bar') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['cookie_notification_bar']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            1 => __('Yes'),
                            0 => __('No'),
                        ],
                        'value' => $settings['cookie_notification_bar']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Display Homepage Stats Counters') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['display_home_stats']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            1 => __('Yes'),
                            0 => __('No'),
                        ],
                        'value' => $settings['display_home_stats']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row conditional" data-cond-option="Options[<?= $settings['display_home_stats']['id'] ?>][value]"
                 data-cond-value="1">
                <div class="col-sm-2"><?= __('Fake Users Base') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['fake_users']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'number',
                        'value' => $settings['fake_users']['value'],
                    ]);
                    ?>
                </div>
            </div>

            <div class="row conditional" data-cond-option="Options[<?= $settings['display_home_stats']['id'] ?>][value]"
                 data-cond-value="1">
                <div class="col-sm-2"><?= __('Fake Links Base') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['fake_links']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'number',
                        'value' => $settings['fake_links']['value'],
                    ]);
                    ?>
                </div>
            </div>

            <div class="row conditional" data-cond-option="Options[<?= $settings['display_home_stats']['id'] ?>][value]"
                 data-cond-value="1">
                <div class="col-sm-2"><?= __('Fake Clicks base') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['fake_clicks']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'number',
                        'value' => $settings['fake_clicks']['value'],
                    ]);
                    ?>
                </div>
            </div>

        </div>

        <div role="tabpanel" id="currency" class="tab-pane fade in active">
            <p></p>
            <div class="row">
                <div class="col-sm-2"><?= __('Currency Code') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['currency_code']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'text',
                        'value' => $settings['currency_code']['value'],
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Currency Symbol') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['currency_symbol']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'text',
                        'value' => $settings['currency_symbol']['value'],
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Currency Position') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['currency_position']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            'before' => __('Before Price'),
                            'after' => __('After Price'),
                        ],
                        'value' => $settings['currency_position']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Price Number of Decimals') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['price_decimals']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'number',
                        'step' => 1,
                        'min' => 0,
                        'max' => 9,
                        'value' => $settings['price_decimals']['value'],
                    ]);
                    ?>
                </div>
            </div>
        </div>

        <div role="tabpanel" id="language" class="tab-pane fade in active">
            <p></p>

            <?php
            $locale = new \Cake\Filesystem\Folder(APP . 'Locale');
            $languages = $locale->subdirectories(null, false);
            ?>

            <div class="row">
                <div class="col-sm-2"><?= __('Default Language') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['language']['id'] . '.value', [
                        'label' => false,
                        'options' => array_combine($languages, $languages),
                        'value' => $settings['language']['value'],
                        //'empty'   => __( 'Choose' ),
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Site Languages') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['site_languages']['id'] . '.value', [
                        'label' => false,
                        'type' => 'select',
                        'multiple' => true,
                        'options' => array_combine($languages, $languages),
                        'value' => unserialize($settings['site_languages']['value']),
                        //'empty'   => __( 'Choose' ),
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Language Automatic Redirect') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['language_auto_redirect']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            1 => __('Yes'),
                            0 => __('No'),
                        ],
                        'value' => $settings['language_auto_redirect']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                    <span class="help-block">
                        <?= __("Automatically redirect the website visitors to browse the website based on " .
                            " their browser language if it is already available.") ?>
                    </span>
                </div>
            </div>

            <div class="row hidden">
                <div class="col-sm-2"><?= __('Language Direction') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['language_direction']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            'ltr' => __('LTR'),
                            'rtl' => __('RTL'),
                        ],
                        'value' => $settings['language_direction']['value'],
                        //'empty'   => __( 'Choose' ),
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>
        </div>

        <div role="tabpanel" id="design" class="tab-pane fade in active">
            <p></p>

            <?php
            $plugins_path = new \Cake\Filesystem\Folder(ROOT . '/plugins');
            $plugins = $plugins_path->subdirectories(null, false);
            $frontend_themes = $member_themes = $admin_themes = [];

            foreach ($plugins as $key => $value) {
                if (!(preg_match('/AdminTheme$/', $value) || preg_match('/MemberTheme$/', $value)) &&
                    preg_match('/Theme$/', $value)
                ) {
                    $frontend_themes[$value] = $value;
                } elseif (preg_match('/AdminTheme$/', $value)) {
                    $admin_themes[$value] = $value;
                } elseif (preg_match('/MemberTheme$/', $value)) {
                    $member_themes[$value] = $value;
                }
            }
            ?>

            <div class="row">
                <div class="col-sm-2"><?= __('Select Frontend Theme') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['theme']['id'] . '.value', [
                        'label' => false,
                        'options' => $frontend_themes,
                        'value' => $settings['theme']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-sm-2"><?= __('Select Member Area Theme') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['member_theme']['id'] . '.value', [
                        'label' => false,
                        'options' => $member_themes,
                        'value' => $settings['member_theme']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Select Member Area Default Theme Skin') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['member_adminlte_theme_skin']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            'skin-blue' => 'Blue',
                            'skin-blue-light' => 'Blue Light',
                            'skin-yellow' => 'Yellow',
                            'skin-yellow-light' => 'Yellow Light',
                            'skin-green' => 'Green',
                            'skin-green-light' => 'Green Light',
                            'skin-purple' => 'Purple',
                            'skin-purple-light' => 'Purple Light',
                            'skin-red' => 'Red',
                            'skin-red-light' => 'Red Light',
                            'skin-black' => 'Black',
                            'skin-black-light' => 'Black Light',
                        ],
                        'value' => $settings['member_adminlte_theme_skin']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-sm-2"><?= __('Select Admin Area Theme') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['admin_theme']['id'] . '.value', [
                        'label' => false,
                        'options' => $admin_themes,
                        'value' => $settings['admin_theme']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Select Admin Area Default Theme Skin') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['admin_adminlte_theme_skin']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            'skin-blue' => 'Blue',
                            'skin-blue-light' => 'Blue Light',
                            'skin-yellow' => 'Yellow',
                            'skin-yellow-light' => 'Yellow Light',
                            'skin-green' => 'Green',
                            'skin-green-light' => 'Green Light',
                            'skin-purple' => 'Purple',
                            'skin-purple-light' => 'Purple Light',
                            'skin-red' => 'Red',
                            'skin-red-light' => 'Red Light',
                            'skin-black' => 'Black',
                            'skin-black-light' => 'Black Light',
                        ],
                        'value' => $settings['admin_adminlte_theme_skin']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-sm-2"><?= __('Logo URL') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['logo_url']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'text',
                        'value' => $settings['logo_url']['value'],
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Logo URL - Alternative') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['logo_url_alt']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'text',
                        'value' => $settings['logo_url_alt']['value'],
                    ]);
                    ?>
                    <span class="help-block"><?= __('Alternative logo used on the login page') ?></span>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Favicon URL') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['favicon_url']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'text',
                        'value' => $settings['favicon_url']['value'],
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Combine & Minify CSS & JS files') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['combine_minify_css_js']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            0 => __('No'),
                            1 => __('Yes'),
                        ],
                        'value' => $settings['combine_minify_css_js']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Assets CDN URL') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['assets_cdn_url']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'text',
                        'value' => $settings['assets_cdn_url']['value'],
                    ]);
                    ?>
                </div>
            </div>
        </div>

        <div role="tabpanel" id="links" class="tab-pane fade in active">
            <p></p>

            <div class="row">
                <div class="col-sm-2"><?= __('Enable No Advert') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['enable_noadvert']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            'yes' => __('Yes'),
                            'no' => __('No'),
                        ],
                        'value' => $settings['enable_noadvert']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <legend><?= __("Default Advertisement Types") ?></legend>

            <div class="row">
                <div class="col-sm-2"><?= __('Anonymous Default Advert') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['anonymous_default_advert']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            '1' => __('Interstitial Advertisement'),
                            '2' => __('Ad Banner'),
                            '0' => __('No Advert'),
                        ],
                        'value' => $settings['anonymous_default_advert']['value'],
                        //'empty'   => __( 'Choose' ),
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Member Default Advert') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['member_default_advert']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            '1' => __('Interstitial Advertisement'),
                            '2' => __('Ad Banner'),
                            '0' => __('No Advert'),
                        ],
                        'value' => $settings['member_default_advert']['value'],
                        //'empty'   => __( 'Choose' ),
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <legend><?= __("Metadata Fetching") ?></legend>
            <p><?= __("When shortening a URL, the URL page is downloaded and title, description & image " .
                    "are fetched from this page. If you have performance issues you can disable this behaviour from the " .
                    "below options.") ?></p>

            <div class="row">
                <div class="col-sm-2"><?= __('Disable Metadata Fetching on Homepage') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['disable_meta_home']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            'yes' => __('Yes'),
                            'no' => __('No'),
                        ],
                        'value' => $settings['disable_meta_home']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Disable Metadata Fetching on Member Area') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['disable_meta_member']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            'yes' => __('Yes'),
                            'no' => __('No'),
                        ],
                        'value' => $settings['disable_meta_member']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Disable Metadata Fetching on API') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['disable_meta_api']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            'yes' => __('Yes'),
                            'no' => __('No'),
                        ],
                        'value' => $settings['disable_meta_api']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                    <span class="help-block"><?= __("This is applicable for Quick Tool, Mass Shrinker, " .
                            "Full Page Script & Developers API.") ?></span>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Display Short link content(title, description and image)') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['short_link_content']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            'yes' => __('Yes'),
                            'no' => __('No'),
                        ],
                        'value' => $settings['short_link_content']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                    <span class="help-block"><?= __("Useful if your ads are displayed based on page content.") ?></span>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-sm-2"><?= __('Make Link Info Available for Public') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['link_info_public']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            'yes' => __('Yes'),
                            'no' => __('No'),
                        ],
                        'value' => $settings['link_info_public']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Display Home URL Shortening Box') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['home_shortening']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            'yes' => __('Yes'),
                            'no' => __('No'),
                        ],
                        'value' => $settings['home_shortening']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Redirect Anonymous Users to Register') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['home_shortening_register']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            'yes' => __('Yes'),
                            'no' => __('No'),
                        ],
                        'value' => $settings['home_shortening_register']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Make Link Info Available for Members') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['link_info_member']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            'yes' => __('Yes'),
                            'no' => __('No'),
                        ],
                        'value' => $settings['link_info_member']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Mass Shrinker Limit') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['mass_shrinker_limit']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'number',
                        'value' => $settings['mass_shrinker_limit']['value'],
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Links Banned Words') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['links_banned_words']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'textarea',
                        'value' => $settings['links_banned_words']['value'],
                    ]);
                    ?>
                    <span class="help-block"><?= __('Disallow links with banned words from being shortened. ' .
                            'The System will check link title or description if they are contain the banned words. ' .
                            'Separate by comma, no spaces.') ?></span>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Disallowed Domains') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['disallowed_domains']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'textarea',
                        'value' => $settings['disallowed_domains']['value'],
                    ]);
                    ?>
                    <span class="help-block"><?= __('Disallow links with certain domains from being shortened. ' .
                            'Separate by comma, no spaces.') ?></span>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Reserved Aliases') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['reserved_aliases']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'textarea',
                        'value' => $settings['reserved_aliases']['value'],
                    ]);
                    ?>
                    <span class="help-block"><?= __('Disallow aliases from being used for short links. ' .
                            'Separate by comma, no spaces.') ?></span>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Alias Min. Length') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['alias_min_length']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'number',
                        'value' => $settings['alias_min_length']['value'],
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Alias Max. Length') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['alias_max_length']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'number',
                        'max' => 30,
                        'value' => $settings['alias_max_length']['value'],
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Add the short links to the sitemap') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['sitemap_shortlinks']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            1 => __('Yes'),
                            0 => __('No'),
                        ],
                        'value' => $settings['sitemap_shortlinks']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>
        </div>

        <div role="tabpanel" id="earnings" class="tab-pane fade in">
            <p></p>

            <div class="row">
                <div class="col-sm-2"><?= __('Earning Mode') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['earning_mode']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            'campaign' => __('Campaigns'),
                            'simple' => __('Simple'),
                        ],
                        'value' => $settings['earning_mode']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row conditional" data-cond-option="Options[<?= $settings['earning_mode']['id'] ?>][value]"
                 data-cond-value="campaign">
                <div class="col-sm-2"><?= __('Allow Members Creating Campaigns') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['enable_advertising']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            'yes' => __('Yes'),
                            'no' => __('No'),
                        ],
                        'value' => $settings['enable_advertising']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row conditional" data-cond-option="Options[<?= $settings['earning_mode']['id'] ?>][value]"
                 data-cond-value="campaign">
                <div class="col-sm-2"><?= __('Campaign minimum price amount') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['campaign_minimum_price']['id'] . '.value', [
                        'label' => false,
                        'type' => 'number',
                        'min' => 0,
                        'step' => 'any',
                        'value' => $settings['campaign_minimum_price']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row conditional" data-cond-option="Options[<?= $settings['earning_mode']['id'] ?>][value]"
                 data-cond-value="campaign">
                <div class="col-sm-2"><?= __('Enable Interstitial Advertisement') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['enable_interstitial']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            'yes' => __('Yes'),
                            'no' => __('No'),
                        ],
                        'value' => $settings['enable_interstitial']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row conditional" data-cond-option="Options[<?= $settings['earning_mode']['id'] ?>][value]"
                 data-cond-value="campaign">
                <div class="col-sm-2"><?= __('Enable Banner Advertisement') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['enable_banner']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            'yes' => __('Yes'),
                            'no' => __('No'),
                        ],
                        'value' => $settings['enable_banner']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row conditional" data-cond-option="Options[<?= $settings['earning_mode']['id'] ?>][value]"
                 data-cond-value="campaign">
                <div class="col-sm-2"><?= __('Enable Popup Advertisement') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['enable_popup']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            'yes' => __('Yes'),
                            'no' => __('No'),
                        ],
                        'value' => $settings['enable_popup']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Enable Publisher Earnings') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['enable_publisher_earnings']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            1 => __('Yes'),
                            0 => __('No'),
                        ],
                        'value' => $settings['enable_publisher_earnings']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row conditional" data-cond-option="Options[<?= $settings['earning_mode']['id'] ?>][value]"
                 data-cond-value="campaign">
                <div class="col-sm-2"><?= __('Unique Visitor Per') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['unique_visitor_per']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            'campaign' => __('Campaign'),
                            'all' => __('All Campaigns'),
                        ],
                        'value' => $settings['unique_visitor_per']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                    <span class="help-block">
                        <?= __('Campaign: Publishers will earn more based on number of Campaigns.') ?><br>
                        <?= __('All Campaigns: Publishers will earn less.') ?>
                    </span>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Paid Views Per Day') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['paid_views_day']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'number',
                        'min' => 1,
                        'step' => 1,
                        'value' => $settings['paid_views_day']['value'],
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Block earnings for specific referers') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['block_referers_domains']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'textarea',
                        'value' => $settings['block_referers_domains']['value'],
                    ]);
                    ?>
                    <span class="help-block"><?= __('Domains should be separated by comma, no spaces.') ?></span>
                </div>
            </div>

            <style>
                .proxy_service {
                    margin-bottom: 15px;
                }

                .proxy_service label {
                    display: block;
                }

                .proxy_service label span {
                    font-weight: normal;
                }
            </style>

            <div class="row proxy_service">
                <div class="col-sm-2"><?= __('Proxy/VPN Service Detection') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->radio('Options.' . $settings['proxy_service']['id'] . '.value',
                        [
                            'disabled' => __('Disable'),
                            'free' => __('Free') . ' - ' . '<span>' . __('Not recommended') . '</span>',
                            'isproxyip' => __('IsProxyIP.com') . ' - ' . '<span>' .
                                __('Detects Public Proxies, VPN, TOR, Hosting Data Centers, Web Proxies & Bad Search Engine Robots.') .
                                '</span>',
                        ],
                        [
                            'value' => $settings['proxy_service']['value'],
                            'escape' => false,
                        ]);
                    ?>
                </div>
            </div>

            <div class="row conditional" data-cond-option="Options[<?= $settings['proxy_service']['id'] ?>][value]"
                 data-cond-value="isproxyip">
                <div class="col-sm-2"><?= __('IsProxyIP API Key') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['isproxyip_key']['id'] . '.value', [
                        'label' => false,
                        'type' => 'text',
                        'value' => $settings['isproxyip_key']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                    <span class="help-block">
                        <?= __('To get an API key you need to register at') ?>
                        <a href="https://isproxyip.com/pricing" target="_blank">https://isproxyip.com/pricing</a>
                    </span>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Force Disable Adblock') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['force_disable_adblock']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            1 => __('Yes'),
                            0 => __('No'),
                        ],
                        'value' => $settings['force_disable_adblock']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Continue Pages number') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['continue_pages_number']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'number',
                        'value' => $settings['continue_pages_number']['value'],
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Counter Start Counting After') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['counter_start']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            'DOMContentLoaded' => __('Page loaded'),
                            'load' => __('Page fully loaded'),
                        ],
                        'value' => $settings['counter_start']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Store Only The Paid Clicks Statistics') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['store_only_paid_clicks_statistics']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            1 => __('Yes'),
                            0 => __('No'),
                        ],
                        'value' => $settings['store_only_paid_clicks_statistics']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Enable Referrals') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['enable_referrals']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            1 => __('Yes'),
                            0 => __('No'),
                        ],
                        'value' => $settings['enable_referrals']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row conditional" data-cond-option="Options[<?= $settings['enable_referrals']['id'] ?>][value]"
                 data-cond-value="1">
                <div class="col-sm-2"><?= __('Referral Banners Code') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['referral_banners_code']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'textarea',
                        'value' => $settings['referral_banners_code']['value'],
                    ]);
                    ?>
                    <span class="help-block"><?= __("Here you can add your referral banners html code. You " .
                            "can use [referral_link] as a placeholder for member referral link.") ?></span>
                </div>
            </div>
        </div>

        <div role="tabpanel" id="users" class="tab-pane fade in">
            <p></p>

            <div class="row">
                <div class="col-sm-2"><?= __('Close Registration') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['close_registration']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            1 => __('Yes'),
                            0 => __('No'),
                        ],
                        'value' => $settings['close_registration']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Enable Premium Membership') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['enable_premium_membership']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            1 => __('Yes'),
                            0 => __('No'),
                        ],
                        'value' => $settings['enable_premium_membership']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row conditional"
                 data-cond-option="Options[<?= $settings['enable_premium_membership']['id'] ?>][value]"
                 data-cond-value="1">
                <div class="col-sm-2"><?= __('Trial Membership Plan') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['trial_plan']['id'] . '.value', [
                        'label' => false,
                        'options' => $plans,
                        'empty' => __('None'),
                        'value' => $settings['trial_plan']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row conditional"
                 data-cond-option="Options[<?= $settings['enable_premium_membership']['id'] ?>][value]"
                 data-cond-value="1">
                <div class="col-sm-2"><?= __('Trial Membership Period') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['trial_plan_period']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            'm' => __('Month'),
                            'y' => __('Year'),
                        ],
                        'value' => $settings['trial_plan_period']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Signup Bonus') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['signup_bonus']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'number',
                        'min' => 0,
                        'step' => 'any',
                        'value' => $settings['signup_bonus']['value'],
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Enable Account Activation by Email') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['account_activate_email']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            'yes' => __('Yes'),
                            'no' => __('No'),
                        ],
                        'value' => $settings['account_activate_email']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Reserved Usernames') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['reserved_usernames']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'textarea',
                        'value' => $settings['reserved_usernames']['value'],
                    ]);
                    ?>
                    <span class="help-block"><?= __('Separate by comma, no spaces.') ?></span>
                </div>
            </div>
        </div>

        <div role="tabpanel" id="integration" class="tab-pane fade in">
            <p></p>

            <div class="row">
                <div class="col-sm-2"><?= __('Front Head Code') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['head_code']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'textarea',
                        'value' => $settings['head_code']['value'],
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Auth Head Code') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['auth_head_code']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'textarea',
                        'value' => $settings['auth_head_code']['value'],
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Member Head Code') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['member_head_code']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'textarea',
                        'value' => $settings['member_head_code']['value'],
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Admin Head Code') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['admin_head_code']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'textarea',
                        'value' => $settings['admin_head_code']['value'],
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Footer Code') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['footer_code']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'textarea',
                        'value' => $settings['footer_code']['value'],
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('After Body Tag Code') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['after_body_tag_code']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'textarea',
                        'value' => $settings['after_body_tag_code']['value'],
                    ]);
                    ?>
                </div>
            </div>

        </div>

        <div role="tabpanel" id="captcha" class="tab-pane fade in">
            <p></p>

            <div class="row">
                <div class="col-sm-2"><?= __('Enable Captcha') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['enable_captcha']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            'yes' => __('Yes'),
                            'no' => __('No'),
                        ],
                        'value' => $settings['enable_captcha']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Captcha Type') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['captcha_type']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            'securimage' => __('Simple Captcha(not recommended)'),
                            'recaptcha' => __('reCAPTCHA v2 Checkbox'),
                            'invisible-recaptcha' => __('reCAPTCHA v2 Invisible'),
                            'hcaptcha_checkbox' => __('hCaptcha Checkbox'),
                            'solvemedia' => __('Solve Media'),
                        ],
                        'value' => $settings['captcha_type']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="conditional" data-cond-option="Options[<?= $settings['captcha_type']['id'] ?>][value]"
                 data-cond-value="recaptcha">

                <legend><?= __('reCAPTCHA Settings') ?></legend>

                <div class="row">
                    <div class="col-sm-2"><?= __('reCAPTCHA Site key') ?></div>
                    <div class="col-sm-10">
                        <?=
                        $this->Form->control('Options.' . $settings['reCAPTCHA_site_key']['id'] . '.value', [
                            'label' => false,
                            'class' => 'form-control',
                            'type' => 'text',
                            'value' => $settings['reCAPTCHA_site_key']['value'],
                        ]);
                        ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-2"><?= __('reCAPTCHA Secret key') ?></div>
                    <div class="col-sm-10">
                        <?=
                        $this->Form->control('Options.' . $settings['reCAPTCHA_secret_key']['id'] . '.value', [
                            'label' => false,
                            'class' => 'form-control',
                            'type' => 'text',
                            'value' => $settings['reCAPTCHA_secret_key']['value'],
                        ]);
                        ?>
                    </div>
                </div>
            </div>

            <div class="conditional" data-cond-option="Options[<?= $settings['captcha_type']['id'] ?>][value]"
                 data-cond-value="invisible-recaptcha">

                <legend><?= __('Invisible reCAPTCHA Settings') ?></legend>

                <div class="row">
                    <div class="col-sm-2"><?= __('Invisible reCAPTCHA Site key') ?></div>
                    <div class="col-sm-10">
                        <?=
                        $this->Form->control('Options.' . $settings['invisible_reCAPTCHA_site_key']['id'] . '.value', [
                            'label' => false,
                            'class' => 'form-control',
                            'type' => 'text',
                            'value' => $settings['invisible_reCAPTCHA_site_key']['value'],
                        ]);
                        ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-2"><?= __('Invisible reCAPTCHA Secret key') ?></div>
                    <div class="col-sm-10">
                        <?=
                        $this->Form->control('Options.' . $settings['invisible_reCAPTCHA_secret_key']['id'] . '.value',
                            [
                                'label' => false,
                                'class' => 'form-control',
                                'type' => 'text',
                                'value' => $settings['invisible_reCAPTCHA_secret_key']['value'],
                            ]);
                        ?>
                    </div>
                </div>
            </div>

            <div class="conditional" data-cond-option="Options[<?= $settings['captcha_type']['id'] ?>][value]"
                 data-cond-value="hcaptcha_checkbox">

                <legend><?= __('hCaptcha Checkbox Settings') ?></legend>

                <div class="row">
                    <div class="col-sm-2"><?= __('hCaptcha Checkbox Site key') ?></div>
                    <div class="col-sm-10">
                        <?=
                        $this->Form->control('Options.' . $settings['hcaptcha_checkbox_site_key']['id'] . '.value', [
                            'label' => false,
                            'class' => 'form-control',
                            'type' => 'text',
                            'value' => $settings['hcaptcha_checkbox_site_key']['value'],
                        ]);
                        ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-2"><?= __('hCaptcha Checkbox Secret key') ?></div>
                    <div class="col-sm-10">
                        <?=
                        $this->Form->control('Options.' . $settings['hcaptcha_checkbox_secret_key']['id'] . '.value', [
                            'label' => false,
                            'class' => 'form-control',
                            'type' => 'text',
                            'value' => $settings['hcaptcha_checkbox_secret_key']['value'],
                        ]);
                        ?>
                    </div>
                </div>
            </div>

            <div class="conditional" data-cond-option="Options[<?= $settings['captcha_type']['id'] ?>][value]"
                 data-cond-value="solvemedia">

                <legend><?= __('Solve Media Settings') ?></legend>

                <div class="row">
                    <div class="col-sm-2"><?= __('Solve Media Challenge Key') ?></div>
                    <div class="col-sm-10">
                        <?=
                        $this->Form->control('Options.' . $settings['solvemedia_challenge_key']['id'] . '.value', [
                            'label' => false,
                            'class' => 'form-control',
                            'type' => 'text',
                            'value' => $settings['solvemedia_challenge_key']['value'],
                        ]);
                        ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-2"><?= __('Solve Media Verification Key') ?></div>
                    <div class="col-sm-10">
                        <?=
                        $this->Form->control('Options.' . $settings['solvemedia_verification_key']['id'] . '.value', [
                            'label' => false,
                            'class' => 'form-control',
                            'type' => 'text',
                            'value' => $settings['solvemedia_verification_key']['value'],
                        ]);
                        ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-2"><?= __('Solve Media Authentication Hash Key') ?></div>
                    <div class="col-sm-10">
                        <?=
                        $this->Form->control('Options.' . $settings['solvemedia_authentication_key']['id'] . '.value', [
                            'label' => false,
                            'class' => 'form-control',
                            'type' => 'text',
                            'value' => $settings['solvemedia_authentication_key']['value'],
                        ]);
                        ?>
                    </div>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-sm-2"><?= __('Enable on Home Anonymous Short Link Box') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['enable_captcha_shortlink_anonymous']['id'] . '.value',
                        [
                            'label' => false,
                            'options' => [
                                1 => __('Yes'),
                                0 => __('No'),
                            ],
                            'value' => $settings['enable_captcha_shortlink_anonymous']['value'],
                            'class' => 'form-control',
                        ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Enable on Short Links Page') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['enable_captcha_shortlink']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            'yes' => __('Yes'),
                            'no' => __('No'),
                        ],
                        'value' => $settings['enable_captcha_shortlink']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Enable on Signin Form') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['enable_captcha_signin']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            'yes' => __('Yes'),
                            'no' => __('No'),
                        ],
                        'value' => $settings['enable_captcha_signin']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Enable on Signup Form') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['enable_captcha_signup']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            'yes' => __('Yes'),
                            'no' => __('No'),
                        ],
                        'value' => $settings['enable_captcha_signup']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Enable on Forgot Password Form') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['enable_captcha_forgot_password']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            'yes' => __('Yes'),
                            'no' => __('No'),
                        ],
                        'value' => $settings['enable_captcha_forgot_password']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Enable on Contact Form') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['enable_captcha_contact']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            'yes' => __('Yes'),
                            'no' => __('No'),
                        ],
                        'value' => $settings['enable_captcha_contact']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

        </div>

        <div role="tabpanel" id="security" class="tab-pane fade in">
            <p></p>

            <div class="row">
                <div class="col-sm-2"><?= __('Enable SSL Integration') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['ssl_enable']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            1 => __('Yes'),
                            0 => __('No'),
                        ],
                        'value' => $settings['ssl_enable']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                    <span class="help-block">
                        <?= __('You should install SSL into your website before enable ' .
                            'SSL integration. For more information about SSL, please ask your hosting company.') ?>
                    </span>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Enable https for Short links') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['https_shortlinks']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            1 => __('Yes'),
                            0 => __('No'),
                        ],
                        'value' => $settings['https_shortlinks']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                    <span class="help-block">
                        <?= __('You should install SSL into your website before enable this option.') ?>
                    </span>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Google Safe Browsing API Key') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['google_safe_browsing_key']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'text',
                        'value' => $settings['google_safe_browsing_key']['value'],
                    ]);
                    ?>
                    <span class="help-block">
                        <?= __(
                            'You can get your key from <a href="{0}" target="_blank">here</a>.',
                            'https://developers.google.com/safe-browsing/v4/get-started'
                        ) ?>
                    </span>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('PhishTank API key') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['phishtank_key']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'text',
                        'value' => $settings['phishtank_key']['value'],
                    ]);
                    ?>
                    <span class="help-block"><?= __(
                            'You can get your key from <a href="{0}" target="_blank">here</a>.',
                            'https://www.phishtank.com/api_register.php'
                        ) ?></span>
                </div>
            </div>

        </div>

        <div role="tabpanel" id="blog" class="tab-pane fade in">
            <p></p>
            <div class="row">
                <div class="col-sm-2"><?= __('Enable Blog') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['blog_enable']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            1 => __('Yes'),
                            0 => __('No'),
                        ],
                        'value' => $settings['blog_enable']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Display Blog Post into Shortlink Page') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['display_blog_post_shortlink']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            'none' => __('None'),
                            'latest' => __('Latest Post'),
                            'random' => __('Random Post'),
                        ],
                        'value' => $settings['display_blog_post_shortlink']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Enable Comments') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['blog_comments_enable']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            1 => __('Yes'),
                            0 => __('No'),
                        ],
                        'value' => $settings['blog_comments_enable']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Disqus Shortname') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['disqus_shortname']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'text',
                        'value' => $settings['disqus_shortname']['value'],
                    ]);
                    ?>
                    <span class="help-block">
                        <?= __("To display comment box, you must create an account at " .
                            "Disqus website by signing up from <a href='https://disqus.com/profile/signup/' " .
                            "target='_blank'>here</a> then add your website their from <a href='https://" .
                            "disqus.com/admin/create/' target='_blank'>here</a> and get your shortname.") ?>
                    </span>
                </div>
            </div>


        </div>

        <div role="tabpanel" id="social" class="tab-pane fade in">
            <p></p>
            <div class="row">
                <div class="col-sm-2"><?= __('Facebook Page URL') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['facebook_url']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'url',
                        'value' => $settings['facebook_url']['value'],
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Twitter Profile URL') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['twitter_url']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'url',
                        'value' => $settings['twitter_url']['value'],
                    ]);
                    ?>
                </div>
            </div>

        </div>

        <div role="tabpanel" id="cronjob" class="tab-pane fade in">
            <?php
            $phpFinder = new \Symfony\Component\Process\PhpExecutableFinder();
            $phpPath = $phpFinder->find() ?: 'php';
            ?>
            <legend>
                <?= __('Schedule Cron Job') ?>
                <span style="font-size: 75%;">
                    <?php if (isScheduleCronRunning()) : ?>
                        <span class="badge"><?= __('running'); ?></span>
                    <?php else: ?>
                        <span class="badge"><?= __('not running'); ?></span>
                    <?php endif; ?>
                </span>

            </legend>
            <p><?= __('Run the following cron job every minute') ?> <code>* * * * * </code></p>
            <pre>
<?= $phpPath ?> -d 'register_argc_argv=on;' -d 'apc.enabled=0;' <?= ROOT ?>/bin/cake.php schedule >> /dev/null 2>&1</pre>

            <hr>

            <legend>
                <?= __('Queue Cron Job') ?>
                <span style="font-size: 75%; display: none;">
                    <?php if (isQueueCronRunning()) : ?>
                        <span class="badge"><?= __('running'); ?></span>
                    <?php else: ?>
                        <span class="badge"><?= __('not running'); ?></span>
                    <?php endif; ?>
                </span>
            </legend>
            <p><?= __('Run the following cron job every 10 minutes') ?> <code>*/10 * * * * </code></p>
            <pre>
<?= $phpPath ?> -d 'register_argc_argv=on;' -d 'apc.enabled=0;' <?= ROOT ?>/bin/cake.php queue runworker -q >> /dev/null 2>&1</pre>

            <legend><?= __('Queue Cron Tasks') ?></legend>

            <div class="alert alert-danger">
                <?= __("If you don't know what the following settings do exactly, please don't change it") ?>
            </div>

            <div class="row">
                <div
                    class="col-sm-2"><?= __('Delete links that did not receive any clicks in the last x months') ?></div>
                <div class="col-sm-10">
                    <div class="form-inline">
                        <?=
                        $this->Form->control('Options.' . $settings['delete_links_without_activity_months']['id'] . '.value',
                            [
                                'label' => false,
                                'class' => 'form-control',
                                'type' => 'number',
                                'min' => 0,
                                'step' => 1,
                                'value' => $settings['delete_links_without_activity_months']['value'],
                            ]);
                        ?>

                        <span><?= __('Months') ?> - </span>

                        <span><?= __('Also delete the stats associated with these links') ?></span>
                        <?=
                        $this->Form->control('Options.' . $settings['delete_links_without_activity_views']['id'] . '.value',
                            [
                                'label' => false,
                                'options' => [
                                    1 => __('Yes'),
                                    0 => __('No'),
                                ],
                                'value' => $settings['delete_links_without_activity_views']['value'],
                                'class' => 'form-control',
                            ]);
                        ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2">
                    <?= __('Delete pending users who registered but not activated their accounts in the last x months') ?>
                </div>
                <div class="col-sm-10">
                    <div class="form-inline">
                        <?=
                        $this->Form->control('Options.' . $settings['delete_pending_users_months']['id'] . '.value',
                            [
                                'label' => false,
                                'class' => 'form-control',
                                'type' => 'number',
                                'min' => 0,
                                'step' => 1,
                                'value' => $settings['delete_pending_users_months']['value'],
                            ]);
                        ?>
                        <span><?= __('Months') ?></span>
                    </div>
                </div>
            </div>


        </div>

    </div>

</div>

<?= $this->Form->button(__('Save'), ['name' => 'save_settings', 'class' => 'btn btn-primary']); ?>
<?= $this->Form->end(); ?>

<?php $this->start('scriptBottom'); ?>
<script>
    $('.conditional').conditionize();
</script>
<?php $this->end(); ?>
