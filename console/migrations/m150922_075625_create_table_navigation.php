<?php

use yii\db\Schema;
use yii\db\Migration;

class m150922_075625_create_table_navigation extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%navigation}}', [
            'id' => Schema::TYPE_PK,
            'location' => Schema::TYPE_SMALLINT . '(4) NOT NULL',
            'type' => Schema::TYPE_SMALLINT . '(4) NOT NULL',
            'text' => Schema::TYPE_STRING . '(50) NOT NULL',
            'href' => Schema::TYPE_STRING . '(255) NULL',
            'code' => Schema::TYPE_STRING . '(500) NULL DEFAULT ""',
            'content' => Schema::TYPE_STRING . '(500) NULL DEFAULT ""',
            'iframeheight' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'iframewidth' => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'style'=>Schema::TYPE_STRING . '(500) NULL DEFAULT ""',
            'order' => Schema::TYPE_SMALLINT . '(4) NULL DEFAULT 1',
            'status'=>Schema::TYPE_SMALLINT . '(4) NULL DEFAULT 1',
        ], $tableOptions);
    }

    public function down()
    {
        echo "m150922_075625_create_table_navigation cannot be reverted.\n";

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
