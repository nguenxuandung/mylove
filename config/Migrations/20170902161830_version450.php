<?php

use Migrations\AbstractMigration;

class Version450 extends AbstractMigration
{
    public $autoId = false;

    public function up()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $table_prefix = $this->getAdapter()->getOption('table_prefix');

        $this->table('statistics')
            ->removeIndexByName('idx_referralearn_referralid')
            ->update();

        $minimum_withdrawal_amount = $this->get_option('minimum_withdrawal_amount');

        $rows = [
            [
                'name' => 'counter_start',
                'value' => 'DOMContentLoaded',
            ],
            [
                'name' => 'links_banned_words',
                'value' => '',
            ],
            [
                'name' => 'site_meta_title',
                'value' => $this->get_option('site_name'),
            ],
            [
                'name' => 'wallet_withdrawal_amount',
                'value' => $minimum_withdrawal_amount,
            ],
            [
                'name' => 'paypal_withdrawal_enable',
                'value' => ($this->get_option('paypal_enable') === 'yes') ? 1 : 0,
            ],
            [
                'name' => 'paypal_withdrawal_amount',
                'value' => $minimum_withdrawal_amount,
            ],
            [
                'name' => 'payza_withdrawal_enable',
                'value' => ($this->get_option('payza_enable') === 'yes') ? 1 : 0,
            ],
            [
                'name' => 'payza_withdrawal_amount',
                'value' => $minimum_withdrawal_amount,
            ],
            [
                'name' => 'skrill_withdrawal_enable',
                'value' => ((bool)$this->get_option('skrill_enable')) ? 1 : 0,
            ],
            [
                'name' => 'skrill_withdrawal_amount',
                'value' => $minimum_withdrawal_amount,
            ],
            [
                'name' => 'bitcoin_withdrawal_enable',
                'value' => (
                    $this->get_option('coinbase_enable') === 'yes' ||
                    (bool)$this->get_option('coinpayments_enable')
                ) ? 1 : 0,
            ],
            [
                'name' => 'bitcoin_withdrawal_amount',
                'value' => $minimum_withdrawal_amount,
            ],
            [
                'name' => 'webmoney_withdrawal_enable',
                'value' => ($this->get_option('webmoney_enable') === 'yes') ? 1 : 0,
            ],
            [
                'name' => 'webmoney_withdrawal_amount',
                'value' => $minimum_withdrawal_amount,
            ],
            [
                'name' => 'perfectmoney_withdrawal_enable',
                'value' => ((bool)$this->get_option('perfectmoney_enable')) ? 1 : 0,
            ],
            [
                'name' => 'perfectmoney_withdrawal_amount',
                'value' => $minimum_withdrawal_amount,
            ],
            [
                'name' => 'payeer_withdrawal_enable',
                'value' => ((bool)$this->get_option('payeer_enable')) ? 1 : 0,
            ],
            [
                'name' => 'payeer_withdrawal_amount',
                'value' => $minimum_withdrawal_amount,
            ],
            [
                'name' => 'banktransfer_withdrawal_enable',
                'value' => ($this->get_option('banktransfer_enable') === 'yes') ? 1 : 0,
            ],
            [
                'name' => 'banktransfer_withdrawal_amount',
                'value' => $minimum_withdrawal_amount,
            ],
            [
                'name' => 'custom_withdrawal_methods',
                'value' => '',
            ],
            [
                'name' => 'member_theme',
                'value' => 'AdminlteMemberTheme',
            ],
            [
                'name' => 'member_adminlte_theme_skin',
                'value' => 'skin-blue',
            ],
            [
                'name' => 'admin_theme',
                'value' => 'AdminlteAdminTheme',
            ],
            [
                'name' => 'admin_adminlte_theme_skin',
                'value' => 'skin-blue',
            ],
        ];
        $this->table('options')
            ->insert($rows)
            ->saveData();

        /**
         * links table
         */
        $this->execute("ALTER TABLE `{$table_prefix}links` CHANGE `hits` " .
            "`hits` BIGINT(20) UNSIGNED NOT NULL DEFAULT '0';");

        $this->table('announcements')
            ->removeColumn('slug')
            ->update();

        $this->execute("UPDATE `{$table_prefix}users` SET `withdrawal_method`='bitcoin' " .
            "WHERE `withdrawal_method` = 'coinbase';");

