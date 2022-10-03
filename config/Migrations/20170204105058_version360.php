<?php

use Migrations\AbstractMigration;

class Version360 extends AbstractMigration
{
    public $autoId = false;

    public function up()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $table_prefix = $this->getAdapter()->getOption('table_prefix');

        $this->table('plans')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('title', 'string', [
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->addColumn('enable', 'boolean', [
                'default' => 0,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('description', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('monthly_price', 'decimal', [
                'default' => 0,
                'null' => false,
                'precision' => 10,
                'scale' => 6,
                'signed' => false,
            ])
            ->addColumn('yearly_price', 'decimal', [
                'default' => 0,
                'null' => false,
                'precision' => 10,
                'scale' => 6,
                'signed' => false,
            ])
            ->addColumn('edit_link', 'boolean', [
                'default' => 0,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('edit_long_url', 'boolean', [
                'default' => 0,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('disable_ads', 'boolean', [
                'default' => 0,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('disable_captcha', 'boolean', [
                'default' => 0,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('direct', 'boolean', [
                'default' => 0,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('alias', 'boolean', [
                'default' => 0,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('referral', 'boolean', [
                'default' => 0,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('stats', 'boolean', [
                'default' => 0,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('api_quick', 'boolean', [
                'default' => 0,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('api_mass', 'boolean', [
                'default' => 0,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('api_full', 'boolean', [
                'default' => 0,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('api_developer', 'boolean', [
                'default' => 0,
                'limit' => null,
                'null' => false,
                'signed' => false,
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
            ->create();

        $plans = [
            [
                'id' => 1,
                'title' => 'Default',
                'enable' => 1,
                'description' => '',
                'monthly_price' => 0,
                'yearly_price' => 0,
                'edit_link' => 1,
                'edit_long_url' => 0,
                'disable_ads' => 0,
                'disable_captcha' => 0,
                'direct' => 0,
                'alias' => 1,
                'referral' => 1,
                'stats' => 1,
                'api_quick' => 0,
                'api_mass' => 0,
                'api_full' => 0,
                'api_developer' => 0,
                'modified' => date("Y-m-d H:i:s"),
                'created' => date("Y-m-d H:i:s"),
            ],
            [
                'id' => 2,
                'title' => 'Primary',
                'enable' => 1,
                'description' => '',
                'monthly_price' => 1.99,
                'yearly_price' => 19.99,
                'edit_link' => 1,
                'edit_long_url' => 1,
                'disable_ads' => 1,
                'disable_captcha' => 1,
                'direct' => 0,
                'alias' => 1,
                'referral' => 1,
                'stats' => 1,
                'api_quick' => 1,
                'api_mass' => 1,
                'api_full' => 0,
                'api_developer' => 0,
                'modified' => date("Y-m-d H:i:s"),
                'created' => date("Y-m-d H:i:s"),
            ],
            [
                'id' => 3,
                'title' => 'Professional',
                'enable' => 1,
                'description' => '',
                'monthly_price' => 3.99,
                'yearly_price' => 39.99,
                'edit_link' => 1,
                'edit_long_url' => 1,
                'disable_ads' => 1,
                'disable_captcha' => 1,
                'direct' => 1,
                'alias' => 1,
                'referral' => 1,
                'stats' => 1,
                'api_quick' => 1,
                'api_mass' => 1,
                'api_full' => 1,
                'api_developer' => 1,
                'modified' => date("Y-m-d H:i:s"),
                'created' => date("Y-m-d H:i:s"),
            ],
        ];
        $this->table('plans')
            ->insert($plans)
            ->saveData();

        $this->table('users')
            ->addColumn('plan_id', 'integer', [
                'default' => 1,
                'limit' => 10,
                'null' => false,
                'signed' => false,
                'after' => 'role',
            ])
            ->addColumn('expiration', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
                'after' => 'withdrawal_account',
            ])
            ->removeIndexByName('idx_status_id')
            ->removeIndexByName('idx_apitoken_status')
            ->addIndex('plan_id', ['name' => 'idx_planid'])
            ->addIndex('api_token', ['unique' => true, 'name' => 'idx_apitoken'])
            ->update();

        $this->table('invoices')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('status', 'integer', [
                'default' => 0,
                'limit' => 2,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('user_id', 'integer', [
                'default' => 0,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('description', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('type', 'integer', [
                'default' => 0,
                'limit' => 2,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('rel_id', 'integer', [
                'default' => 0,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('payment_method', 'string', [
                'default' => '',
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('amount', 'decimal', [
                'default' => 0,
                'null' => false,
                'precision' => 10,
                'scale' => 6,
                'signed' => false,
            ])
            ->addColumn('data', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('paid_date', 'datetime', [
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
            ->addIndex('user_id', ['name' => 'idx_userId'])
            ->create();

        $rows = [
            [
                'name' => 'enable_premium_membership',
                'value' => 0,
            ],
            [
                'name' => 'captcha_type',
                'value' => 'recaptcha',
            ],
            [
                'name' => 'solvemedia_challenge_key',
                'value' => '',
            ],
            [
                'name' => 'solvemedia_verification_key',
                'value' => '',
            ],
            [
                'name' => 'solvemedia_authentication_key',
                'value' => '',
            ],
        ];

        $this->table('options')
            ->insert($rows)
            ->saveData();

        $interstitial_price = $this->getOption('interstitial_price', []);
        $banner_price = $this->getOption('banner_price', []);
        $popup_price = $this->getOption('popup_price', []);

        if (count(array_values($interstitial_price)[0]) == 2) {
            $new_interstitial_price = [];
            foreach ($interstitial_price as $key => $value) {
                $new_interstitial_price[$key] = [
                    1 => $value,
                    2 => $value,
                    3 => $value,
                ];
            }
            $new_interstitial_price = serialize($new_interstitial_price);
            $this->execute("UPDATE `{$table_prefix}options` SET `value` = '{$new_interstitial_price}' " .
                "WHERE `name` = 'interstitial_price';");
        }

        if (count(array_values($banner_price)[0]) == 2) {
            $new_banner_price = [];
            foreach ($banner_price as $key => $value) {
                $new_banner_price[$key] = [
                    1 => $value,
                    2 => $value,
                    3 => $value,
                ];
            }
            $new_banner_price = serialize($new_banner_price);
            $this->execute("UPDATE `{$table_prefix}options` SET `value` = '{$new_banner_price}' " .
                "WHERE `name` = 'banner_price';");
        }

        if (count(array_values($popup_price)[0]) == 2) {
            $new_popup_price = [];
            foreach ($popup_price as $key => $value) {
                $new_popup_price[$key] = [
                    1 => $value,
                    2 => $value,
                    3 => $value,
                ];
            }
            $new_popup_price = serialize($new_popup_price);
            $this->execute("UPDATE `{$table_prefix}options` SET `value` = '{$new_popup_price}' " .
                "WHERE `name` = 'popup_price';");
        }
    }

    public function getOption($name, $default)
    {
        $table_prefix = $this->getAdapter()->getOption('table_prefix');

        $row = $this->fetchRow("SELECT * FROM `{$table_prefix}options` WHERE `name` = '{$name}'");

        if (empty($row)) {
            return $default;
        }

        return (is_serialized($row['value'])) ? unserialize($row['value']) : $row['value'];
    }

    public function down()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $table_prefix = $this->getAdapter()->getOption('table_prefix');

        $this->dropTable('plans');

        $this->table('users')
            ->removeColumn('plan_id')
            ->removeColumn('expiration')
            ->removeIndexByName('idx_apitoken')
            ->addIndex(['status', 'id'], ['name' => 'idx_status_id'])
            ->addIndex(['api_token', 'status'], ['name' => 'idx_apitoken_status'])
            ->update();

        $this->dropTable('invoices');

        $items = implode(",", [
            "'enable_premium_membership'",
            "'captcha_type'",
            "'solvemedia_challenge_key'",
            "'solvemedia_verification_key'",
            "'solvemedia_authentication_key'",
        ]);
        $this->execute("DELETE FROM `{$table_prefix}options` WHERE `name` IN ({$items});");

        $interstitial_price = $this->getOption('interstitial_price', []);
        $banner_price = $this->getOption('banner_price', []);
        $popup_price = $this->getOption('popup_price', []);

        if (count(array_values($interstitial_price)[0]) == 3) {
            $new_interstitial_price = [];
            foreach ($interstitial_price as $key => $value) {
                $new_interstitial_price[$key] = $value[1];
            }
            $new_interstitial_price = serialize($new_interstitial_price);
            $this->execute("UPDATE `{$table_prefix}options` SET `value` = '{$new_interstitial_price}' " .
                "WHERE `name` = 'interstitial_price';");
        }

        if (count(array_values($banner_price)[0]) == 3) {
            $new_banner_price = [];
            foreach ($banner_price as $key => $value) {
                $new_banner_price[$key] = $value[1];
            }
            $new_banner_price = serialize($new_banner_price);
            $this->execute("UPDATE `{$table_prefix}options` SET `value` = '{$new_banner_price}' " .
                "WHERE `name` = 'banner_price';");
        }

        if (count(array_values($popup_price)[0]) == 3) {
            $new_popup_price = [];
            foreach ($popup_price as $key => $value) {
                $new_popup_price[$key] = $value[1];
            }
            $new_popup_price = serialize($new_popup_price);
            $this->execute("UPDATE `{$table_prefix}options` SET `value` = '{$new_popup_price}' " .
                "WHERE `name` = 'popup_price';");
        }
    }
}
