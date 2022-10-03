<?php

use Migrations\AbstractMigration;

class Version650 extends AbstractMigration
{
    public $autoId = false;

    public function up()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $table_prefix = $this->getAdapter()->getOption('table_prefix');

        $rows = [
            [
                'name' => 'hcaptcha_checkbox_site_key',
                'value' => '',
            ],
            [
                'name' => 'hcaptcha_checkbox_secret_key',
                'value' => '',
            ],
            [
                'name' => 'continue_pages_number',
                'value' => 0,
            ],
            [
                'name' => 'block_referers_domains',
                'value' => '',
            ],
            [
                'name' => 'schedule_cron_last_time_run',
                'value' => '',
            ],
            [
                'name' => 'queue_cron_last_time_run',
                'value' => '',
            ],
            [
                'name' => 'delete_links_without_activity_months',
                'value' => '0',
            ],
            [
                'name' => 'delete_links_without_activity_views',
                'value' => '0',
            ],
            [
                'name' => 'delete_pending_users_months',
                'value' => '0',
            ],
        ];

        $this->table('options')
            ->insert($rows)
            ->saveData();

        $this->execute("DELETE FROM `{$table_prefix}options` WHERE `name` = 'payza_enable';");
        $this->execute("DELETE FROM `{$table_prefix}options` WHERE `name` = 'payza_email';");
        $this->execute("DELETE FROM `{$table_prefix}options` WHERE `name` = 'google_plus_url';");

        $this->table('withdraws')
            ->addColumn('json_data', 'text', [
                'after' => 'amount',
                'default' => null,
                'limit' => 4294967295,
                'null' => true,
            ])
            ->addColumn('user_note', 'string', [
                'after' => 'account',
                'default' => null,
                'limit' => 191,
                'null' => true,
            ])
            ->update();

        $this->table('plans')
            ->addColumn('timer', 'integer', [
                'after' => 'cpm_fixed',
                'default' => $this->getOption('counter_value', 5),
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('views_hourly_limit', 'integer', [
                'after' => 'url_monthly_limit',
                'default' => 0,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('views_daily_limit', 'integer', [
                'after' => 'views_hourly_limit',
                'default' => 0,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('views_monthly_limit', 'integer', [
                'after' => 'views_daily_limit',
                'default' => 0,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('referral_percentage', 'integer', [
                'after' => 'referral',
                'default' => $this->getOption('referral_percentage', 20),
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('direct_redirect', 'boolean', [
                'after' => 'timer',
                'default' => ($this->getOption('enable_noadvert', 'no') === 'yes') ? 1 : 0,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('banner_redirect', 'boolean', [
                'after' => 'direct_redirect',
                'default' => ($this->getOption('enable_banner', 'no') === 'yes') ? 1 : 0,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('interstitial_redirect', 'boolean', [
                'after' => 'banner_redirect',
                'default' => ($this->getOption('enable_interstitial', 'no') === 'yes') ? 1 : 0,
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('random_redirect', 'boolean', [
                'after' => 'interstitial_redirect',
                'default' => (bool)$this->getOption('enable_random_ad_type', 0),
                'limit' => null,
                'null' => false,
                'signed' => false,
            ])
            ->update();

        $this->table('queued_jobs')
            ->addColumn('id', 'biginteger', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 20,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('job_type', 'string', [
                'default' => null,
                'limit' => 45,
                'null' => false,
            ])
            ->addColumn('data', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('job_group', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('reference', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('notbefore', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('failed', 'integer', [
                'default' => '0',
                'limit' => 11,
                'null' => false,
            ])
            ->addColumn('failure_message', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('workerkey', 'string', [
                'default' => null,
                'limit' => 45,
                'null' => true,
            ])
            ->addColumn('status', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('priority', 'integer', [
                'default' => '5',
                'limit' => 3,
                'null' => false,
            ])
            ->addColumn('progress', 'float', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('fetched', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('completed', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->create();

        $this->table('queue_processes')
            ->addColumn('id', 'biginteger', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 20,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('pid', 'string', [
                'default' => null,
                'limit' => 40,
                'null' => false,
            ])
            ->addColumn('terminate', 'boolean', [
                'default' => false,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('server', 'string', [
                'default' => null,
                'limit' => 90,
                'null' => true,
            ])
            ->addColumn('workerkey', 'string', [
                'default' => null,
                'limit' => 45,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addIndex(
                [
                    'workerkey',
                ],
                ['name' => 'idx_workerkey', 'unique' => true]
            )
            ->addIndex(
                [
                    'pid',
                    'server',
                ],
                ['name' => 'idx_pid_server', 'unique' => true]
            )
            ->create();

        $this->table('statistics')
            ->changeColumn('reason', 'integer', [
                'comment' => '1=Earn, 2=Disabled cookie, 3=Anonymous user, 4=Adblock, 5=Proxy, 6=IP changed, ' .
                    '7=Not unique, 8=Full weight, 9=Default campaign, 10=direct, 11=Invalid country, ' .
                    '12=Earnings disabled, 13=User disabled earnings, 14=Blocked referer domain, 15=Reached the hourly limit, ' .
                    '16=Reached the daily limit, 17=Reached the monthly limit',
                'default' => 0,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->update();

        $this->table('users')
            ->addIndex('expiration', ['name' => 'idx_expiration'])
            ->update();

        $this->table('links')
            ->addColumn('last_activity', 'datetime', [
                'after' => 'expiration',
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex('last_activity', ['name' => 'idx_lastActivity'])
            ->update();

        // Todo: Options to be removed
        // referral_percentage
        // counter_value
        // enable_random_ad_type
    }

    protected function getOption($name, $default = '')
    {
        $row = $this->fetchRow("SELECT * FROM `options` WHERE `name` = '{$name}'");

        if (empty($row)) {
            return $default;
        }

        return $row['value'];
    }
}
