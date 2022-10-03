<?php

use Migrations\AbstractMigration;

class Version400 extends AbstractMigration
{
    public $autoId = false;

    public function up()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $table_prefix = $this->getAdapter()->getOption('table_prefix');

        $rows = [
            [
                'name' => 'stripe_enable',
                'value' => 0,
            ],
            [
                'name' => 'stripe_secret_key',
                'value' => '',
            ],
            [
                'name' => 'stripe_publishable_key',
                'value' => '',
            ],
            [
                'name' => 'coinpayments_enable',
                'value' => 0,
            ],
            [
                'name' => 'coinpayments_public_key',
                'value' => '',
            ],
            [
                'name' => 'coinpayments_private_key',
                'value' => '',
            ],
            [
                'name' => 'coinpayments_merchant_id',
                'value' => '',
            ],
            [
                'name' => 'coinpayments_ipn_secret',
                'value' => '',
            ],
            [
                'name' => 'perfectmoney_enable',
                'value' => 0,
            ],
            [
                'name' => 'perfectmoney_account',
                'value' => '',
            ],
            [
                'name' => 'perfectmoney_passphrase',
                'value' => '',
            ],
            [
                'name' => 'payeer_enable',
                'value' => 0,
            ],
            [
                'name' => 'payeer_merchant_id',
                'value' => 0,
            ],
            [
                'name' => 'payeer_secret_key',
                'value' => '',
            ],
            [
                'name' => 'payeer_encryption_key',
                'value' => '',
            ],
            [
                'name' => 'combine_minify_css_js',
                'value' => 1,
            ],
            [
                'name' => 'assets_cdn_url',
                'value' => '',
            ],
            [
                'name' => 'favicon_url',
                'value' => '',
            ],
            [
                'name' => 'force_disable_adblock',
                'value' => 0,
            ],
            [
                'name' => 'unique_visitor_per',
                'value' => 'campaign',
            ],
            [
                'name' => 'bitcoin_processor',
                'value' => 'coinbase',
            ],
        ];

        $this->table('options')
            ->insert($rows)
            ->saveData();

        $this->execute("UPDATE `{$table_prefix}options` SET `name` = 'paid_views_day' WHERE " .
            "`name` = 'campaign_paid_views_day';");

        $this->table('links')
            ->changeColumn('method', 'integer', [
                'comment' => '1=web, 2=quick, 3=mass, 4=full, 5=api, 6=bookmarklet',
                'default' => 0,
                'limit' => 2,
                'null' => false,
                'signed' => false,
            ])
            ->changeColumn('url', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->update();

        $this->table('plans')
            ->addColumn('bookmarklet', 'boolean', [
                'after' => 'api_developer',
                'default' => 0,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->update();

        $this->table('users')
            ->addColumn('login_ip', 'string', [
                'after' => 'expiration',
                'default' => '',
                'limit' => 45,
                'null' => false,
            ])
            ->addColumn('register_ip', 'string', [
                'after' => 'login_ip',
                'default' => '',
                'limit' => 45,
                'null' => false,
            ])
            ->update();

        $this->table('statistics')
            ->changeColumn('reason', 'integer', [
                'comment' => '1=Earn, 2=Disabled cookie, 3=Anonymous user, 4=Adblock, 5=Proxy, 6=IP changed, ' .
                    '7=Not unique, 8=Full weight, 9=Default campaign, 10=direct, 11=Invalid country',
                'default' => 0,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->update();

        $this->table('withdraws')
            ->addIndex(['created', 'user_id'], ['name' => 'idx_created_userId'])
            ->update();

        $items = implode(",", [
            "'coinbase_sandbox'",
        ]);
        $this->execute("DELETE FROM `{$table_prefix}options` WHERE `name` IN ({$items});");
    }

    public function down()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $table_prefix = $this->getAdapter()->getOption('table_prefix');

        $items = implode(",", [
            "'stripe_enable'",
            "'stripe_secret_key'",
            "'stripe_publishable_key'",
            "'coinpayments_enable'",
            "'coinpayments_public_key'",
            "'coinpayments_private_key'",
            "'coinpayments_merchant_id'",
            "'coinpayments_ipn_secret'",
            "'perfectmoney_enable'",
            "'perfectmoney_account'",
            "'perfectmoney_passphrase'",
            "'payeer_enable'",
            "'payeer_merchant_id'",
            "'payeer_secret_key'",
            "'payeer_encryption_key'",
            "'combine_minify_css_js'",
            "'assets_cdn_url'",
            "'favicon_url'",
            "'force_disable_adblock'",
            "'unique_visitor_per'",
            "'bitcoin_processor'",
        ]);
        $this->execute("DELETE FROM `{$table_prefix}options` WHERE `name` IN ({$items});");

        $this->execute("UPDATE `{$table_prefix}options` SET `name` = 'campaign_paid_views_day' WHERE " .
            "`name` = 'paid_views_day';");

        $this->table('links')
            ->changeColumn('method', 'integer', [
                'after' => 'hits',
                'comment' => '1=web, 2=quick, 3=mass, 4=full, 5=api',
                'default' => 0,
                'limit' => 2,
                'null' => false,
                'signed' => false,
            ])
            ->changeColumn('url', 'string', [
                'default' => '',
                'limit' => 500,
                'null' => false,
            ])
            ->update();

        $this->table('plans')
            ->removeColumn('bookmarklet')
            ->update();

        $this->table('users')
            ->removeColumn('login_ip')
            ->removeColumn('register_ip')
            ->update();

        $this->table('withdraws')
            ->removeIndexByName('idx_created_userId')
            ->update();

        $rows = [
            [
                'name' => 'coinbase_sandbox',
                'value' => 'no',
            ],
        ];

        $this->table('options')
            ->insert($rows)
            ->saveData();
    }
}
