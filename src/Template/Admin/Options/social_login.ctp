<?php
/**
 * @var \App\View\AppView $this
 * @var mixed $options
 * @var array $settings
 */
$this->assign('title', __('Social Login Settings'));
$this->assign('description', '');
$this->assign('content_title', __('Social Login Settings'));
?>

<div class="box box-primary">
    <div class="box-body">
        <?= $this->Form->create($options, [
            'id' => 'form-settings',
            'onSubmit' => "save_settings.disabled=true; save_settings.innerHTML='" . __('Saving ...') . "'; return true;"
        ]); ?>

        <legend><?= __("Facebook Settings") ?></legend>

        <span class="help-block"><?= __('You can find the setup instructions <a href="{0}" target="_blank">here</a>.',
                'https://mightyscripts.freshdesk.com/support/solutions/articles/5000721764-social-login-with-facebook') ?></span>

        <div class="row">
            <div class="col-sm-2"><?= __('Enable Facebook') ?></div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('Options.' . $settings['social_login_facebook']['id'] . '.value', [
                    'label' => false,
                    'options' => [
                        1 => __('Yes'),
                        0 => __('No')
                    ],
                    'value' => $settings['social_login_facebook']['value'],
                    'class' => 'form-control'
                ]);
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2"><?= __('App Id') ?></div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('Options.' . $settings['social_login_facebook_app_id']['id'] . '.value', [
                    'label' => false,
                    'class' => 'form-control',
                    'type' => 'text',
                    'value' => $settings['social_login_facebook_app_id']['value']
                ]);
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2"><?= __('App Secret') ?></div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('Options.' . $settings['social_login_facebook_app_secret']['id'] . '.value', [
                    'label' => false,
                    'class' => 'form-control',
                    'type' => 'text',
                    'value' => $settings['social_login_facebook_app_secret']['value']
                ]);
                ?>
            </div>
        </div>

        <legend><?= __("Twitter Settings") ?></legend>

        <span class="help-block"><?= __('You can find the setup instructions <a href="{0}" target="_blank">here</a>.',
                'https://mightyscripts.freshdesk.com/support/solutions/articles/5000721765-social-login-with-twitter') ?></span>

        <div class="row">
            <div class="col-sm-2"><?= __('Enable Twitter') ?></div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('Options.' . $settings['social_login_twitter']['id'] . '.value', [
                    'label' => false,
                    'options' => [
                        1 => __('Yes'),
                        0 => __('No')
                    ],
                    'value' => $settings['social_login_twitter']['value'],
                    'class' => 'form-control'
                ]);
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2"><?= __('Consumer Key (API Key)') ?></div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('Options.' . $settings['social_login_twitter_api_key']['id'] . '.value', [
                    'label' => false,
                    'class' => 'form-control',
                    'type' => 'text',
                    'value' => $settings['social_login_twitter_api_key']['value']
                ]);
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2"><?= __('Consumer Secret (API Secret)') ?></div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('Options.' . $settings['social_login_twitter_api_secret']['id'] . '.value', [
                    'label' => false,
                    'class' => 'form-control',
                    'type' => 'text',
                    'value' => $settings['social_login_twitter_api_secret']['value']
                ]);
                ?>
            </div>
        </div>

        <legend><?= __("Google Settings") ?></legend>

        <span class="help-block"><?= __('You can find the setup instructions <a href="{0}" target="_blank">here</a>.',
                'https://mightyscripts.freshdesk.com/support/solutions/articles/5000721767-social-login-with-google') ?></span>

        <div class="row">
            <div class="col-sm-2"><?= __('Enable Google') ?></div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('Options.' . $settings['social_login_google']['id'] . '.value', [
                    'label' => false,
                    'options' => [
                        1 => __('Yes'),
                        0 => __('No')
                    ],
                    'value' => $settings['social_login_google']['value'],
                    'class' => 'form-control'
                ]);
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2"><?= __('Client ID') ?></div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('Options.' . $settings['social_login_google_client_id']['id'] . '.value', [
                    'label' => false,
                    'class' => 'form-control',
                    'type' => 'text',
                    'value' => $settings['social_login_google_client_id']['value']
                ]);
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2"><?= __('Client Secret') ?></div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('Options.' . $settings['social_login_google_client_secret']['id'] . '.value', [
                    'label' => false,
                    'class' => 'form-control',
                    'type' => 'text',
                    'value' => $settings['social_login_google_client_secret']['value']
                ]);
                ?>
            </div>
        </div>

        <?= $this->Form->button(__('Save'), ['name' => 'save_settings', 'class' => 'btn btn-primary']); ?>
        <?= $this->Form->end(); ?>
    </div>
</div>
