<?php

use Migrations\AbstractMigration;

class Version300 extends AbstractMigration
{
    public $autoId = false;

    public function up()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $table_prefix = $this->getAdapter()->getOption('table_prefix');

        $rows = [
            [
                'name' => 'link_info_public',
                'value' => 'yes',
            ],
            [
                'name' => 'link_info_member',
                'value' => 'yes',
            ],
            [
                'name' => 'coinbase_enable',
                'value' => 'no',
            ],
            [
                'name' => 'coinbase_api_key',
                'value' => '',
            ],
            [
                'name' => 'coinbase_api_secret',
                'value' => '',
            ],
            [
                'name' => 'coinbase_sandbox',
                'value' => 'no',
            ],
            [
                'name' => 'banktransfer_enable',
                'value' => 'no',
            ],
            [
                'name' => 'banktransfer_instructions',
                'value' => json_decode('"<p>Transfer the money to the bank account below<\/p>\n' .
                    '<table class=\"table table-striped\">\n    <tr>\n        <td>Account holder<\/td>\n        <td>' .
                    '----------<\/td>\n    <\/tr>\n    <tr>\n        <td>Bank Name<\/td>\n        <td>----------' .
                    '<\/td>\n    <\/tr>\n    <tr>\n        <td>City\/Town<\/td>\n        <td>----------<\/td>\n    ' .
                    '<\/tr>\n    <tr>\n        <td>Country<\/td>\n        <td>----------<\/td>\n    <\/tr>\n    <tr>' .
                    '\n        <td>Account number<\/td>\n        <td>----------<\/td>\n    <\/tr>\n    <tr>\n        ' .
                    '<td>SWIFT<\/td>\n        <td>----------<\/td>\n    <\/tr>\n    <tr>\n        <td>IBAN<\/td>\n' .
                    '        <td>----------<\/td>\n    <\/tr>\n    <tr>\n        <td>Account currency<\/td>\n        ' .
                    '<td>----------<\/td>\n    <\/tr>\n    <tr>\n        <td>Reference<\/td>\n        <td>Invoice ' .
                    '#[invoice_id]<\/td>\n    <\/tr>\n<\/table>"'),
            ],
            [
                'name' => 'home_shortening',
                'value' => 'yes',
            ],
            [
                'name' => 'home_shortening_register',
                'value' => 'no',
            ],
        ];

        $this->table('options')
            ->insert($rows)
            ->saveData();

        $this->table('statistics')
            ->changeColumn('ad_type', 'integer', [
                'comment' => '0=direct, 1=inter., 2=banner',
                'default' => 1,
                'limit' => 2,
                'null' => false,
                'signed' => false,
            ])
            ->changeColumn('reason', 'integer', [
                'comment' => '1=Earn, 2=Disabled cookie, 3=Anonymous user, 4=Adblock, 5=Proxy, 6=IP changed, ' .
                    '7=Not unique, 8=Full weight, 9=Default campaign, 10=direct',
                'default' => 0,
                'limit' => 10,
                'null' => false,
                'signed' => false,
            ])
            ->update();

        $this->execute("UPDATE `{$table_prefix}campaigns` SET `status` = '1' WHERE `status` = 'Active';");
        $this->execute("UPDATE `{$table_prefix}campaigns` SET `status` = '2' WHERE `status` = 'Paused';");
        $this->execute("UPDATE `{$table_prefix}campaigns` SET `status` = '3' WHERE `status` = 'Canceled';");
        $this->execute("UPDATE `{$table_prefix}campaigns` SET `status` = '4' WHERE `status` = 'Finished';");
        $this->execute("UPDATE `{$table_prefix}campaigns` SET `status` = '5' WHERE `status` = 'Under Review';");
        $this->execute("UPDATE `{$table_prefix}campaigns` SET `status` = '6' WHERE `status` = 'Pending Payment';");
        $this->execute("UPDATE `{$table_prefix}campaigns` SET `status` = '7' WHERE `status` = 'Invalid Payment';");
        $this->execute("UPDATE `{$table_prefix}campaigns` SET `status` = '8' WHERE `status` = 'Refunded';");

        $this->table('campaigns')
            ->changeColumn('status', 'integer', [
                'comment' => '1=Active, 2=Paused, 3=Canceled, 4=Finished, 5=Under Review, 6=Pending Payment, ' .
                    '7=Invalid Payment, 8=Refunded',
                'default' => 0,
                'limit' => 2,
                'null' => false,
                'signed' => false,
            ])
            ->update();

        $this->execute("UPDATE `{$table_prefix}links` SET `status` = '1' WHERE `status` = 'active';");
        $this->execute("UPDATE `{$table_prefix}links` SET `status` = '2' WHERE `status` = 'hidden';");
        $this->execute("UPDATE `{$table_prefix}links` SET `status` = '3' WHERE `status` = 'inactive';");

        $this->table('links')
            ->changeColumn('status', 'integer', [
                'comment' => '1=active, 2=hidden, 3=inactive',
                'default' => 0,
                'limit' => 2,
                'null' => false,
                'signed' => false,
            ])
            ->update();

