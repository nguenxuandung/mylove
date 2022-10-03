<?php

use Migrations\AbstractMigration;

class Version310 extends AbstractMigration
{
    public $autoId = false;

    public function up()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $table_prefix = $this->getAdapter()->getOption('table_prefix');

        $items = implode(",", [
            "'mass_shrinker_default_advert'",
        ]);
        $this->execute("DELETE FROM `{$table_prefix}options` WHERE `name` IN ({$items});");

        $rows = [
            [
                'name' => 'reserved_usernames',
                'value' => '',
            ],
            [
                'name' => 'reserved_aliases',
                'value' => '',
            ],
            [
                'name' => 'currency_position',
                'value' => 'before',
            ],
            [
                'name' => 'enable_captcha_contact',
                'value' => 'yes',
            ],
        ];

        $this->table('options')
            ->insert($rows)
            ->saveData();
    }

    public function down()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $table_prefix = $this->getAdapter()->getOption('table_prefix');

        $rows = [
            [
                'name' => 'mass_shrinker_default_advert',
                'value' => '1',
            ],
        ];

        $this->table('options')
            ->insert($rows)
            ->saveData();

        $items = implode(",", [
            "'reserved_usernames'",
            "'reserved_aliases'",
            "'currency_position'",
            "'enable_captcha_contact'",
        ]);
        $this->execute("DELETE FROM `{$table_prefix}options` WHERE `name` IN ({$items});");
    }
}
