<?php

use Migrations\AbstractMigration;

class Version530 extends AbstractMigration
{
    public $autoId = false;

    public function up()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $table_prefix = $this->getAdapter()->getOption('table_prefix');

        $rows = [
            [
                'name' => 'alert_member_canceled_withdraw',
                'value' => 1,
            ],
            [
                'name' => 'alert_member_returned_withdraw',
                'value' => 1,
            ],
        ];

        $this->table('options')
            ->insert($rows)
            ->saveData();
    }
}
