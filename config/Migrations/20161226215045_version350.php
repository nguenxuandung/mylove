<?php

use Migrations\AbstractMigration;

class Version350 extends AbstractMigration
{
    public $autoId = false;

    public function up()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $table_prefix = $this->getAdapter()->getOption('table_prefix');

        $this->table('options')
            ->changeColumn('name', 'string', [
                'default' => '',
                'limit' => 100,
                'null' => false,
            ])
            ->update();

        $rows = [
            [
                'name' => 'social_login_facebook',
                'value' => 0,
            ],
            [
                'name' => 'social_login_facebook_app_id',
                'value' => '',
            ],
            [
                'name' => 'social_login_facebook_app_secret',
                'value' => '',
            ],
            [
                'name' => 'social_login_twitter',
                'value' => 0,
            ],
            [
                'name' => 'social_login_twitter_api_key',
                'value' => '',
            ],
            [
                'name' => 'social_login_twitter_api_secret',
                'value' => '',
            ],
            [
                'name' => 'social_login_google',
                'value' => 0,
            ],
            [
                'name' => 'social_login_google_client_id',
                'value' => '',
            ],
            [
                'name' => 'social_login_google_client_secret',
                'value' => '',
            ],
            [
                'name' => 'blog_enable',
                'value' => 1,
            ],
            [
                'name' => 'blog_comments_enable',
                'value' => 0,
            ],
            [
                'name' => 'disqus_shortname',
                'value' => '',
            ],
            [
                'name' => 'ssl_enable',
                'value' => 0,
            ],
            [
                'name' => 'google_safe_browsing_key',
                'value' => '',
            ],
            [
                'name' => 'phishtank_key',
                'value' => '',
            ],
            [
                'name' => 'close_registration',
                'value' => 0,
            ],
            [
                'name' => 'enable_captcha_shortlink_anonymous',
                'value' => 0,
            ],
            [
                'name' => 'skrill_enable',
                'value' => 0,
            ],
            [
                'name' => 'skrill_email',
                'value' => '',
            ],
            [
                'name' => 'skrill_secret_word',
                'value' => '',
            ],
            [
                'name' => 'wallet_enable',
                'value' => 0,
            ],
        ];

        $this->table('options')
            ->insert($rows)
            ->saveData();

        $this->table('options')
            ->removeIndexByName('idx_name')
            ->addIndex('name', ['unique' => true, 'name' => 'idx_name'])
            ->update();

        $this->table('social_profiles')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('user_id', 'integer', [
                'default' => null,
                'limit' => 11,
                'null' => true,
            ])
            ->addColumn('provider', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('identifier', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('profile_url', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('website_url', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('photo_url', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('display_name', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('description', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('first_name', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('last_name', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('gender', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('language', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('age', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('birth_day', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('birth_month', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('birth_year', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('email', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('email_verified', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('phone', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('address', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('country', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('region', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('city', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('zip', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'null' => true,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'null' => true,
            ])
            ->addIndex('user_id', ['name' => 'idx_userId'])
            ->create();


        $this->table('posts')
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
            ->addColumn('slug', 'string', [
                'default' => '',
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('published', 'integer', [
                'default' => 0,
                'limit' => 2,
                'null' => false,
            ])
            ->addColumn('short_description', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('description', 'text', [
                'default' => null,
                'limit' => 4294967295,
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
            ->addIndex(['published', 'id'], ['name' => 'idx_published_id'])
            ->create();

        $this->table('announcements')
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
            ->addColumn('slug', 'string', [
                'default' => '',
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('published', 'integer', [
                'default' => 0,
                'limit' => 2,
                'null' => false,
            ])
            ->addColumn('content', 'text', [
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
            ->addIndex(['published', 'id'], ['name' => 'idx_published_id'])
            ->create();

        $this->table('statistics')
            ->addColumn('referral_id', 'integer', [
                'default' => 0,
                'limit' => 10,
                'null' => false,
                'signed' => false,
                'after' => 'user_id',
            ])
            ->addColumn('referral_earn', 'float', [
                'default' => 0,
                'null' => false,
                'precision' => 50,
                'scale' => 6,
                'after' => 'publisher_earn',
            ])
            ->changeColumn('user_agent', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex('referral_id', ['name' => 'idx_referralid'])
            ->addIndex(['referral_earn', 'referral_id'], ['name' => 'idx_referralearn_referralid'])
            ->update();

        $this->table('users')
            ->addColumn('wallet_money', 'float', [
                'default' => 0,
                'null' => false,
                'precision' => 50,
                'scale' => 6,
                'signed' => false,
                'after' => 'activation_key',
            ])
            ->update();
    }

    public function down()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $table_prefix = $this->getAdapter()->getOption('table_prefix');

        $items = implode(",", [
            "'social_login_facebook'",
            "'social_login_facebook_app_id'",
            "'social_login_facebook_app_secret'",
            "'social_login_twitter'",
            "'social_login_twitter_api_key'",
            "'social_login_twitter_api_secret'",
            "'social_login_google'",
            "'social_login_google_client_id'",
            "'social_login_google_client_secret'",
            "'blog_enable'",
            "'blog_comments_enable'",
            "'disqus_shortname'",
            "'ssl_enable'",
            "'google_safe_browsing_key'",
            "'phishtank_key'",
            "'close_registration'",
            "'enable_captcha_shortlink_anonymous'",
            "'skrill_enable'",
            "'skrill_email'",
            "'skrill_secret_word'",
            "'wallet_enable'",
        ]);
        $this->execute("DELETE FROM `{$table_prefix}options` WHERE `name` IN ({$items});");

        $this->table('options')
            ->changeColumn('name', 'string', [
                'default' => '',
                'limit' => 30,
                'null' => false,
            ])
            ->removeIndexByName('idx_name')
            ->addIndex('name', ['name' => 'idx_name'])
            ->update();

        $this->dropTable('social_profiles');

        $this->dropTable('posts');

        $this->dropTable('announcements');

        $this->table('statistics')
            ->removeColumn('referral_id')
            ->removeColumn('referral_earn')
            ->changeColumn('user_agent', 'string', [
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->removeIndexByName('idx_referralid')
            ->removeIndexByName('idx_referralearn_referralid')
            ->update();

        $this->table('users')
            ->removeColumn('wallet_money')
            ->update();
    }
}
