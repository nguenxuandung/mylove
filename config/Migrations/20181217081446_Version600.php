<?php

use Migrations\AbstractMigration;

class Version600 extends AbstractMigration
{
    public $autoId = false;

    public function up()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $table_prefix = $this->getAdapter()->getOption('table_prefix');

        $this->table('i18n')
            ->changeColumn('model', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => false,
            ])
            ->changeColumn('field', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => false,
            ])
            ->update();

        $interstitial_prices = unserialize($this->getOption('interstitial_price'));
        $payout_rates_interstitial = [];
        foreach ($interstitial_prices as $country => $interstitial_price) {
            $payout_rates_interstitial[$country][2] = (isset($interstitial_price[2]['publisher'])) ? $interstitial_price[2]['publisher'] : '';
            $payout_rates_interstitial[$country][3] = (isset($interstitial_price[3]['publisher'])) ? $interstitial_price[3]['publisher'] : '';
        }

        $banner_prices = unserialize($this->getOption('banner_price'));
        $payout_rates_banner = [];
        foreach ($banner_prices as $country => $banner_price) {
            $payout_rates_banner[$country][2] = (isset($banner_price[2]['publisher'])) ? $banner_price[2]['publisher'] : '';
            $payout_rates_banner[$country][3] = (isset($banner_price[3]['publisher'])) ? $banner_price[3]['publisher'] : '';
        }

        $popup_prices = unserialize($this->getOption('popup_price'));
        $payout_rates_popup = [];
        foreach ($popup_prices as $country => $popup_price) {
            $payout_rates_popup[$country][2] = (isset($popup_price[2]['publisher'])) ? $popup_price[2]['publisher'] : '';
            $payout_rates_popup[$country][3] = (isset($popup_price[3]['publisher'])) ? $popup_price[3]['publisher'] : '';
        }

        $rows = [
            [
                'name' => 'seo_keywords',
                'value' => 'earn money, short link, get paid',
            ],
            [
                'name' => 'paystack_enable',
                'value' => 0,
            ],
            [
                'name' => 'paystack_secret_key',
                'value' => '',
            ],
            [
                'name' => 'earning_mode',
                'value' => (is_app_installed()) ? 'campaign' : 'simple',
            ],
            [
                'name' => 'payout_rates_interstitial',
                'value' => serialize($payout_rates_interstitial),
            ],
            [
                'name' => 'payout_rates_banner',
                'value' => serialize($payout_rates_banner),
            ],
            [
                'name' => 'payout_rates_popup',
                'value' => serialize($payout_rates_popup),
            ],
            [
                'name' => 'interstitial_ad_url',
                'value' => 'https://example.com/',
            ],
            [
                'name' => 'popup_ad_url',
                'value' => 'https://example.com/',
            ],
            [
                'name' => 'ad_captcha_below',
                'value' => '',
            ],
            [
                'name' => 'sitemap_shortlinks',
                'value' => 0,
            ],
            [
                'name' => 'paytm_enable',
                'value' => 0,
            ],
            [
                'name' => 'paytm_merchant_key',
                'value' => '',
            ],
            [
                'name' => 'paytm_merchant_mid',
                'value' => '',
            ],
            [
                'name' => 'paytm_merchant_website',
                'value' => '',
            ],
            [
                'name' => 'paytm_industry_type',
                'value' => '',
            ],
            [
                'name' => 'enable_withdraw',
                'value' => 1,
            ],
            [
                'name' => 'withdraw_days',
                'value' => 4,
            ],
            [
                'name' => 'menu_main',
                'value' => '[{"id":"m_5cd6097425fa4","title":"Home","link":"\/","visibility":"all","class":""},{"id":"m_5cd609b7e9016","title":"Publisher Rates","link":"\/payout-rates","visibility":"all","class":""},{"id":"m_5cd609c082283","title":"Blog","link":"\/blog","visibility":"all","class":""},{"id":"m_5cd624080e582","title":"Login","link":"\/auth\/signin","visibility":"guest","class":""},{"id":"m_5cd6241af16f2","title":"Sign Up","link":"\/auth\/signup","visibility":"guest","class":""},{"id":"m_5cd623e85a645","title":"Dashboard","link":"\/member\/dashboard","visibility":"logged","class":""}]',
            ],
            [
                'name' => 'menu_short',
                'value' => '[{"id":"m_5cd6097425fa4","title":"Home","link":"\/","visibility":"all","class":""},{"id":"m_5cd609b7e9016","title":"Publisher Rates","link":"\/payout-rates","visibility":"all","class":""},{"id":"m_5cd609c082283","title":"Blog","link":"\/blog","visibility":"all","class":""},{"id":"m_5cd624080e582","title":"Login","link":"\/auth\/signin","visibility":"guest","class":""},{"id":"m_5cd6241af16f2","title":"Sign Up","link":"\/auth\/signup","visibility":"guest","class":""},{"id":"m_5cd623e85a645","title":"Dashboard","link":"\/member\/dashboard","visibility":"logged","class":""}]',
            ],
            [
                'name' => 'menu_footer',
                'value' => '[{"id":"m_5cd6cd1ee9c7a","title":"Privacy Policy","link":"\/pages\/privacy","visibility":"all","class":""},{"id":"m_5cd6cd304c063","title":"Terms of Use","link":"\/pages\/terms","visibility":"all","class":""}]',
            ],
            [
                'name' => 'withdraw_methods',
                'value' => json_encode($this->getWithdrawMethods()),
            ],
            [
                'name' => 'email_from_name',
                'value' => '',
            ],
            [
                'name' => 'maintenance_mode',
                'value' => 0,
            ],
            [
                'name' => 'maintenance_message',
                'value' => 'The website is currently down for maintenance. We\'ll be back shortly!',
            ],
            [
                'name' => 'campaign_minimum_price',
                'value' => '',
            ],
        ];

        $this->table('options')
            ->insert($rows)
            ->saveData();

        $this->table('remember_tokens')
            ->addColumn('id', 'biginteger', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 20,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('selector', 'string', [
                'default' => null,
                'limit' => 12,
                'null' => true,
                'collation' => 'utf8_bin',
            ])
            ->addColumn('token', 'string', [
                'default' => null,
                'limit' => 191,
                'null' => true,
            ])
            ->addColumn('user_id', 'biginteger', [
                'default' => null,
                'limit' => 20,
                'null' => true,
                'signed' => false,
            ])
            ->addColumn('expires', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex('selector', ['name' => 'idx_selector', 'unique' => true])
            ->addIndex('user_id', ['name' => 'idx_userid'])
            ->create();

        $this->table('plans')
            ->addColumn('cpm_fixed', 'decimal', [
                'after' => 'yearly_price',
                'default' => 0,
                'null' => false,
                'precision' => 50,
                'scale' => 9,
                'signed' => false,
            ])
            ->addColumn('link_expiration', 'boolean', [
                'after' => 'edit_long_url',
                'default' => 0,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('visitors_remove_captcha', 'boolean', [
                'after' => 'disable_captcha',
                'default' => 0,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('url_daily_limit', 'integer', [
                'after' => 'yearly_price',
                'default' => 0,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('url_monthly_limit', 'integer', [
                'after' => 'url_daily_limit',
                'default' => 0,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->update();

        $this->execute("DELETE FROM `{$table_prefix}options` WHERE `name` = 'coinhive_site_key';");
        $this->execute("DELETE FROM `{$table_prefix}options` WHERE `name` = 'coinhive_secret_key';");
        $this->execute("DELETE FROM `{$table_prefix}options` WHERE `name` = 'coinhive_hashes';");
        $this->execute("UPDATE `{$table_prefix}options` SET `name` = 'ad_captcha_above' WHERE `name` = 'ad_captcha';");
        $this->execute("UPDATE `{$table_prefix}options` SET `name` = 'interstitial_banner_ad' WHERE `name` = 'interstitial_ads';");
        $this->execute("DELETE FROM `{$table_prefix}options` WHERE `name` = 'paypal_withdrawal_enable';");
        $this->execute("DELETE FROM `{$table_prefix}options` WHERE `name` = 'paypal_withdrawal_amount';");
        $this->execute("DELETE FROM `{$table_prefix}options` WHERE `name` = 'payza_withdrawal_enable';");
        $this->execute("DELETE FROM `{$table_prefix}options` WHERE `name` = 'payza_withdrawal_amount';");
        $this->execute("DELETE FROM `{$table_prefix}options` WHERE `name` = 'skrill_withdrawal_enable';");
        $this->execute("DELETE FROM `{$table_prefix}options` WHERE `name` = 'skrill_withdrawal_amount';");
        $this->execute("DELETE FROM `{$table_prefix}options` WHERE `name` = 'bitcoin_withdrawal_enable';");
        $this->execute("DELETE FROM `{$table_prefix}options` WHERE `name` = 'bitcoin_withdrawal_amount';");
        $this->execute("DELETE FROM `{$table_prefix}options` WHERE `name` = 'webmoney_withdrawal_enable';");
        $this->execute("DELETE FROM `{$table_prefix}options` WHERE `name` = 'webmoney_withdrawal_amount';");
        $this->execute("DELETE FROM `{$table_prefix}options` WHERE `name` = 'perfectmoney_withdrawal_enable';");
        $this->execute("DELETE FROM `{$table_prefix}options` WHERE `name` = 'perfectmoney_withdrawal_amount';");
        $this->execute("DELETE FROM `{$table_prefix}options` WHERE `name` = 'payeer_withdrawal_enable';");
        $this->execute("DELETE FROM `{$table_prefix}options` WHERE `name` = 'payeer_withdrawal_amount';");
        $this->execute("DELETE FROM `{$table_prefix}options` WHERE `name` = 'custom_withdrawal_methods';");

        $this->table('links')
            ->addColumn('url_hash', 'string', [
                'after' => 'url',
                'default' => null,
                'limit' => 40,
                'null' => true,
            ])
            ->addColumn('expiration', 'datetime', [
                'after' => 'method',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->removeIndexByName('idx_alias_status')
            ->removeIndexByName('idx_userid_status_adtype')
            ->update();

        $this->execute('ALTER TABLE `links` CHANGE `url` ' .
            '`url` TEXT CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL;');

        /*
        $links = $this->fetchAll("SELECT `id`, `url`, `url_hash` FROM `links` WHERE `url_hash` IS NULL");

        foreach ($links as $link) {
            $link_id = $link['id'];
            $url_hash = sha1($link['url']);
            $this->execute("UPDATE `links` SET `url_hash` = '{$url_hash}' WHERE `id` = {$link_id};");
        }
        */

        $this->execute("UPDATE `links` SET `url_hash` = SHA1(`url`) WHERE `url_hash` IS NULL;");

        $this->table('links')
            ->addIndex('alias', ['name' => 'idx_alias'])
            ->addIndex('user_id', ['name' => 'idx_userid'])
            ->addIndex('url_hash', ['name' => 'idx_urlhash'])
            ->update();
    }

    public function getOption($name, $default = '')
    {
        $table_prefix = $this->getAdapter()->getOption('table_prefix');

        $row = $this->fetchRow("SELECT * FROM `{$table_prefix}options` WHERE `name` = '{$name}'");

        if (empty($row)) {
            return $default;
        }

        return $row['value'];
    }

    protected function getWithdrawMethods()
    {
        $withdrawal_methods = [];

        /*
        if ((bool)$this->getOption('wallet_enable', false)) {
            $withdrawal_methods[] = [
                'id' => 'wallet',
                'name' => __('My Wallet'),
                'amount' => $this->getOption('wallet_withdrawal_amount', 5),
            ];
        }
        */

        $withdrawal_methods[] = [
            'id' => 'paypal',
            'name' => __('PayPal'),
            'status' => (bool)$this->getOption('paypal_withdrawal_enable', false),
            'amount' => $this->getOption('paypal_withdrawal_amount', 5),
            'image' => '/assets/methods/paypal.png',
            'description' => '- For PayPal, add your email.',
        ];

        $withdrawal_methods[] = [
            'id' => 'payza',
            'name' => __('Payza'),
            'status' => (bool)$this->getOption('payza_withdrawal_enable', false),
            'amount' => $this->getOption('payza_withdrawal_amount', 5),
            'image' => '/assets/methods/payza.png',
            'description' => '- For Payza, add your email.',
        ];

        $withdrawal_methods[] = [
            'id' => 'skrill',
            'name' => __('Skrill'),
            'status' => (bool)$this->getOption('skrill_withdrawal_enable', false),
            'amount' => $this->getOption('skrill_withdrawal_amount', 5),
            'image' => '/assets/methods/skrill.png',
            'description' => '- For Skrill, add your email.',
        ];

        $withdrawal_methods[] = [
            'id' => 'bitcoin',
            'name' => __('Bitcoin'),
            'status' => (bool)$this->getOption('bitcoin_withdrawal_enable', false),
            'amount' => $this->getOption('bitcoin_withdrawal_amount', 5),
            'image' => '/assets/methods/bitcoin.png',
            'description' => '- For Bitcoin add your wallet address.',
        ];

        $withdrawal_methods[] = [
            'id' => 'webmoney',
            'name' => __('Web Money'),
            'status' => (bool)$this->getOption('webmoney_withdrawal_enable', false),
            'amount' => $this->getOption('webmoney_withdrawal_amount', 5),
            'image' => '/assets/methods/webmoney.png',
            'description' => '- For Web Money, add your purse.',
        ];

        $withdrawal_methods[] = [
            'id' => 'perfectmoney',
            'name' => __('Perfect Money'),
            'status' => (bool)$this->getOption('perfectmoney_withdrawal_enable', false),
            'amount' => $this->getOption('perfectmoney_withdrawal_amount', 5),
            'image' => '/assets/methods/perfectmoney.png',
            'description' => '- For Perfect Money add your email.',
        ];

        $withdrawal_methods[] = [
            'id' => 'payeer',
            'name' => __('Payeer'),
            'status' => (bool)$this->getOption('payeer_withdrawal_enable', false),
            'amount' => $this->getOption('payeer_withdrawal_amount', 5),
            'image' => '/assets/methods/payeer.png',
            'description' => '- For Payeer add account, e-mail or phone number.',
        ];

        $withdrawal_methods[] = [
            'id' => 'banktransfer',
            'name' => __('Bank Transfer'),
            'status' => (bool)$this->getOption('banktransfer_withdrawal_enable', false),
            'amount' => $this->getOption('banktransfer_withdrawal_amount', 5),
            'image' => '/assets/methods/banktransfer.png',
            'description' => '- For bank transfer add your account holder name, Bank Name, City/Town, Country, Account number, SWIFT, IBAN and Account currency',
        ];

        $custom_methods_blocks = explode(',', $this->getOption('custom_withdrawal_methods'));
        $custom_methods_blocks = array_map('trim', $custom_methods_blocks);
        $custom_methods_blocks = array_filter($custom_methods_blocks);

        if (empty($custom_methods_blocks)) {
            return $withdrawal_methods;
        }

        foreach ($custom_methods_blocks as $block) {
            $method = array_filter(explode('|', $block));

            if (count($method) !== 3) {
                continue;
            }

            $withdrawal_methods[] = [
                'id' => $method[0],
                'name' => $method[1],
                'status' => 1,
                'amount' => floatval($method[2]),
                'image' => '',
                'description' => '',
            ];
        }

        return $withdrawal_methods;
    }
}
