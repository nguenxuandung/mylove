<?php
/**
 * @var \App\View\AppView $this
 * @var mixed $options
 * @var array $settings
 */
$this->assign('title', __('Email Settings'));
$this->assign('description', '');
$this->assign('content_title', __('Email Settings'));
?>

<?= $this->Form->create($options, [
    'id' => 'email-settings',
    'onSubmit' => "save_settings.disabled=true; save_settings.innerHTML='" . __('Saving ...') . "'; return true;",
]); ?>

<div class="nav-tabs-custom">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active">
            <a href="#settings" aria-controls="settings" role="tab" data-toggle="tab"><?= __('Settings') ?></a>
        </li>
        <li role="presentation">
            <a href="#notifications" aria-controls="notifications" role="tab"
               data-toggle="tab"><?= __('Notifications') ?></a>
        </li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">
        <div role="tabpanel" id="settings" class="tab-pane fade in active">
            <p></p>

            <div class="row">
                <div class="col-sm-2"><?= __('Admin Email') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['admin_email']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'email',
                        'value' => $settings['admin_email']['value'],
                    ]);
                    ?>
                    <span
                        class="help-block"><?= __('The recipient email for the contact form and support requests.') ?></span>
                </div>
            </div>

            <legend><?= __("Sending Email Settings") ?></legend>

            <div class="row">
                <div class="col-sm-2"><?= __('From Email') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['email_from']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'email',
                        'value' => $settings['email_from']['value'],
                    ]);

                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Email From Name') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['email_from_name']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'text',
                        'value' => $settings['email_from_name']['value'],
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Email Method') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['email_method']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            'default' => __('PHP Mail Function'),
                            'smtp' => __('SMTP'),
                            'sendmail' => __('Sendmail'),
                            'mail2' => __('PHP Mail Function 2'),
                        ],
                        'value' => $settings['email_method']['value'],
                        'class' => 'form-control',
                    ]);

                    ?>
                </div>
            </div>

            <div class="row conditional" data-cond-option="Options[<?= $settings['email_method']['id'] ?>][value]"
                 data-cond-value="smtp">
                <div class="col-sm-2"><?= __('SMTP Connection Security') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['email_smtp_security']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            'none' => __('None'),
                            'ssl' => __('SSL'),
                            'tls' => __('TLS'),
                        ],
                        'value' => $settings['email_smtp_security']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row conditional" data-cond-option="Options[<?= $settings['email_method']['id'] ?>][value]"
                 data-cond-value="smtp">
                <div class="col-sm-2"><?= __('SMTP Outgoing Host') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['email_smtp_host']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'text',
                        'value' => $settings['email_smtp_host']['value'],
                    ]);
                    ?>
                </div>
            </div>

            <div class="row conditional" data-cond-option="Options[<?= $settings['email_method']['id'] ?>][value]"
                 data-cond-value="smtp">
                <div class="col-sm-2"><?= __('SMTP Outgoing Port') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['email_smtp_port']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'number',
                        'value' => $settings['email_smtp_port']['value'],
                    ]);
                    ?>
                    <span class="help-block">
                    <?= __('Port value depends on the Connection Security type you set above ' .
                        'None - port 25, SSL - port 465, TLS - port 587. these values maybe different between email providers. ') ?>
                </span>
                </div>
            </div>

            <div class="row conditional" data-cond-option="Options[<?= $settings['email_method']['id'] ?>][value]"
                 data-cond-value="smtp">
                <div class="col-sm-2"><?= __('SMTP Username') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['email_smtp_username']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'text',
                        'value' => $settings['email_smtp_username']['value'],
                        'autocomplete' => 'off',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row conditional" data-cond-option="Options[<?= $settings['email_method']['id'] ?>][value]"
                 data-cond-value="smtp">
                <div class="col-sm-2"><?= __('SMTP Password') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['email_smtp_password']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'password',
                        'value' => $settings['email_smtp_password']['value'],
                        'autocomplete' => 'off',
                    ]);
                    ?>
                </div>
            </div>
        </div>

        <div role="tabpanel" id="notifications" class="tab-pane fade in">
            <p></p>

            <legend><?= __("Admin Notifications") ?></legend>

            <div class="row">
                <div class="col-sm-2"><?= __('New User Registration') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['alert_admin_new_user_register']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            1 => __('Yes'),
                            0 => __('No'),
                        ],
                        'value' => $settings['alert_admin_new_user_register']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('New Withdrawal Request') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['alert_admin_new_withdrawal']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            1 => __('Yes'),
                            0 => __('No'),
                        ],
                        'value' => $settings['alert_admin_new_withdrawal']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('New Created Invoice') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['alert_admin_created_invoice']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            1 => __('Yes'),
                            0 => __('No'),
                        ],
                        'value' => $settings['alert_admin_created_invoice']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Paid Invoice') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['alert_admin_paid_invoice']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            1 => __('Yes'),
                            0 => __('No'),
                        ],
                        'value' => $settings['alert_admin_paid_invoice']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <legend><?= __("Member Notifications") ?></legend>

            <div class="row">
                <div class="col-sm-2"><?= __('Approved Withdrawal Request') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['alert_member_approved_withdraw']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            1 => __('Yes'),
                            0 => __('No'),
                        ],
                        'value' => $settings['alert_member_approved_withdraw']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Completed Withdrawal Request') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['alert_member_completed_withdraw']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            1 => __('Yes'),
                            0 => __('No'),
                        ],
                        'value' => $settings['alert_member_completed_withdraw']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Canceled Withdrawal Request') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['alert_member_canceled_withdraw']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            1 => __('Yes'),
                            0 => __('No'),
                        ],
                        'value' => $settings['alert_member_canceled_withdraw']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Returned Withdrawal Request') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['alert_member_returned_withdraw']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            1 => __('Yes'),
                            0 => __('No'),
                        ],
                        'value' => $settings['alert_member_returned_withdraw']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
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

