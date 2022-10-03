<?php

use Migrations\AbstractMigration;

class Version610 extends AbstractMigration
{
    public $autoId = false;

    public function up()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $table_prefix = $this->getAdapter()->getOption('table_prefix');

        $withdraw_methods = json_decode($this->getOption('withdraw_methods'));

        foreach ($withdraw_methods as &$method) {
            $method->id = \Cake\Utility\Text::slug($method->id);
        }

        $builder = $this->getQueryBuilder();
        $builder
            ->update('options')
            ->set('value', json_encode($withdraw_methods))
            ->where(['name' => 'withdraw_methods'])
            ->execute();

        $users = $this->fetchAll("SELECT `id`, `withdrawal_method` FROM `users`;");
        foreach ($users as $user) {
            $user_id = $user['id'];
            $method = \Cake\Utility\Text::slug($user['withdrawal_method']);
            $this->execute("UPDATE `users` SET `withdrawal_method` = '{$method}' WHERE `id` = {$user_id};");
        }

        $withdraws = $this->fetchAll("SELECT `id`, `method` FROM `withdraws`;");
        foreach ($withdraws as $withdraw) {
            $withdraw_id = $withdraw['id'];
            $method = \Cake\Utility\Text::slug($withdraw['method']);
            $this->execute("UPDATE `withdraws` SET `method` = '{$method}' WHERE `id` = {$withdraw_id};");
        }

        $this->execute("UPDATE `users` SET `plan_id`=1 WHERE `plan_id`=0;");

        $this->execute("ALTER TABLE `users` CHANGE `plan_id` `plan_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT '1';");
    }

    protected function getOption($name, $default = '')
    {
        $row = $this->fetchRow("SELECT * FROM `options` WHERE `name` = '{$name}'");

        if (empty($row)) {
            return $default;
        }

        return $row['value'];
    }
}