        $this->execute("UPDATE `{$table_prefix}withdraws` SET `method`='bitcoin' " .
            "WHERE `method` = 'coinbase';");

        $this->table('campaign_items')
            ->changeColumn('weight', 'float', [
                'default' => 0,
                'null' => false,
                'precision' => 18,
                'scale' => 15,
            ])
            ->update();

        $this->table('campaigns')
            ->changeColumn('website_url', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->update();

        $this->table('pages')
            ->addColumn('meta_title', 'string', [
                'default' => '',
                'limit' => 255,
                'null' => false,
                'after' => 'content',
            ])
            ->addColumn('meta_description', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
                'after' => 'meta_title',
            ])
            ->update();

        $this->table('posts')
            ->addColumn('meta_title', 'string', [
                'default' => '',
                'limit' => 255,
                'null' => false,
                'after' => 'description',
            ])
            ->addColumn('meta_description', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
                'after' => 'meta_title',
            ])
            ->update();

        $this->table('plans')
            ->addColumn('hidden', 'boolean', [
                'default' => 0,
                'limit' => null,
                'null' => false,
                'signed' => false,
                'after' => 'enable',
            ])
            ->addIndex(['enable', 'hidden'], ['name' => 'idx_enable_hidden'])
            ->update();
    }

    public function get_option($name)
    {
        $table_prefix = $this->getAdapter()->getOption('table_prefix');

        $row = $this->fetchRow("SELECT * FROM `{$table_prefix}options` WHERE `name` = '{$name}'");

        if (empty($row)) {
            return '';
        }

        return $row['value'];
    }

    public function down()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $table_prefix = $this->getAdapter()->getOption('table_prefix');

        $this->table('statistics')
            ->addIndex(['referral_earn', 'referral_id'], ['name' => 'idx_referralearn_referralid'])
            ->update();

        $items = implode(",", [
            "'counter_start'",
            "'links_banned_words'",
            "'site_meta_title'",
            "'wallet_withdrawal_amount'",
            "'paypal_withdrawal_enable'",
            "'paypal_withdrawal_amount'",
            "'payza_withdrawal_enable'",
            "'payza_withdrawal_amount'",
            "'skrill_withdrawal_enable'",
            "'skrill_withdrawal_amount'",
            "'bitcoin_withdrawal_enable'",
            "'bitcoin_withdrawal_amount'",
            "'webmoney_withdrawal_enable'",
            "'webmoney_withdrawal_amount'",
            "'perfectmoney_withdrawal_enable'",
            "'perfectmoney_withdrawal_amount'",
            "'payeer_withdrawal_enable'",
            "'payeer_withdrawal_amount'",
            "'banktransfer_withdrawal_enable'",
            "'banktransfer_withdrawal_amount'",
            "'custom_withdrawal_methods'",
            "'member_theme'",
            "'member_adminlte_theme_skin'",
            "'admin_theme'",
            "'admin_adminlte_theme_skin'",
        ]);
        $this->execute("DELETE FROM `{$table_prefix}options` WHERE `name` IN ({$items});");

        /**
         * links table
         */
        $this->execute("ALTER TABLE `{$table_prefix}links` CHANGE `hits` " .
            "`hits` INT(10) UNSIGNED NOT NULL DEFAULT '0';");

        $this->table('announcements')
            ->addColumn('slug', 'string', [
                'default' => '',
                'limit' => 255,
                'null' => false,
            ])
            ->update();

        $this->table('campaign_items')
            ->changeColumn('weight', 'float', [
                'default' => 0,
                'null' => false,
                'precision' => 5,
                'scale' => 2,
            ])
            ->update();

        $this->table('campaigns')
            ->changeColumn('website_url', 'string', [
                'default' => '',
                'limit' => 500,
                'null' => false,
            ])
            ->update();

        $this->table('pages')
            ->removeColumn('meta_title')
            ->removeColumn('meta_description')
            ->update();

        $this->table('posts')
            ->removeColumn('meta_title')
            ->removeColumn('meta_description')
            ->update();

        $this->table('plans')
            ->removeIndexByName('idx_enable_hidden')
            ->removeColumn('hidden')
            ->update();
    }
}
