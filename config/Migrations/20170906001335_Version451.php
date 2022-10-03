<?php

use Migrations\AbstractMigration;

class Version451 extends AbstractMigration
{
    public $autoId = false;

    public function up()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $table_prefix = $this->getAdapter()->getOption('table_prefix');

        $this->execute("UPDATE `{$table_prefix}users` SET `plan_id`=1 WHERE `plan_id`=0;");
    }

    public function down()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $table_prefix = $this->getAdapter()->getOption('table_prefix');
    }
}
