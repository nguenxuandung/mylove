<?php

use Migrations\AbstractMigration;

class Version520 extends AbstractMigration
{
    public $autoId = false;

    public function up()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $table_prefix = $this->getAdapter()->getOption('table_prefix');

        $rows = [
            [
                'name' => 'cookie_notification_bar',
                'value' => 1,
            ],
            [
                'name' => 'prevent_direct_access_multi_domains',
                'value' => 1,
            ],
        ];

        $this->table('options')
            ->insert($rows)
            ->saveData();

        $this->table('social_profiles')
            ->addColumn('access_token', 'blob', [
                'default' => null,
                'null' => false,
                'after' => 'provider',
            ])
            ->addColumn('username', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
                'after' => 'identifier',
            ])
            ->addColumn('full_name', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
                'after' => 'last_name',
            ])
            ->removeColumn('gender')
            ->removeColumn('profile_url')
            ->removeColumn('website_url')
            ->removeColumn('photo_url')
            ->removeColumn('display_name')
            ->removeColumn('description')
            ->removeColumn('language')
            ->removeColumn('age')
            ->removeColumn('birth_day')
            ->removeColumn('birth_month')
            ->removeColumn('birth_year')
            ->removeColumn('phone')
            ->removeColumn('address')
            ->removeColumn('country')
            ->removeColumn('region')
            ->removeColumn('city')
            ->removeColumn('zip')
            ->update();
    }
}
