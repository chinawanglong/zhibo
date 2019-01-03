<?php

use yii\db\Schema;
use yii\db\Migration;

class m150911_103553_create_tankuang_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%tanchuang}}', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . '(50) NOT NULL',
            'img' => Schema::TYPE_STRING . '(255) NOT NULL',
            'type' => Schema::TYPE_SMALLINT . 'NOT NULL DEFAULT 1',
            'time' => Schema::TYPE_STRING . '(50) NOT NULL DEFAULT 0',
            'boffset' => Schema::TYPE_DOUBLE . ' NOT NULL DEFAULT 0',
            'kfnum' => Schema::TYPE_SMALLINT . 'DEFAULT NUll',
            'showkf' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 1',
        ], $tableOptions);

    }

    public function down()
    {
        echo "m150911_103553_create_tanchuang_table cannot be reverted.\n";

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
