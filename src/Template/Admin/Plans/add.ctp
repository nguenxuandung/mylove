<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Plan $plan
 */
$this->assign('title', __('Add Plan'));
$this->assign('description', '');
$this->assign('content_title', __('Add Plan'));
?>

<style>
    /* The switch - the box around the slider */
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }

    /* Hide default HTML checkbox */
    .switch input {
        display: none;
    }

    /* The slider */
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked + .slider {
        background-color: #2196F3;
    }

    input:focus + .slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked + .slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }
</style>

<div class="box box-primary">
    <div class="box-body">

        <?= $this->Form->create($plan); ?>

        <?=
        $this->Form->control('enable', [
            'type' => 'checkbox',
            'label' => __('Enable'),
        ]);
        ?>

        <?=
        $this->Form->control('hidden', [
            'type' => 'checkbox',
            'label' => __('Hidden'),
        ]);
        ?>
        <span class="help-block">
            <?= __('Only admins can see hidden plans and assign it to users but users will not see it at the member area.') ?>
        </span>

        <?=
        $this->Form->control('title', [
            'label' => __('Title'),
            'class' => 'form-control',
            'type' => 'text',
        ]);
        ?>

        <div class="row">
            <div class="col-sm-6">
                <?=
                $this->Form->control('monthly_price', [
                    'label' => __('Monthly Price'),
                    'class' => 'form-control',
                    'type' => 'text',
                ]);
                ?>
            </div>
            <div class="col-sm-6">
                <?=
                $this->Form->control('yearly_price', [
                    'label' => __('Yearly Price'),
                    'class' => 'form-control',
                    'type' => 'text',
                ]);
                ?>
            </div>
        </div>

        <?=
        $this->Form->control('description', [
            'label' => __('Description'),
            'class' => 'form-control text-editor',
            'type' => 'textarea',
        ]);
        ?>

        <div class="row">
            <div class="col-sm-6">
                <?=
                $this->Form->control('url_daily_limit', [
                    'label' => __('Maximum number of created shortened URLs per day'),
                    'class' => 'form-control',
                    'type' => 'number',
                    'step' => 1,
                    'min' => 0,
                ]);
                ?>
            </div>
            <div class="col-sm-6">
                <?=
                $this->Form->control('url_monthly_limit', [
                    'label' => __('Maximum number of created shortened URLs per month'),
                    'class' => 'form-control',
                    'type' => 'number',
                    'step' => 1,
                    'min' => 0,
                ]);
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-4">
                <?=
                $this->Form->control('views_hourly_limit', [
                    'label' => __('The maximum paid views the user can gain per hour'),
                    'class' => 'form-control',
                    'type' => 'number',
                    'step' => 1,
                    'min' => 0,
                ]);
                ?>
            </div>
            <div class="col-sm-4">
                <?=
                $this->Form->control('views_daily_limit', [
                    'label' => __('The maximum paid views the user can gain per day'),
                    'class' => 'form-control',
                    'type' => 'number',
                    'step' => 1,
                    'min' => 0,
                ]);
                ?>
            </div>
            <div class="col-sm-4">
                <?=
                $this->Form->control('views_monthly_limit', [
                    'label' => __('The maximum paid views the user can gain per month'),
                    'class' => 'form-control',
                    'type' => 'number',
                    'step' => 1,
                    'min' => 0,
                ]);
                ?>
            </div>
        </div>

        <?=
        $this->Form->control('cpm_fixed', [
            'label' => __('Fixed CPM'),
            'class' => 'form-control',
            'type' => 'number',
            'min' => 0,
            'step' => 'any',
        ]);
        ?>

        <table class="table table-hover table-striped">
            <tr>
                <td>
                    <span style="font-weight: bold;"><?= __('Timer') ?></span>
                    <span
                        class="help-block"><?= __("How many seconds for the countdown timer into the short link page?") ?></span>
                </td>
                <td>
                    <?=
                    $this->Form->control('timer', [
                        'label' => false,
                        'placeholder' => __('Enter the number of seconds'),
                        'class' => 'form-control',
                        'type' => 'number',
                        'step' => 1,
                        'min' => 0,
                    ]);
                    ?>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <h2>Short Link creation redirect types</h2>
                </td>
            </tr>
            <tr>
                <td>
                    <span style="font-weight: bold;"><?= __('Enable Direct Redirect') ?></span>
                </td>
                <td><label class="switch"><?= $this->Form->checkbox('direct_redirect', ['required' => false]); ?>
                        <span class="slider round"></span></label></td>
            </tr>
            <tr>
                <td>
                    <span style="font-weight: bold;"><?= __('Enable Banner Redirect') ?></span>
                </td>
                <td><label class="switch"><?= $this->Form->checkbox('banner_redirect', ['required' => false]); ?>
                        <span class="slider round"></span></label></td>
            </tr>
            <tr>
                <td>
                    <span style="font-weight: bold;"><?= __('Enable Interstitial Redirect') ?></span>
                </td>
                <td><label class="switch"><?= $this->Form->checkbox('interstitial_redirect', ['required' => false]); ?>
                        <span class="slider round"></span></label></td>
            </tr>
            <tr>
                <td>
                    <span style="font-weight: bold;"><?= __('Enable Random Redirect') ?></span>
                    <span class="help-block">
                        <?= __("You need to enable interstitial and banner to allow users to select random redirect type..") ?>
                    </span>
                </td>
                <td><label class="switch"><?= $this->Form->checkbox('random_redirect', ['required' => false]); ?>
                        <span class="slider round"></span></label></td>
            </tr>
            <tr>
                <td>
                    <span style="font-weight: bold;"><?= __('Edit Link') ?></span>
                    <span class="help-block"><?= __("Enabling this feature will allow short link creator " .
                            "to edit his links but without editing the long URL.") ?></span>
                </td>
                <td><label class="switch"><?= $this->Form->checkbox('edit_link', ['required' => false]); ?><span
                            class="slider round"></span></label></td>
            </tr>
            <tr>
                <td>
                    <span style="font-weight: bold;"><?= __('Edit Long URL') ?></span>
                    <span class="help-block">
                        <?= __("Enabling this feature will allow short link creator to edit the long URL " .
                            "for his links. You must enable 'Edit Link' feature to use this feature.") ?>
                    </span>
                </td>
                <td><label class="switch"><?= $this->Form->checkbox('edit_long_url', ['required' => false]); ?><span
                            class="slider round"></span></label></td>
            </tr>
            <tr>
                <td>
                    <span style="font-weight: bold;"><?= __('Custom Alias') ?></span>
                    <span class="help-block">
                        <?= __("Enabling this feature will allow short link creator to add a custom alias " .
                            "when shorten a url.") ?>
                    </span>
                </td>
                <td><label class="switch"><?= $this->Form->checkbox('alias', ['required' => false]); ?><span
                            class="slider round"></span></label></td>
            </tr>
            <tr>
                <td>
                    <span style="font-weight: bold;"><?= __('Short Link Expiration') ?></span>
                    <span class="help-block">
                        <?= __("Enabling this feature will allow short link creator to set an expiration date/time" .
                            "after that date, the short link will not be accessible.") ?>
                    </span>
                </td>
                <td><label class="switch"><?= $this->Form->checkbox('link_expiration', ['required' => false]); ?><span
                            class="slider round"></span></label></td>
            </tr>
            <tr>
                <td>
                    <span style="font-weight: bold;"><?= __('Multi Domains') ?></span>
                    <span class="help-block">
                        <?= __("Enabling this feature will allow short link creator to select a different " .
                            "domain for his links.") ?>
                    </span>
                </td>
                <td><label class="switch"><?= $this->Form->checkbox('multi_domains', ['required' => false]); ?><span
                            class="slider round"></span></label></td>
            </tr>
            <tr>
                <td>
                    <span
                        style="font-weight: bold;">* <?= __('Remove Ads from short link page and member area') ?></span>
                    <span class="help-block"><?= __("Enabling this feature will allow the logged in visitors " .
                            "who are on this plan not to show the ads on short link page and member area.") ?></span>
                </td>
                <td><label class="switch"><?= $this->Form->checkbox('disable_ads', ['required' => false]); ?><span
                            class="slider round"></span></label>
                </td>
            </tr>
            <tr>
                <td>
                    <span style="font-weight: bold;">* <?= __('Remove Captcha') ?></span>
                    <span class="help-block"><?= __("Enabling this feature will allow the logged in visitors " .
                            "to escape the captcha step and see the short link page directly.") ?></span>
                </td>
                <td><label class="switch"><?= $this->Form->checkbox('disable_captcha', ['required' => false]); ?><span
                            class="slider round"></span></label></td>
            </tr>
            <tr>
                <td>
                    <span style="font-weight: bold;"><?= __('Visitors Remove Captcha') ?></span>
                    <span class="help-block"><?= __("Enabling this feature will allow the short link visitors " .
                            "to escape the captcha step and see the short link page directly.") ?></span>
                </td>
                <td><label class="switch"><?= $this->Form->checkbox('visitors_remove_captcha',
                            ['required' => false]); ?><span
                            class="slider round"></span></label></td>
            </tr>
            <tr>
                <td>
                    <span style="font-weight: bold;">* <?= __('Onetime Captcha') ?></span>
                    <span class="help-block"><?= __("Onetime Captcha") ?></span>
                </td>
                <td><label class="switch"><?= $this->Form->checkbox('onetime_captcha', ['required' => false]); ?><span
                            class="slider round"></span></label></td>
            </tr>
            <tr>
                <td>
                    <span style="font-weight: bold;">* <?= __('Direct') ?></span>
                    <span class="help-block"><?= __("Enabling this feature will allow the logged in visitors " .
                            "to go to directly the long URL without seeing the short link page.") ?></span>
                </td>
                <td><label class="switch"><?= $this->Form->checkbox('direct', ['required' => false]); ?><span
                            class="slider round"></span></label></td>
            </tr>
            <tr>
                <td>
                    <span style="font-weight: bold;"><?= __('Referral Earnings') ?></span>
                    <span class="help-block">
                        <?= __("Enabling this feature will allow the publisher to earn from his referrals.") ?>
                    </span>
                </td>
                <td><label class="switch"><?= $this->Form->checkbox('referral', ['required' => false]); ?><span
                            class="slider round"></span></label></td>
            </tr>
            <tr>
                <td>
                    <span style="font-weight: bold;"><?= __('Referral Percentage') ?></span>
                    <span class="help-block">
                        <?= __("Enter the referral earning percentage. Ex. 20") ?>
                    </span>
                </td>
                <td>
                    <?=
                    $this->Form->control('referral_percentage', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'number',
                        'min' => 0,
                        'step' => 'any',
                        'required' => false,
                    ]);
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    <span style="font-weight: bold;"><?= __('Link Statistics') ?></span>
                    <span class="help-block">
                        <?= __("Enabling this feature will allow short link creator to view short link Statistics.") ?>
                    </span>
                </td>
                <td><label class="switch"><?= $this->Form->checkbox('stats', ['required' => false]); ?><span
                            class="slider round"></span></label></td>
            </tr>
            <tr>
                <td style="font-weight: bold;"><?= __('Quick Link Tool') ?></td>
                <td><label class="switch"><?= $this->Form->checkbox('api_quick', ['required' => false]); ?><span
                            class="slider round"></span></label></td>
            </tr>
            <tr>
                <td style="font-weight: bold;"><?= __('Mass Shrinker Tool') ?></td>
                <td><label class="switch"><?= $this->Form->checkbox('api_mass', ['required' => false]); ?><span
                            class="slider round"></span></label></td>
            </tr>
            <tr>
                <td style="font-weight: bold;"><?= __('Full Page Script Tool') ?></td>
                <td><label class="switch"><?= $this->Form->checkbox('api_full', ['required' => false]); ?><span
                            class="slider round"></span></label></td>
            </tr>
            <tr>
                <td style="font-weight: bold;"><?= __('Bookmarklet Tool') ?></td>
                <td><label class="switch"><?= $this->Form->checkbox('bookmarklet', ['required' => false]); ?><span
                            class="slider round"></span></label>
                </td>
            </tr>
            <tr>
                <td style="font-weight: bold;"><?= __('Developers API Tool') ?></td>
                <td><label class="switch"><?= $this->Form->checkbox('api_developer', ['required' => false]); ?><span
                            class="slider round"></span></label></td>
            </tr>
        </table>

        <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-primary']); ?>

        <?= $this->Form->end(); ?>

        <br>

        <p>* <?= __("This feature requires the visitor to the short link to be logged in then this feature " .
                "will take effect.") ?></p>
    </div>
</div>

<?php $this->start('scriptBottom'); ?>

<script src="//cdn.ckeditor.com/4.10.1/full/ckeditor.js"></script>
<script>
    $(document).ready(function() {
        CKEDITOR.replaceClass = 'text-editor';
        CKEDITOR.config.allowedContent = true;
        CKEDITOR.dtd.$removeEmpty['span'] = false;
        CKEDITOR.dtd.$removeEmpty['i'] = false;
    });
</script>

<?php $this->end(); ?>
