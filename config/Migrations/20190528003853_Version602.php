<?php

use Migrations\AbstractMigration;

class Version602 extends AbstractMigration
{
    public $autoId = false;

    public function up()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $table_prefix = $this->getAdapter()->getOption('table_prefix');

        $this->table('remember_tokens')
            ->removeIndexByName('idx_selector')
            ->removeIndexByName('idx_userid')
            ->addIndex('selector', ['name' => 'idx_selector', 'unique' => true])
            ->addIndex('user_id', ['name' => 'idx_userid'])
            ->update();
    }
}
