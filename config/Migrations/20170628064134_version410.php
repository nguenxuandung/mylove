<?php

use Migrations\AbstractMigration;

class Version410 extends AbstractMigration
{
    public $autoId = false;

    public function up()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $table_prefix = $this->getAdapter()->getOption('table_prefix');

        $this->table('statistics')
            ->removeIndexByName('idx_ownerearn')
            ->removeIndexByName('idx_publisherearn_userid')
            ->removeIndexByName('idx_created')
            ->addIndex(['created', 'user_id'], ['name' => 'idx_created_userid'])
            ->update();

        $this->table('withdraws')
            ->changeColumn('status', 'integer', [
                'comment' => '1=Approved, 2=Pending, 3=Complete, 4=Cancelled',
                'default' => 0,
                'limit' => 2,
                'null' => false,
                'signed' => false,
            ])
            ->addColumn('account', 'string', [
                'after' => 'method',
                'default' => '',
                'limit' => 256,
                'null' => false,
            ])
            ->update();

        /**
         * announcements table
         */
        $this->execute("ALTER TABLE `{$table_prefix}announcements` CHANGE `id` " .
            "`id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT;");

        /**
         * campaigns table
         */
        $this->execute("ALTER TABLE `{$table_prefix}campaigns` " .
            "CHANGE `id` `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT, " .
            "CHANGE `user_id` `user_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT '0', " .
            "CHANGE `price` `price` DECIMAL(50,9) NOT NULL DEFAULT '0';");

        /**
         * campaign_items table
         */
        $this->execute("ALTER TABLE `{$table_prefix}campaign_items` " .
            "CHANGE `id` `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT, " .
            "CHANGE `campaign_id` `campaign_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT '0'," .
            "CHANGE `advertiser_price` `advertiser_price` DECIMAL(50,9) NOT NULL DEFAULT '0', " .
            "CHANGE `publisher_price` `publisher_price` DECIMAL(50,9) NOT NULL DEFAULT '0';");

        /**
         * i18n table
         */
        $this->execute("ALTER TABLE `{$table_prefix}i18n` CHANGE `id` " .
            "`id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT;");

        /**
         * invoices table
         */
        $this->execute("ALTER TABLE `{$table_prefix}invoices` " .
            "CHANGE `id` `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT, " .
            "CHANGE `user_id` `user_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT '0', " .
            "CHANGE `rel_id` `rel_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT '0', " .
            "CHANGE `amount` `amount` DECIMAL(50,9) NOT NULL DEFAULT '0';");

        /**
         * links table
         */
        $this->execute("ALTER TABLE `{$table_prefix}links` " .
            "CHANGE `id` `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT, " .
            "CHANGE `user_id` `user_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT '0';");

        /**
         * options table
         */
        $this->execute("ALTER TABLE `{$table_prefix}options` CHANGE `id` " .
            "`id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT;");

        /**
         * pages table
         */
        $this->execute("ALTER TABLE `{$table_prefix}pages` CHANGE `id` " .
            "`id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT;");

        /**
         * plans table
         */
        $this->execute("ALTER TABLE `{$table_prefix}plans` CHANGE `id` " .
            "`id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT, " .
            "CHANGE `monthly_price` `monthly_price` DECIMAL(50,9) NOT NULL DEFAULT '0', " .
            "CHANGE `yearly_price` `yearly_price` DECIMAL(50,9) NOT NULL DEFAULT '0';");

        /**
         * posts table
         */
        $this->execute("ALTER TABLE `{$table_prefix}posts` CHANGE `id` " .
            "`id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT;");

        /**
         * social_profiles table
         */
        $this->execute("ALTER TABLE `{$table_prefix}social_profiles` " .
            "CHANGE `id` `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT, " .
            "CHANGE `user_id` `user_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT '0';");

        /**
         * statistics table
         */
        $this->execute("ALTER TABLE `{$table_prefix}statistics` " .
            "CHANGE `id` `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT, " .
            "CHANGE `link_id` `link_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT '0', " .
            "CHANGE `user_id` `user_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT '0', " .
            "CHANGE `referral_id` `referral_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT '0', " .
            "CHANGE `campaign_id` `campaign_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT '0', " .
            "CHANGE `campaign_user_id` `campaign_user_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT '0', " .
            "CHANGE `campaign_item_id` `campaign_item_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT '0', " .
            "CHANGE `owner_earn` `owner_earn` DECIMAL(50,9) NOT NULL DEFAULT '0', " .
            "CHANGE `publisher_earn` `publisher_earn` DECIMAL(50,9) NOT NULL DEFAULT '0', " .
            "CHANGE `referral_earn` `referral_earn` DECIMAL(50,9) NOT NULL DEFAULT '0';");

        /**
         * testimonials table
         */
        $this->execute("ALTER TABLE `{$table_prefix}testimonials` CHANGE `id` " .
            "`id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT;");

        /**
         * users table
         */
        $this->execute("ALTER TABLE `{$table_prefix}users` " .
            "CHANGE `id` `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT, " .
            "CHANGE `plan_id` `plan_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT '1', " .
            "CHANGE `referred_by` `referred_by` BIGINT(20) UNSIGNED NOT NULL DEFAULT '0', " .
            "CHANGE `wallet_money` `wallet_money` DECIMAL(50,9) NOT NULL DEFAULT '0', " .
            "CHANGE `advertiser_balance` `advertiser_balance` DECIMAL(50,9) NOT NULL DEFAULT '0', " .
            "CHANGE `publisher_earnings` `publisher_earnings` DECIMAL(50,9) NOT NULL DEFAULT '0', " .
            "CHANGE `referral_earnings` `referral_earnings` DECIMAL(50,9) NOT NULL DEFAULT '0';");

