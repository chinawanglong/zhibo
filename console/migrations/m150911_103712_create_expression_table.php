<?php

use yii\db\Schema;
use yii\db\Migration;

class m150911_103712_create_expression_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }


        $this->createTable('{{%expression}}', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . '(50) NOT NULL',
            'address' => Schema::TYPE_STRING . '(255) NOT NULL',
            'prefix' => Schema::TYPE_STRING . '(50) NOT NULL',
            'type' => Schema::TYPE_SMALLINT . 'NOT NULL DEFAULT 1',
            'status' => Schema::TYPE_SMALLINT . ' NOT NULL',
        ], $tableOptions);
    }

    public function down()
    {
        echo "m150911_103712_create_expression_table cannot be reverted.\n";

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
