<?php

use Migrations\AbstractMigration;

class Version260 extends AbstractMigration
{
    public $autoId = false;

    public function up()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $table_prefix = $this->getAdapter()->getOption('table_prefix');

        $rows = [
            [
                'name' => 'campaign_paid_views_day',
                'value' => '1',
            ],
            [
                'name' => 'mass_shrinker_default_advert',
                'value' => '1',
            ],
            [
                'name' => 'minimum_withdrawal_amount',
                'value' => '5',
            ],
        ];

        $this->table('options')
            ->insert($rows)
            ->saveData();

        $this->table('statistics')
            ->addColumn('reason', 'integer', [
                'comment' => '1 = Earn, 2 = Disabled cookie, 3= Anonymous user, 4 = Adblock, 5 = Proxy, ' .
                    '6 = IP changed, 7 = Not unique, 8 = Full weight, 9 = Default campaign',
                'default' => 0,
                'limit' => 10,
                'null' => false,
                'signed' => false,
                'after' => 'user_agent',
            ])
            ->update();
    }

    public function down()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $table_prefix = $this->getAdapter()->getOption('table_prefix');

        $items = implode(",", [
            "'campaign_paid_views_day'",
            "'mass_shrinker_default_advert'",
            "'minimum_withdrawal_amount'",
        ]);
        $this->execute("DELETE FROM `{$table_prefix}options` WHERE `name` IN ({$items});");

        $this->table('statistics')
            ->removeColumn('reason')
            ->update();
    }
}
