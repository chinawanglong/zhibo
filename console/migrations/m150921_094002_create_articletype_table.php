<?php

use yii\db\Schema;
use yii\db\Migration;

class m150921_094002_create_articletype_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }


        $this->createTable('{{%article_type}}', [
            'id' => Schema::TYPE_PK,
            'code' => Schema::TYPE_STRING . '(100) NOT NULL',
            'tname' => Schema::TYPE_STRING . '(200) NOT NULL',
            'nums' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 0',
        ], $tableOptions);
    }

    public function down()
    {
        echo "m150921_094002_create_articletype_table cannot be reverted.\n";

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
