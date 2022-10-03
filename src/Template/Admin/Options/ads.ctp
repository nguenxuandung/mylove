<?php
/**
 * @var \App\View\AppView $this
 * @var mixed $options
 * @var array $settings
 */
?>
<?php
$this->assign('title', __('Ads'));
$this->assign('description', '');
$this->assign('content_title', __('Ads'));
?>

<div class="box box-primary">
    <div class="box-body">

        <?= $this->Form->create($options, [
            'id' => 'form-settings',
            'onSubmit' => "save_settings.disabled=true; save_settings.innerHTML='" . __('Saving ...') . "'; return true;",
        ]); ?>

        <div class="row">
            <div class="col-sm-2"><?= __('Member Area Ad') ?></div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('Options.' . $settings['ad_member']['id'] . '.value', [
                    'label' => false,
                    'class' => 'form-control',
                    'type' => 'textarea',
                    'value' => $settings['ad_member']['value'],
                ]);
                ?>
            </div>
        </div>

        <h3 class="page-header"><?= __('Captcha Ads') ?></h3>

        <div class="row">
            <div class="col-sm-2"><?= __('Above Captcha Ad') ?></div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('Options.' . $settings['ad_captcha_above']['id'] . '.value', [
                    'label' => false,
                    'class' => 'form-control',
                    'type' => 'textarea',
                    'value' => $settings['ad_captcha_above']['value'],
                ]);
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2"><?= __('Below Captcha Ad') ?></div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('Options.' . $settings['ad_captcha_below']['id'] . '.value', [
                    'label' => false,
                    'class' => 'form-control',
                    'type' => 'textarea',
                    'value' => $settings['ad_captcha_below']['value'],
                ]);
                ?>
            </div>
        </div>

        <h3 class="page-header"><?= __('Interstitial Ads') ?></h3>

        <div class="row">
            <div class="col-sm-2">
                <?= __('Interstitial Page Ad Code') ?><br>
                <small class="help-block"><?= __('Recommended size is 468x60 or use a responsive banner ad code') ?></small>
            </div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('Options.' . $settings['interstitial_banner_ad']['id'] . '.value', [
                    'label' => false,
                    'class' => 'form-control',
                    'type' => 'textarea',
                    'value' => $settings['interstitial_banner_ad']['value'],
                ]);
                ?>
                <span class="help-block"><?= __('This ad will be displayed between logo and counter.') ?></span>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2"><?= __('Interstitial Ad URL') ?></div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('Options.' . $settings['interstitial_ad_url']['id'] . '.value', [
                    'label' => false,
                    'class' => 'form-control',
                    'type' => 'text',
                    'value' => $settings['interstitial_ad_url']['value'],
                ]);
                ?>
            </div>
        </div>

        <h3 class="page-header"><?= __('Popup Ads') ?></h3>

        <div class="row">
            <div class="col-sm-2"><?= __('Popup Ad URL') ?></div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('Options.' . $settings['popup_ad_url']['id'] . '.value', [
                    'label' => false,
                    'class' => 'form-control',
                    'type' => 'text',
                    'value' => $settings['popup_ad_url']['value'],
                ]);
                ?>
            </div>
        </div>

        <h3 class="page-header"><?= __('Banner Ads') ?></h3>

        <div class="row">
            <div class="col-sm-2"><?= __('Banner 728x90') ?></div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('Options.' . $settings['banner_728x90']['id'] . '.value', [
                    'label' => false,
                    'class' => 'form-control',
                    'type' => 'textarea',
                    'value' => $settings['banner_728x90']['value'],
                ]);
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2"><?= __('Banner 468x60') ?></div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('Options.' . $settings['banner_468x60']['id'] . '.value', [
                    'label' => false,
                    'class' => 'form-control',
                    'type' => 'textarea',
                    'value' => $settings['banner_468x60']['value'],
                ]);
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2"><?= __('Banner 336x280') ?></div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('Options.' . $settings['banner_336x280']['id'] . '.value', [
                    'label' => false,
                    'class' => 'form-control',
                    'type' => 'textarea',
                    'value' => $settings['banner_336x280']['value'],
                ]);
                ?>
            </div>
        </div>


        <?= $this->Form->button(__('Save'), ['name' => 'save_settings', 'class' => 'btn btn-primary']); ?>
        <?= $this->Form->end(); ?>

    </div>
</div>

<?php $this->start('scriptBottom'); ?>
<script>
  $('.conditional').conditionize();
</script>
<?php $this->end(); ?>
