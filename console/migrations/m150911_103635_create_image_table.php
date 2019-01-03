<?php

use yii\db\Schema;
use yii\db\Migration;

class m150911_103635_create_image_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%image}}', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . '(50) NOT NULL',
            'address' => Schema::TYPE_STRING . '(255) NOT NULL',
            'data' => Schema::TYPE_DATETIME . ' NOT NULL',
            'isdefault' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 0',
        ], $tableOptions);
    }

    public function down()
    {
        echo "m150911_103635_create_image_table cannot be reverted.\n";

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