        /**
         * withdraws table
         */
        $this->execute("ALTER TABLE `{$table_prefix}withdraws` " .
            "CHANGE `id` `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT, " .
            "CHANGE `user_id` `user_id` BIGINT(20) UNSIGNED NOT NULL DEFAULT '0', " .
            "CHANGE `publisher_earnings` `publisher_earnings` DECIMAL(50,9) NOT NULL DEFAULT '0', " .
            "CHANGE `referral_earnings` `referral_earnings` DECIMAL(50,9) NOT NULL DEFAULT '0', " .
            "CHANGE `amount` `amount` DECIMAL(50,9) NOT NULL DEFAULT '0';");
    }

    public function down()
    {
        $this->execute("SET SESSION sql_mode = ''");

        $table_prefix = $this->getAdapter()->getOption('table_prefix');

        $this->table('statistics')
            ->removeIndexByName('idx_created_userid')
            ->addIndex('owner_earn', ['name' => 'idx_ownerearn'])
            ->addIndex(['publisher_earn', 'user_id'], ['name' => 'idx_publisherearn_userid'])
            ->addIndex('created', ['name' => 'idx_created'])
            ->update();

        $this->table('withdraws')
            ->changeColumn('status', 'integer', [
                'comment' => '1=Approved, 2=Pending, 3=Complete',
                'default' => 0,
                'limit' => 2,
                'null' => false,
                'signed' => false,
            ])
            ->removeColumn('account')
            ->update();

        /**
         * announcements table
         */
        $this->execute("ALTER TABLE `{$table_prefix}announcements` CHANGE `id` " .
            "`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;");

        /**
         * campaigns table
         */
        $this->execute("ALTER TABLE `{$table_prefix}campaigns` " .
            "CHANGE `id` `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT, " .
            "CHANGE `user_id` `user_id` INT(10) UNSIGNED NOT NULL DEFAULT '0';");

        /**
         * campaign_items table
         */
        $this->execute("ALTER TABLE `{$table_prefix}campaign_items` " .
            "CHANGE `id` `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT, " .
            "CHANGE `campaign_id` `campaign_id` INT(10) UNSIGNED NOT NULL DEFAULT '0';");

        /**
         * i18n table
         */
        $this->execute("ALTER TABLE `{$table_prefix}i18n` CHANGE `id` " .
            "`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;");

        /**
         * invoices table
         */
        $this->execute("ALTER TABLE `{$table_prefix}invoices` " .
            "CHANGE `id` `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT, " .
            "CHANGE `user_id` `user_id` INT(10) UNSIGNED NOT NULL DEFAULT '0', " .
            "CHANGE `rel_id` `rel_id` INT(10) UNSIGNED NOT NULL DEFAULT '0';");

        /**
         * links table
         */
        $this->execute("ALTER TABLE `{$table_prefix}links` " .
            "CHANGE `id` `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT, " .
            "CHANGE `user_id` `user_id` INT(10) UNSIGNED NOT NULL DEFAULT '0';");

        /**
         * options table
         */
        $this->execute("ALTER TABLE `{$table_prefix}options` CHANGE `id` " .
            "`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;");

        /**
         * pages table
         */
        $this->execute("ALTER TABLE `{$table_prefix}pages` CHANGE `id` " .
            "`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;");

        /**
         * plans table
         */
        $this->execute("ALTER TABLE `{$table_prefix}plans` CHANGE `id` " .
            "`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;");

        /**
         * posts table
         */
        $this->execute("ALTER TABLE `{$table_prefix}posts` CHANGE `id` " .
            "`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;");

        /**
         * social_profiles table
         */
        $this->execute("ALTER TABLE `{$table_prefix}social_profiles` " .
            "CHANGE `id` `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT, " .
            "CHANGE `user_id` `user_id` INT(10) UNSIGNED NOT NULL DEFAULT '0';");

        /**
         * statistics table
         */
        $this->execute("ALTER TABLE `{$table_prefix}statistics` " .
            "CHANGE `id` `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT, " .
            "CHANGE `link_id` `link_id` INT(10) UNSIGNED NOT NULL DEFAULT '0', " .
            "CHANGE `user_id` `user_id` INT(10) UNSIGNED NOT NULL DEFAULT '0', " .
            "CHANGE `referral_id` `referral_id` INT(10) UNSIGNED NOT NULL DEFAULT '0', " .
            "CHANGE `campaign_id` `campaign_id` INT(10) UNSIGNED NOT NULL DEFAULT '0', " .
            "CHANGE `campaign_user_id` `campaign_user_id` INT(10) UNSIGNED NOT NULL DEFAULT '0', " .
            "CHANGE `campaign_item_id` `campaign_item_id` INT(10) UNSIGNED NOT NULL DEFAULT '0';");

        /**
         * testimonials table
         */
        $this->execute("ALTER TABLE `{$table_prefix}testimonials` CHANGE `id` " .
            "`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT;");

        /**
         * users table
         */
        $this->execute("ALTER TABLE `{$table_prefix}users` " .
            "CHANGE `id` `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT, " .
            "CHANGE `plan_id` `plan_id` INT(10) UNSIGNED NOT NULL DEFAULT '0', " .
            "CHANGE `referred_by` `referred_by` INT(10) UNSIGNED NOT NULL DEFAULT '0';");

        /**
         * withdraws table
         */
        $this->execute("ALTER TABLE `{$table_prefix}withdraws` " .
            "CHANGE `id` `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT, " .
            "CHANGE `user_id` `user_id` INT(10) UNSIGNED NOT NULL DEFAULT '0';");
    }
}
