<?php

use Migrations\AbstractMigration;

class Version120 extends AbstractMigration
{
    public $autoId = false;

    public function up()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $options = $this->table('options');

        $rows = [
            [
                'name' => 'enable_advertising',
                'value' => 'yes',
            ],
        ];

        $options->insert($rows);
        $options->saveData();
    }

    public function down()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $table_prefix = $this->getAdapter()->getOption('table_prefix');

        $items = implode(",", ["'counter_value'"]);
        $this->execute("DELETE FROM `{$table_prefix}options` WHERE `name` IN ({$items});");
    }
}
