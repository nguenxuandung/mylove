<?php

use Migrations\AbstractMigration;

class Version601 extends AbstractMigration
{
    public $autoId = false;

    public function up()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $table_prefix = $this->getAdapter()->getOption('table_prefix');

        if (!(bool)$this->getOption('enable_premium_membership', 0)) {
            $builder = $this->getQueryBuilder();
            $builder
                ->update('plans')
                ->set('edit_link', 1)
                ->set('edit_long_url', 1)
                ->set('link_expiration', 1)
                ->set('multi_domains', 1)
                ->set('alias', 1)
                ->set('referral', 1)
                ->set('stats', 1)
                ->set('api_quick', 1)
                ->set('api_mass', 1)
                ->set('api_full', 1)
                ->set('api_developer', 1)
                ->set('bookmarklet', 1)
                ->where(['id' => 1])
                ->execute();
        }
    }

    protected function getOption($name, $default = '')
    {
        $table_prefix = $this->getAdapter()->getOption('table_prefix');

        $row = $this->fetchRow("SELECT * FROM `{$table_prefix}options` WHERE `name` = '{$name}'");

        if (empty($row)) {
            return $default;
        }

        return $row['value'];
    }
}