        $this->execute("UPDATE `{$table_prefix}users` SET `status` = '1' WHERE `status` = 'active';");
        $this->execute("UPDATE `{$table_prefix}users` SET `status` = '2' WHERE `status` = 'pending';");
        $this->execute("UPDATE `{$table_prefix}users` SET `status` = '3' WHERE `status` = 'inactive';");

        $this->table('users')
            ->changeColumn('status', 'integer', [
                'comment' => '1=active, 2=pending, 3=inactive',
                'default' => 0,
                'limit' => 2,
                'null' => false,
                'signed' => false,
            ])
            ->renameColumn('withdrawal_email', 'withdrawal_account')
            ->changeColumn('withdrawal_account', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->update();

        $this->execute("UPDATE `{$table_prefix}withdraws` SET `status` = '1' WHERE `status` = 'Approved';");
        $this->execute("UPDATE `{$table_prefix}withdraws` SET `status` = '2' WHERE `status` = 'Pending';");
        $this->execute("UPDATE `{$table_prefix}withdraws` SET `status` = '3' WHERE `status` = 'Complete';");

        $this->table('withdraws')
            ->changeColumn('status', 'integer', [
                'comment' => '1=Approved, 2=Pending, 3=Complete',
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
            "'link_info_public'",
            "'link_info_member'",
            "'coinbase_enable'",
            "'coinbase_api_key'",
            "'coinbase_api_secret'",
            "'coinbase_sandbox'",
            "'banktransfer_enable'",
            "'banktransfer_instructions'",
            "'home_shortening'",
            "'home_shortening_register'",
        ]);
        $this->execute("DELETE FROM `{$table_prefix}options` WHERE `name` IN ({$items});");

        $this->table('statistics')
            ->changeColumn('ad_type', 'integer', [
                'comment' => '1=inter., 2=banner',
                'default' => 1,
                'limit' => 2,
                'null' => false,
                'signed' => false,
            ])
            ->changeColumn('reason', 'integer', [
                'comment' => '1=Earn, 2=Disabled cookie, 3=Anonymous user, 4=Adblock, 5=Proxy, 6=IP changed, 7=Not unique, 8=Full weight, 9=Default campaign',
                'default' => 0,
                'limit' => 10,
                'null' => false,
                'signed' => false,
                'after' => 'user_agent',
            ])
            ->update();

        $this->table('campaigns')
            ->changeColumn('status', 'string', [
                'comment' => 'Under Review, Pending Payment, Canceled Payment, Invalid Payment, Refunded, Active, Paused, Finished, Canceled',
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->update();

        $this->execute("UPDATE `{$table_prefix}campaigns` SET `status` = 'Active' WHERE `status` = '1';");
        $this->execute("UPDATE `{$table_prefix}campaigns` SET `status` = 'Paused' WHERE `status` = '2';");
        $this->execute("UPDATE `{$table_prefix}campaigns` SET `status` = 'Canceled' WHERE `status` = '3';");
        $this->execute("UPDATE `{$table_prefix}campaigns` SET `status` = 'Finished' WHERE `status` = '4';");
        $this->execute("UPDATE `{$table_prefix}campaigns` SET `status` = 'Under Review' WHERE `status` = '5';");
        $this->execute("UPDATE `{$table_prefix}campaigns` SET `status` = 'Pending Payment' WHERE `status` = '6';");
        $this->execute("UPDATE `{$table_prefix}campaigns` SET `status` = 'Invalid Payment' WHERE `status` = '7';");
        $this->execute("UPDATE `{$table_prefix}campaigns` SET `status` = 'Refunded' WHERE `status` = '8';");

        $this->table('links')
            ->changeColumn('status', 'string', [
                'comment' => 'active,inactive,hidden',
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->update();

        $this->execute("UPDATE `{$table_prefix}links` SET `status` = 'active' WHERE `status` = '1';");
        $this->execute("UPDATE `{$table_prefix}links` SET `status` = 'hidden' WHERE `status` = '2';");
        $this->execute("UPDATE `{$table_prefix}links` SET `status` = 'inactive' WHERE `status` = '3';");

        $this->table('users')
            ->changeColumn('status', 'string', [
                'comment' => 'active,inactive,pending',
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->renameColumn('withdrawal_account', 'withdrawal_email')
            ->changeColumn('withdrawal_email', 'string', [
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->update();

        $this->execute("UPDATE `{$table_prefix}users` SET `status` = 'active' WHERE `status` = '1';");
        $this->execute("UPDATE `{$table_prefix}users` SET `status` = 'pending' WHERE `status` = '2';");
        $this->execute("UPDATE `{$table_prefix}users` SET `status` = 'inactive' WHERE `status` = '3';");

        $this->table('withdraws')
            ->changeColumn('status', 'string', [
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->update();

        $this->execute("UPDATE `{$table_prefix}withdraws` SET `status` = 'Approved' WHERE `status` = '1';");
        $this->execute("UPDATE `{$table_prefix}withdraws` SET `status` = 'Pending' WHERE `status` = '2';");
        $this->execute("UPDATE `{$table_prefix}withdraws` SET `status` = 'Complete' WHERE `status` = '3';");
    }
}
