<?php

use Migrations\AbstractMigration;

class Version640 extends AbstractMigration
{
    public $autoId = false;

    public function up()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $table_prefix = $this->getAdapter()->getOption('table_prefix');

        $rows = [
            [
                'name' => 'proxy_service',
                'value' => 'free',
            ],
            [
                'name' => 'isproxyip_key',
                'value' => '',
            ],
            [
                'name' => 'display_home_stats',
                'value' => 1,
            ],
        ];

        $this->table('options')
            ->insert($rows)
            ->saveData();
    }
}
