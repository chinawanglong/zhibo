<?php

use yii\db\Schema;
use yii\db\Migration;

class m150911_095610_create_configitems_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%config_items}}', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . '(255) NOT NULL',
            'zh_name' => Schema::TYPE_STRING . '(255) NOT NULL',
            'desc' => Schema::TYPE_STRING . '(255) NOT NULL',
            'categoryid' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'val' => Schema::TYPE_TEXT. ' NULL DEFAULT ""',
            'status'=>Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 1',
        ], $tableOptions);
    }

    public function down()
    {
        echo "m150911_095610_create_configitems_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
