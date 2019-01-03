<?php

use yii\db\Schema;
use yii\db\Migration;

class m150915_064239_create_chat_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }


        $this->createTable('{{%chat}}', [
            'id' => Schema::TYPE_PK,
            'fid' => Schema::TYPE_STRING . '(32) NOT NULL',
            'content' => Schema::TYPE_STRING . '(500) NOT NULL',
            'ftime' => Schema::TYPE_DATETIME . ' NOT NULL',
            'status' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 0',
            'sname' => Schema::TYPE_STRING . '(32) NOT NULL',
        ], $tableOptions);
    }

    public function down()
    {
        echo "m150915_064239_create_chat_table cannot be reverted.\n";

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
