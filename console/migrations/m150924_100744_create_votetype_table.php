<?php

use yii\db\Schema;
use yii\db\Migration;

class m150924_100744_create_votetype_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%votetype}}', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . '(32) NOT NULL',
            'options' => Schema::TYPE_STRING . '(100) NOT NULL',
            'interval' => Schema::TYPE_SMALLINT . '(4) NOT NULL',
            'minlimit' => Schema::TYPE_SMALLINT . '(4) NULL DEFAULT 0',
            'status'=>Schema::TYPE_SMALLINT . '(4) NULL DEFAULT 0',
            'btime' => Schema::TYPE_STRING . '(50) NOT NULL',
            'etime' => Schema::TYPE_STRING . '(50) NOT NULL',
            'changes' => Schema::TYPE_SMALLINT . '(4) NULL DEFAULT 0',
            'allowyou' => Schema::TYPE_SMALLINT . '(4) NULL DEFAULT 1',
        ], $tableOptions);
    }

    public function down()
    {
        echo "m150924_100744_create_votetype_table cannot be reverted.\n";

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
