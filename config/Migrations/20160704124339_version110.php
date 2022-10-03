<?php

use Migrations\AbstractMigration;

class Version110 extends AbstractMigration
{
    public $autoId = false;

    public function up()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $options = $this->table('options');

        $rows = [
            [
                'name' => 'app_version',
                'value' => APP_VERSION,
            ],
            [
                'name' => 'counter_value',
                'value' => '5',
            ],
            [
                'name' => 'mass_shrinker_limit',
                'value' => '20',
            ],
        ];

        $options->insert($rows);
        $options->saveData();
    }

    public function down()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $table_prefix = $this->getAdapter()->getOption('table_prefix');

        $items = implode(",", ["'app_version'", "'counter_value'", "'mass_shrinker_limit'"]);
        $this->execute("DELETE FROM `{$table_prefix}options` WHERE `name` IN ({$items});");
    }
}
