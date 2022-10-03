<?php

use Migrations\AbstractMigration;

class Version250 extends AbstractMigration
{
    public $autoId = false;

    public function up()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $table_prefix = $this->getAdapter()->getOption('table_prefix');

        $rows = [
            [
                'name' => 'paypal_enable',
                'value' => 'yes',
            ],
            [
                'name' => 'payza_enable',
                'value' => 'no',
            ],
            [
                'name' => 'payza_email',
                'value' => '',
            ],
            [
                'name' => 'interstitial_ads',
                'value' => '',
            ],
            [
                'name' => 'account_activate_email',
                'value' => 'yes',
            ],
            [
                'name' => 'anonymous_default_advert',
                'value' => (is_app_installed()) ? 1 : 2,
            ],
            [
                'name' => 'member_default_advert',
                'value' => (is_app_installed()) ? 1 : 2,
            ],
            [
                'name' => 'enable_interstitial',
                'value' => (is_app_installed()) ? 'yes' : 'no',
            ],
            [
                'name' => 'enable_banner',
                'value' => 'yes',
            ],
            [
                'name' => 'enable_noadvert',
                'value' => (is_app_installed()) ? 'yes' : 'no',
            ],
            [
                'name' => 'referral_banners_code',
                'value' => '',
            ],
            [
                'name' => 'auth_head_code',
                'value' => '',
            ],
            [
                'name' => 'member_head_code',
                'value' => '',
            ],
            [
                'name' => 'admin_head_code',
                'value' => '',
            ],
        ];

        $this->table('options')
            ->insert($rows)
            ->saveData();

        $this->table('campaigns')
            ->addColumn('payment_method', 'string', [
                'default' => '',
                'limit' => 256,
                'null' => false,
                'after' => 'status',
            ])
            ->update();

        $this->table('withdraws')
            ->addColumn('method', 'string', [
                'default' => '',
                'limit' => 256,
                'null' => false,
                'after' => 'amount',
            ])
            ->update();
    }

    public function down()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $table_prefix = $this->getAdapter()->getOption('table_prefix');

        $items = implode(",", [
            "'paypal_enable'",
            "'payza_enable'",
            "'payza_email'",
            "'interstitial_ads'",
            "'account_activate_email'",
            "'anonymous_default_advert'",
            "'member_default_advert'",
            "'enable_interstitial'",
            "'enable_banner'",
            "'enable_noadvert'",
            "'referral_banners_code'",
            "'auth_head_code'",
            "'member_head_code'",
            "'admin_head_code'",
        ]);
        $this->execute("DELETE FROM `{$table_prefix}options` WHERE `name` IN ({$items});");

        $this->table('campaigns')
            ->removeColumn('payment_method')
            ->removeColumn('transaction_id')
            ->removeColumn('transaction_details')
            ->update();

        $this->table('withdraws')
            ->removeColumn('method')
            ->update();
    }
}
