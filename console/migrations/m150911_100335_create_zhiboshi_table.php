<?php

use yii\db\Schema;
use yii\db\Migration;

class m150911_100335_create_zhiboshi_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%zhibo}}', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . '(255) NOT NULL',
            'title' => Schema::TYPE_STRING . '(255) NOT NULL',
            'keyword' => Schema::TYPE_STRING . '(500) NOT NULL',
            'description' => Schema::TYPE_STRING . '(1024) NOT NULL',
            'announcement'=>Schema::TYPE_TEXT. ' NULL DEFAULT ""',
            'logo'=>Schema::TYPE_STRING . '(255) NOT NULL',
            'allowroles'=>Schema::TYPE_TEXT . ' NULL DEFAULT ""',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'loadguest'=>Schema::TYPE_SMALLINT . ' DEFAULT 0',
            'status'=>Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 1',
        ], $tableOptions);
    }

    public function down()
    {
        echo "m150911_100335_create_zhiboshi_table cannot be reverted.\n";

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
