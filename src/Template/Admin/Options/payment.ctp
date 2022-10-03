<?php
/**
 * @var \App\View\AppView $this
 * @var mixed $options
 * @var array $settings
 */
$this->assign('title', __('Payment Settings'));
$this->assign('description', '');
$this->assign('content_title', __('Payment Settings'));
?>

<div class="box box-primary">
    <div class="box-body">
        <?= $this->Form->create($options, [
            'id' => 'form-settings',
            'onSubmit' => "save_settings.disabled=true; save_settings.innerHTML='" . __('Saving ...') . "'; return true;",
        ]); ?>

        <legend><?= __('Wallet Settings') ?></legend>

        <p><?= __("Your users will be able to withdraw money to their wallet then use it to pay campaigns.") ?></p>

        <div class="row">
            <div class="col-sm-2"><?= __('Enable Wallet') ?></div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('Options.' . $settings['wallet_enable']['id'] . '.value', [
                    'label' => false,
                    'options' => [
                        0 => __('No'),
                        1 => __('Yes'),
                    ],
                    'value' => $settings['wallet_enable']['value'],
                    'class' => 'form-control',
                ]);
                ?>
            </div>
        </div>

        <legend><?= __('PayPal Settings') ?></legend>

        <span class="help-block"><?= __('For setup instructions click <a href="{0}" target="_blank">here</a>.',
                "https://mightyscripts.freshdesk.com/support/solutions/articles/5000738312-paypal-setup") ?></span>

        <div class="row">
            <div class="col-sm-2"><?= __('Enable PayPal') ?></div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('Options.' . $settings['paypal_enable']['id'] . '.value', [
                    'label' => false,
                    'options' => [
                        'no' => __('No'),
                        'yes' => __('Yes'),
                    ],
                    'value' => $settings['paypal_enable']['value'],
                    'class' => 'form-control',
                ]);
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2"><?= __('Payment Business Email') ?></div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('Options.' . $settings['paypal_email']['id'] . '.value', [
                    'label' => false,
                    'class' => 'form-control',
                    'type' => 'email',
                    'value' => $settings['paypal_email']['value'],
                    'autocomplete' => 'off',
                ]);
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2"><?= __('Enable PayPal Sandbox') ?></div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('Options.' . $settings['paypal_sandbox']['id'] . '.value', [
                    'label' => false,
                    'options' => [
                        'no' => __('No'),
                        'yes' => __('Yes'),
                    ],
                    'value' => $settings['paypal_sandbox']['value'],
                    'class' => 'form-control',
                ]);
                ?>
            </div>
        </div>

        <legend><?= __('Stripe Settings') ?></legend>

        <span class="help-block"><?= __('For setup instructions click <a href="{0}" target="_blank">here</a>.',
                "https://mightyscripts.freshdesk.com/support/solutions/articles/5000738313-stripe-setup") ?></span>
        <div class="row">
            <div class="col-sm-2"><?= __('Enable Stripe') ?></div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('Options.' . $settings['stripe_enable']['id'] . '.value', [
                    'label' => false,
                    'options' => [
                        0 => __('No'),
                        1 => __('Yes'),
                    ],
                    'value' => $settings['stripe_enable']['value'],
                    'class' => 'form-control',
                ]);
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2"><?= __('Stripe Secret Key') ?></div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('Options.' . $settings['stripe_secret_key']['id'] . '.value', [
                    'label' => false,
                    'class' => 'form-control',
                    'type' => 'text',
                    'value' => $settings['stripe_secret_key']['value'],
                    'autocomplete' => 'off',
                ]);
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2"><?= __('Stripe Publishable Key') ?></div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('Options.' . $settings['stripe_publishable_key']['id'] . '.value', [
                    'label' => false,
                    'class' => 'form-control',
                    'type' => 'text',
                    'value' => $settings['stripe_publishable_key']['value'],
                    'autocomplete' => 'off',
                ]);
                ?>
            </div>
        </div>

        <legend><?= __('Skrill Settings') ?></legend>

        <span class="help-block"><?= __('For setup instructions click <a href="{0}" target="_blank">here</a>.',
                "https://mightyscripts.freshdesk.com/support/solutions/articles/5000738315-skrill-setup") ?></span>

        <div class="row">
            <div class="col-sm-2"><?= __('Enable Skrill') ?></div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('Options.' . $settings['skrill_enable']['id'] . '.value', [
                    'label' => false,
                    'options' => [
                        0 => __('No'),
                        1 => __('Yes'),
                    ],
                    'value' => $settings['skrill_enable']['value'],
                    'class' => 'form-control',
                ]);
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2"><?= __('Skrill Merchant Email') ?></div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('Options.' . $settings['skrill_email']['id'] . '.value', [
                    'label' => false,
                    'class' => 'form-control',
                    'type' => 'email',
                    'value' => $settings['skrill_email']['value'],
                    'autocomplete' => 'off',
                ]);
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2"><?= __('Skrill Secret Word') ?></div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('Options.' . $settings['skrill_secret_word']['id'] . '.value', [
                    'label' => false,
                    'class' => 'form-control',
                    'type' => 'text',
                    'value' => $settings['skrill_secret_word']['value'],
                    'autocomplete' => 'off',
                ]);
                ?>
            </div>
        </div>

        <legend><?= __('Bitcoin Processor') ?></legend>
        <?=
        $this->Form->control('Options.' . $settings['bitcoin_processor']['id'] . '.value', [
            'label' => false,
            'options' => [
                'coinbase' => __('Coinbase'),
                'coinpayments' => __('CoinPayments'),
            ],
            'value' => $settings['bitcoin_processor']['value'],
            'class' => 'form-control',
        ]);
        ?>

        <div class="conditional" data-cond-option="Options[<?= $settings['bitcoin_processor']['id'] ?>][value]"
             data-cond-value="coinpayments">
            <legend><?= __('CoinPayments Settings') ?></legend>

            <span class="help-block"><?= __('For setup instructions click <a href="{0}" target="_blank">here</a>.',
                    "https://mightyscripts.freshdesk.com/support/solutions/articles/5000738316-coinpayments-setup") ?></span>

            <div class="row">
                <div class="col-sm-2"><?= __('Enable CoinPayments') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['coinpayments_enable']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            0 => __('No'),
                            1 => __('Yes'),
                        ],
                        'value' => $settings['coinpayments_enable']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('CoinPayments Public Key') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['coinpayments_public_key']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'text',
                        'value' => $settings['coinpayments_public_key']['value'],
                        'autocomplete' => 'off',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('CoinPayments Private Key') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['coinpayments_private_key']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'text',
                        'value' => $settings['coinpayments_private_key']['value'],
                        'autocomplete' => 'off',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('CoinPayments Merchant Id') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['coinpayments_merchant_id']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'text',
                        'value' => $settings['coinpayments_merchant_id']['value'],
                        'autocomplete' => 'off',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('CoinPayments IPN Secret') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['coinpayments_ipn_secret']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'text',
                        'value' => $settings['coinpayments_ipn_secret']['value'],
                        'autocomplete' => 'off',
                    ]);
                    ?>
                </div>
            </div>
        </div>

        <div class="conditional" data-cond-option="Options[<?= $settings['bitcoin_processor']['id'] ?>][value]"
             data-cond-value="coinbase">
            <legend><?= __('Coinbase Settings') ?></legend>

            <span class="help-block"><?= __('For setup instructions click <a href="{0}" target="_blank">here</a>.',
                    "https://mightyscripts.freshdesk.com/support/solutions/articles/5000738317-coinbase-settings") ?></span>

            <div class="row">
                <div class="col-sm-2"><?= __('Enable Coinbase') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['coinbase_enable']['id'] . '.value', [
                        'label' => false,
                        'options' => [
                            'no' => __('No'),
                            'yes' => __('Yes'),
                        ],
                        'value' => $settings['coinbase_enable']['value'],
                        'class' => 'form-control',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Coinbase API Key') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['coinbase_api_key']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'text',
                        'value' => $settings['coinbase_api_key']['value'],
                        'autocomplete' => 'off',
                    ]);
                    ?>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2"><?= __('Coinbase API Secret') ?></div>
                <div class="col-sm-10">
                    <?=
                    $this->Form->control('Options.' . $settings['coinbase_api_secret']['id'] . '.value', [
                        'label' => false,
                        'class' => 'form-control',
                        'type' => 'text',
                        'value' => $settings['coinbase_api_secret']['value'],
                        'autocomplete' => 'off',
                    ]);
                    ?>
                </div>
            </div>
        </div>

        <legend><?= __('Webmoney Settings') ?></legend>

        <span class="help-block"><?= __('For setup instructions click <a href="{0}" target="_blank">here</a>.',
                "https://mightyscripts.freshdesk.com/support/solutions/articles/5000738318-webmoney-settings") ?></span>

        <div class="row">
            <div class="col-sm-2"><?= __('Enable Webmoney') ?></div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('Options.' . $settings['webmoney_enable']['id'] . '.value', [
                    'label' => false,
                    'options' => [
                        'no' => __('No'),
                        'yes' => __('Yes'),
                    ],
                    'value' => $settings['webmoney_enable']['value'],
                    'class' => 'form-control',
                ]);
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2"><?= __('Webmoney Merchant Purse') ?></div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('Options.' . $settings['webmoney_merchant_purse']['id'] . '.value', [
                    'label' => false,
                    'class' => 'form-control',
                    'type' => 'text',
                    'value' => $settings['webmoney_merchant_purse']['value'],
                    'autocomplete' => 'off',
                ]);
                ?>
            </div>
        </div>

        <legend><?= __('Perfect Money Settings') ?></legend>

        <span class="help-block"><?= __('For setup instructions click <a href="{0}" target="_blank">here</a>.',
                "https://mightyscripts.freshdesk.com/support/solutions/articles/5000738319-perfect-money-settings") ?></span>

        <div class="row">
            <div class="col-sm-2"><?= __('Enable Perfect Money') ?></div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('Options.' . $settings['perfectmoney_enable']['id'] . '.value', [
                    'label' => false,
                    'options' => [
                        0 => __('No'),
                        1 => __('Yes'),
                    ],
                    'value' => $settings['perfectmoney_enable']['value'],
                    'class' => 'form-control',
                ]);
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2"><?= __('Perfect Money Payee Account') ?></div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('Options.' . $settings['perfectmoney_account']['id'] . '.value', [
                    'label' => false,
                    'class' => 'form-control',
                    'type' => 'text',
                    'value' => $settings['perfectmoney_account']['value'],
                    'autocomplete' => 'off',
                ]);
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2"><?= __('Perfect Money Alternate Passphrase') ?></div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('Options.' . $settings['perfectmoney_passphrase']['id'] . '.value', [
                    'label' => false,
                    'class' => 'form-control',
                    'type' => 'text',
                    'value' => $settings['perfectmoney_passphrase']['value'],
                    'autocomplete' => 'off',
                ]);
                ?>
            </div>
        </div>

        <legend><?= __('Payeer Settings') ?></legend>

        <span class="help-block"><?= __('For setup instructions click <a href="{0}" target="_blank">here</a>.',
                "https://mightyscripts.freshdesk.com/support/solutions/articles/5000738320-payeer-settings") ?></span>

        <div class="row">
            <div class="col-sm-2"><?= __('Enable Payeer') ?></div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('Options.' . $settings['payeer_enable']['id'] . '.value', [
                    'label' => false,
                    'options' => [
                        0 => __('No'),
                        1 => __('Yes'),
                    ],
                    'value' => $settings['payeer_enable']['value'],
                    'class' => 'form-control',
                ]);
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2"><?= __('Payeer Merchant Id') ?></div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('Options.' . $settings['payeer_merchant_id']['id'] . '.value', [
                    'label' => false,
                    'class' => 'form-control',
                    'type' => 'text',
                    'value' => $settings['payeer_merchant_id']['value'],
                    'autocomplete' => 'off',
                ]);
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2"><?= __('Payeer Secret Key') ?></div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('Options.' . $settings['payeer_secret_key']['id'] . '.value', [
                    'label' => false,
                    'class' => 'form-control',
                    'type' => 'text',
                    'value' => $settings['payeer_secret_key']['value'],
                    'autocomplete' => 'off',
                ]);
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2"><?= __('Payeer Encryption Key') ?></div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('Options.' . $settings['payeer_encryption_key']['id'] . '.value', [
                    'label' => false,
                    'class' => 'form-control',
                    'type' => 'text',
                    'value' => $settings['payeer_encryption_key']['value'],
                    'autocomplete' => 'off',
                ]);
                ?>
            </div>
        </div>

        <legend><?= __('Paystack Settings') ?></legend>

        <div class="row">
            <div class="col-sm-2"><?= __('Enable Paystack') ?></div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('Options.' . $settings['paystack_enable']['id'] . '.value', [
                    'label' => false,
                    'options' => [
                        0 => __('No'),
                        1 => __('Yes'),
                    ],
                    'value' => $settings['paystack_enable']['value'],
                    'class' => 'form-control',
                ]);
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2"><?= __('Paystack Secret Key') ?></div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('Options.' . $settings['paystack_secret_key']['id'] . '.value', [
                    'label' => false,
                    'class' => 'form-control',
                    'type' => 'text',
                    'value' => $settings['paystack_secret_key']['value'],
                    'autocomplete' => 'off',
                ]);
                ?>
            </div>
        </div>

        <legend><?= __('Paytm Settings') ?></legend>

        <div class="row">
            <div class="col-sm-2"><?= __('Enable Paytm') ?></div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('Options.' . $settings['paytm_enable']['id'] . '.value', [
                    'label' => false,
                    'options' => [
                        0 => __('No'),
                        1 => __('Yes'),
                    ],
                    'value' => $settings['paytm_enable']['value'],
                    'class' => 'form-control',
                ]);
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2"><?= __('Paytm Merchant Key') ?></div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('Options.' . $settings['paytm_merchant_key']['id'] . '.value', [
                    'label' => false,
                    'class' => 'form-control',
                    'type' => 'text',
                    'value' => $settings['paytm_merchant_key']['value'],
                    'autocomplete' => 'off',
                ]);
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2"><?= __('Paytm Merchant MID') ?></div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('Options.' . $settings['paytm_merchant_mid']['id'] . '.value', [
                    'label' => false,
                    'class' => 'form-control',
                    'type' => 'text',
                    'value' => $settings['paytm_merchant_mid']['value'],
                    'autocomplete' => 'off',
                ]);
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2"><?= __('Paytm Merchant Website') ?></div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('Options.' . $settings['paytm_merchant_website']['id'] . '.value', [
                    'label' => false,
                    'class' => 'form-control',
                    'type' => 'text',
                    'value' => $settings['paytm_merchant_website']['value'],
                    'autocomplete' => 'off',
                ]);
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2"><?= __('Paytm Industry Type') ?></div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('Options.' . $settings['paytm_industry_type']['id'] . '.value', [
                    'label' => false,
                    'class' => 'form-control',
                    'type' => 'text',
                    'value' => $settings['paytm_industry_type']['value'],
                    'autocomplete' => 'off',
                ]);
                ?>
            </div>
        </div>

        <legend><?= __('Bank Transfer Settings') ?></legend>

        <div class="row">
            <div class="col-sm-2"><?= __('Enable Bank Transfer') ?></div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('Options.' . $settings['banktransfer_enable']['id'] . '.value', [
                    'label' => false,
                    'options' => [
                        'no' => __('No'),
                        'yes' => __('Yes'),
                    ],
                    'value' => $settings['banktransfer_enable']['value'],
                    'class' => 'form-control',
                ]);
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-2"><?= __('Bank Transfer Instructions') ?></div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('Options.' . $settings['banktransfer_instructions']['id'] . '.value', [
                    'label' => false,
                    'class' => 'form-control',
                    'type' => 'textarea',
                    'value' => $settings['banktransfer_instructions']['value'],
                ]);
                ?>
                <span class="help-block"><?= __("You can use these placeholders [invoice_id], " .
                        "[invoice_amount], [invoice_description]") ?></span>
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
