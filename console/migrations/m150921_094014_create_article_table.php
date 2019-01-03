<?php

use yii\db\Schema;
use yii\db\Migration;

class m150921_094014_create_article_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }


        $this->createTable('{{%article}}', [
            'id' => Schema::TYPE_PK,
            'typeid' => Schema::TYPE_SMALLINT . '(32) NOT NULL',
            'title' => Schema::TYPE_STRING . '(100) NOT NULL',
            'description' => Schema::TYPE_STRING . '(200) NOT NULL',
            'keyword' => Schema::TYPE_STRING . '(500) NOT NULL',
            'content' => Schema::TYPE_TEXT . ' NOT NULL',
            'addtime' => Schema::TYPE_DATETIME . ' NOT NULL',
            'stname' => Schema::TYPE_TEXT . ' DEFAULT NULL',
            'htmlpath' => Schema::TYPE_TEXT . ' DEFAULT NULL',
        ], $tableOptions);

    }

    public function down()
    {
        echo "m150921_094014_create_article_table cannot be reverted.\n";

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
