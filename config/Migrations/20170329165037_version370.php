<?php

use Migrations\AbstractMigration;

class Version370 extends AbstractMigration
{
    public $autoId = false;

    public function up()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $table_prefix = $this->getAdapter()->getOption('table_prefix');

        $this->execute('ALTER TABLE `' . $table_prefix . 'links` CHANGE `alias` ' .
            '`alias` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL;');

        $rows = [
            [
                'name' => 'language_auto_redirect',
                'value' => 0,
            ],
            [
                'name' => 'invisible_reCAPTCHA_site_key',
                'value' => '',
            ],
            [
                'name' => 'invisible_reCAPTCHA_secret_key',
                'value' => '',
            ],
        ];

        $this->table('options')
            ->insert($rows)
            ->saveData();

        $this->table('campaign_items')
            ->addIndex(['weight', 'country'], ['name' => 'idx_weight_country'])
            ->update();

        $this->table('links')
            ->addColumn('method', 'integer', [
                'after' => 'hits',
                'comment' => '1=web, 2=quick, 3=mass, 4=full, 5=api',
                'default' => 0,
                'limit' => 2,
                'null' => false,
                'signed' => false,
            ])
            ->update();
    }

    public function down()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $table_prefix = $this->getAdapter()->getOption('table_prefix');

        $items = implode(",", [
            "'language_auto_redirect'",
            "'invisible_reCAPTCHA_site_key'",
            "'invisible_reCAPTCHA_secret_key'",
        ]);
        $this->execute("DELETE FROM `{$table_prefix}options` WHERE `name` IN ({$items});");

        $this->table('campaign_items')
            ->removeIndexByName('idx_weight_country')
            ->update();

        $this->table('links')
            ->removeColumn('method')
            ->update();
    }
}
