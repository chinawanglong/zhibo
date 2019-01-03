<?php

use yii\db\Schema;
use yii\db\Migration;

class m150921_094025_create_ads_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }


        $this->createTable('{{%ads}}', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . '(100) NOT NULL',
            'url' => Schema::TYPE_STRING . '(500) NOT NULL',
            'image' => Schema::TYPE_STRING . '(200) NOT NULL',
            'status' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 0',
        ], $tableOptions);

    }

    public function down()
    {
        echo "m150921_094025_create_ads_table cannot be reverted.\n";

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
