<?php

use Migrations\AbstractMigration;

class Version500 extends AbstractMigration
{
    public $autoId = false;

    public function up()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $table_prefix = $this->getAdapter()->getOption('table_prefix');

        $email_smtp_tls = $this->getOption('email_smtp_tls');
        $email_smtp_host = $this->getOption('email_smtp_host');

        $email_smtp_security = 'none';

        if ($email_smtp_tls == 'true') {
            $email_smtp_security = 'tls';
        } elseif (preg_match('#^ssl://#i', $email_smtp_host)) {
            $email_smtp_security = 'ssl';
            $email_smtp_host = explode('ssl://', $email_smtp_host)[1];
            $this->execute("UPDATE `options` SET `value` = '{$email_smtp_host}' WHERE `name` = 'email_smtp_host';");
        }

        $this->execute("DELETE FROM `options` WHERE `name` = 'email_smtp_tls';");

        $rows = [
            [
                'name' => 'enable_captcha_signin',
                'value' => 'no',
            ],
            [
                'name' => 'https_shortlinks',
                'value' => 0,
            ],
            [
                'name' => 'enable_referrals',
                'value' => 1,
            ],
            [
                'name' => 'cache_admin_statistics',
                'value' => (is_app_installed()) ? 1 : 0,
            ],
            [
                'name' => 'cache_member_statistics',
                'value' => (is_app_installed()) ? 1 : 0,
            ],
            [
                'name' => 'cache_home_counters',
                'value' => (is_app_installed()) ? 1 : 0,
            ],
            [
                'name' => 'email_smtp_security',
                'value' => $email_smtp_security,
            ],
            [
                'name' => 'price_decimals',
                'value' => 6,
            ],
            [
                'name' => 'enable_publisher_earnings',
                'value' => 1,
            ],
            [
                'name' => 'display_blog_post_shortlink',
                'value' => 'none',
            ],
            [
                'name' => 'enable_random_ad_type',
                'value' => 0,
            ],
            [
                'name' => 'signup_bonus',
                'value' => 0,
            ],
            [
                'name' => 'trial_plan',
                'value' => '',
            ],
            [
                'name' => 'trial_plan_period',
                'value' => '',
            ],
        ];

        $this->table('options')
            ->insert($rows)
            ->saveData();

        $this->table('users')
            ->changeColumn('username', 'string', [
                'default' => '',
                'limit' => 150,
                'null' => false,
            ])
            ->changeColumn('password', 'string', [
                'default' => '',
                'limit' => 255,
                'null' => false,
            ])
            ->changeColumn('email', 'string', [
                'default' => '',
                'limit' => 254,
                'null' => false,
            ])
            ->changeColumn('temp_email', 'string', [
                'default' => '',
                'limit' => 254,
                'null' => false,
            ])
            ->addColumn('disable_earnings', 'boolean', [
                'default' => 0,
                'limit' => null,
                'null' => false,
                'signed' => false,
                'after' => 'temp_email',
            ])
            ->addIndex('username', ['name' => 'idx_username'])
            ->addIndex('email', ['name' => 'idx_email'])
            ->update();

        $this->table('plans')
            ->addColumn('onetime_captcha', 'boolean', [
                'default' => 0,
                'limit' => null,
                'null' => false,
                'signed' => false,
                'after' => 'disable_captcha',
            ])
            ->addColumn('multi_domains', 'boolean', [
                'default' => 1,
                'limit' => null,
                'null' => false,
                'signed' => false,
                'after' => 'disable_captcha',
            ])
            ->update();

        $this->table('withdraws')
            ->changeColumn('status', 'integer', [
                'comment' => '1=Approved, 2=Pending, 3=Complete, 4=Cancelled, 5=Returned',
                'default' => 0,
                'limit' => 2,
                'null' => false,
                'signed' => false,
            ])
            ->update();

        $this->table('links')
            ->changeColumn('ad_type', 'integer', [
                'comment' => '0=direct, 1=interstitial, 2=banner, 3=random',
                'default' => 1,
                'limit' => 2,
                'null' => false,
                'signed' => false,
            ])
            ->update();

        $this->table('statistics')
            ->changeColumn('reason', 'integer', [
                'comment' => '1=Earn, 2=Disabled cookie, 3=Anonymous user, 4=Adblock, 5=Proxy, 6=IP changed, ' .
                    '7=Not unique, 8=Full weight, 9=Default campaign, 10=direct, 11=Invalid country, ' .
                    '12=Earnings disabled, 13=User disabled earnings',
                'default' => 0,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->update();
    }

    public function getOption($name)
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
    }
}
