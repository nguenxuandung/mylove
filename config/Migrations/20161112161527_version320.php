<?php

use Migrations\AbstractMigration;

class Version320 extends AbstractMigration
{
    public $autoId = false;

    public function up()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $table_prefix = $this->getAdapter()->getOption('table_prefix');

        $rows = [
            [
                'name' => 'site_languages',
                'value' => '',
            ],
            [
                'name' => 'disable_meta_home',
                'value' => 'no',
            ],
            [
                'name' => 'disable_meta_member',
                'value' => 'no',
            ],
            [
                'name' => 'disable_meta_api',
                'value' => 'yes',
            ],
            [
                'name' => 'main_domain',
                'value' => env("HTTP_HOST", ""),
            ],
            [
                'name' => 'default_short_domain',
                'value' => '',
            ],
            [
                'name' => 'multi_domains',
                'value' => '',
            ],
            [
                'name' => 'theme',
                'value' => 'CloudTheme',
            ],
            [
                'name' => 'fake_clicks',
                'value' => '0',
            ],
            [
                'name' => 'fake_links',
                'value' => '0',
            ],
            [
                'name' => 'fake_users',
                'value' => '0',
            ],
        ];

        $this->table('options')
            ->insert($rows)
            ->saveData();

        $this->table('i18n')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 11,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('locale', 'string', [
                'default' => null,
                'limit' => 6,
                'null' => false,
            ])
            ->addColumn('model', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => false,
            ])
            ->addColumn('foreign_key', 'integer', [
                'default' => null,
                'limit' => 10,
                'null' => false,
            ])
            ->addColumn('field', 'string', [
                'default' => null,
                'limit' => 20,
                'null' => false,
            ])
            ->addColumn('content', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addIndex(
                [
                    'locale',
                    'model',
                    'foreign_key',
                    'field',
                ],
                ['unique' => true]
            )
            ->addIndex(
                [
                    'model',
                    'foreign_key',
                    'field',
                ]
            )
            ->create();

        $this->table('testimonials')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('name', 'string', [
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->addColumn('position', 'string', [
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->addColumn('image', 'string', [
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->addColumn('published', 'boolean', [
                'default' => 0,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('content', 'text', [
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
            ->create();

        $this->table('links')
            ->addColumn('domain', 'string', [
                'default' => '',
                'limit' => 255,
                'null' => false,
                'after' => 'url',
            ])
            ->update();

        $this->table('campaigns')
            ->changeColumn('website_url', 'string', [
                'default' => '',
                'limit' => 500,
                'null' => false,
            ])
            ->addIndex('user_id', ['name' => 'idx_userid'])
            ->addIndex(['default_campaign', 'ad_type', 'status', 'traffic_source'], ['name' => 'idx_campaigns'])
            ->update();

        $this->table('campaign_items')
            ->changeColumn('country', 'string', [
                'default' => '',
                'limit' => 3,
                'null' => false,
            ])
            ->changeColumn('weight', 'float', [
                'default' => 0,
                'null' => false,
                'precision' => 5,
                'scale' => 2,
            ])
            ->addIndex('campaign_id', ['name' => 'idx_campaignid'])
            ->update();

        $this->table('links')
            ->changeColumn('alias', 'string', [
                'default' => '',
                'limit' => 30,
                'null' => false,
            ])
            ->addIndex(['alias', 'status'], ['name' => 'idx_alias_status'])
            ->addIndex(['user_id', 'status', 'ad_type'], ['name' => 'idx_userid_status_adtype'])
            ->update();

        $this->table('options')
            ->changeColumn('name', 'string', [
                'default' => '',
                'limit' => 30,
                'null' => false,
            ])
            ->addIndex('name', ['name' => 'idx_name'])
            ->update();

        $this->table('pages')
            ->changeColumn('slug', 'string', [
                'default' => '',
                'limit' => 255,
                'null' => false,
            ])
            ->addIndex(['slug', 'published'], ['name' => 'idx_slug_published'])
            ->update();

        $this->table('statistics')
            ->changeColumn('ip', 'string', [
                'default' => '',
                'limit' => 45,
                'null' => false,
            ])
            ->changeColumn('country', 'string', [
                'default' => '',
                'limit' => 6,
                'null' => false,
            ])
            ->addIndex('user_id', ['name' => 'idx_userid'])
            ->addIndex('ip', ['name' => 'idx_ip'])
            ->addIndex('owner_earn', ['name' => 'idx_ownerearn'])
            ->addIndex('created', ['name' => 'idx_created'])
            ->addIndex(['campaign_id', 'user_id'], ['name' => 'idx_campaignid_userid'])
            ->addIndex(['publisher_earn', 'user_id'], ['name' => 'idx_publisherearn_userid'])
            ->update();

        $this->table('users')
            ->changeColumn('api_token', 'string', [
                'default' => '',
                'limit' => 40,
                'null' => false,
            ])
            ->changeColumn('activation_key', 'string', [
                'default' => '',
                'limit' => 40,
                'null' => false,
            ])
            ->addIndex('referred_by', ['name' => 'idx_referredby'])
            ->addIndex(['status', 'id'], ['name' => 'idx_status_id'])
            ->addIndex(['api_token', 'status'], ['name' => 'idx_apitoken_status'])
            ->update();

        $this->table('withdraws')
            ->addIndex(['status', 'user_id'], ['name' => 'idx_status_userid'])
            ->update();
    }

    public function down()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $table_prefix = $this->getAdapter()->getOption('table_prefix');

        $items = implode(",", [
            "'site_languages'",
            "'disable_meta_home'",
            "'disable_meta_member'",
            "'disable_meta_api'",
            "'main_domain'",
            "'multi_domains'",
            "'default_short_domain'",
            "'theme'",
            "'fake_clicks'",
            "'fake_clicks'",
            "'fake_users'",
        ]);
        $this->execute("DELETE FROM `{$table_prefix}options` WHERE `name` IN ({$items});");

        $this->dropTable('i18n');
        $this->dropTable('testimonials');

        $this->table('links')
            ->removeColumn('domain')
            ->update();

        $this->table('campaigns')
            ->removeIndexByName('idx_userid')
            ->removeIndexByName('idx_campaigns')
            ->update();

        $this->table('campaign_items')
            ->removeIndexByName('idx_campaignid')
            ->update();

        $this->table('links')
            ->removeIndexByName('idx_alias_status')
            ->removeIndexByName('idx_userid_status_adtype')
            ->update();

        $this->table('options')
            ->removeIndexByName('idx_name')
            ->update();

        $this->table('pages')
            ->removeIndexByName('idx_slug_published')
            ->update();

        $this->table('statistics')
            ->removeIndexByName('idx_userid')
            ->removeIndexByName('idx_ip')
            ->removeIndexByName('idx_ownerearn')
            ->removeIndexByName('idx_created')
            ->removeIndexByName('idx_campaignid_userid')
            ->removeIndexByName('idx_publisherearn_userid')
            ->update();

        $this->table('users')
            ->removeIndexByName('idx_referredby')
            ->removeIndexByName('idx_status_id')
            ->removeIndexByName('idx_apitoken_status')
            ->update();

        $this->table('withdraws')
            ->removeIndexByName('idx_status_userid')
            ->update();
    }
}
