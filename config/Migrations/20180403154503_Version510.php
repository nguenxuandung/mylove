<?php

use Migrations\AbstractMigration;

class Version510 extends AbstractMigration
{
    public $autoId = false;

    public function up()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $table_prefix = $this->getAdapter()->getOption('table_prefix');

        $rows = [
            [
                'name' => 'alert_admin_new_user_register',
                'value' => 0,
            ],
            [
                'name' => 'alert_admin_new_withdrawal',
                'value' => 1,
            ],
            [
                'name' => 'alert_admin_created_invoice',
                'value' => 0,
            ],
            [
                'name' => 'alert_admin_paid_invoice',
                'value' => 1,
            ],
            [
                'name' => 'alert_member_approved_withdraw',
                'value' => 1,
            ],
            [
                'name' => 'alert_member_completed_withdraw',
                'value' => 1,
            ],
            [
                'name' => 'store_only_paid_clicks_statistics',
                'value' => (is_app_installed()) ? 0 : 1,
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
    }
}
