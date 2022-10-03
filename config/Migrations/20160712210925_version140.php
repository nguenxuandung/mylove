<?php

use Migrations\AbstractMigration;

class Version140 extends AbstractMigration
{
    public $autoId = false;

    public function up()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $options = $this->table('options');

        $rows = [
            [
                'name' => 'email_smtp_tls',
                'value' => 'false',
            ],
            [
                'name' => 'currency_symbol',
                'value' => '$',
            ],
        ];

        $options->insert($rows);
        $options->saveData();

        $this->table('campaigns')
            ->changeColumn('default_campaign', 'boolean', [
                'default' => 0,
                'limit' => null,
                'null' => true,
            ])
            ->update();

        $this->table('campaign_items')
            ->changeColumn('views', 'integer', [
                'default' => 0,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->changeColumn('weight', 'float', [
                'default' => 0,
                'limit' => true,
                'null' => false,
            ])
            ->update();
    }

    public function down()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $table_prefix = $this->getAdapter()->getOption('table_prefix');

        $items = implode(",", [
            "'email_smtp_tls'",
            "'currency_symbol'",
        ]);
        $this->execute("DELETE FROM `{$table_prefix}options` WHERE `name` IN ({$items});");

        $this->table('campaigns')
            ->changeColumn('default_campaign', 'boolean', [
                'default' => 0,
                'limit' => null,
                'null' => false,
            ])
            ->update();

        $this->table('campaign_items')
            ->changeColumn('views', 'integer', [
                'default' => 0,
                'limit' => 10,
                'null' => false,
                'signed' => true,
            ])
            ->changeColumn('weight', 'float', [
                'default' => 0,
                'limit' => null,
                'null' => false,
            ])
            ->update();
    }
}
