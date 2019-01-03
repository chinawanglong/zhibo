<?php

use yii\db\Schema;
use yii\db\Migration;

class m150922_075559_create_table_course extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%course}}', [
            'id' => Schema::TYPE_PK,
            'content'=>Schema::TYPE_TEXT. ' NOT NULL DEFAULT ""',
            'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
            'status'=>Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 1',
        ], $tableOptions);
    }

    public function down()
    {
        echo "m150922_075559_create_table_course cannot be reverted.\n";

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
