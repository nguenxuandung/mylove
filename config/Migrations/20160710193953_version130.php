<?php

use Migrations\AbstractMigration;

class Version130 extends AbstractMigration
{
    public $autoId = false;

    public function up()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $options = $this->table('options');

        $rows = [
            [
                'name' => 'email_from',
                'value' => 'no_reply@' . env('HTTP_HOST', 'localhost'),
            ],
            [
                'name' => 'email_method',
                'value' => 'default',
            ],
            [
                'name' => 'email_smtp_host',
                'value' => '',
            ],
            [
                'name' => 'email_smtp_port',
                'value' => '',
            ],
            [
                'name' => 'email_smtp_username',
                'value' => '',
            ],
            [
                'name' => 'email_smtp_password',
                'value' => '',
            ],
        ];

        $options->insert($rows);
        $options->saveData();
    }

    public function down()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $table_prefix = $this->getAdapter()->getOption('table_prefix');

        $items = implode(",", [
            "'email_from'",
            "'email_method'",
            "'email_smtp_host'",
            "'email_smtp_port'",
            "'email_smtp_username'",
            "'email_smtp_password'",
        ]);
        $this->execute("DELETE FROM `{$table_prefix}options` WHERE `name` IN ({$items});");
    }
}
