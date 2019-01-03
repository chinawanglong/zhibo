<?php

use yii\db\Schema;
use yii\db\Migration;

class m150924_100758_create_voteval_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%voteval}}', [
            'id' => Schema::TYPE_PK,
            'vid' => Schema::TYPE_SMALLINT . ' NOT NULL',
            'valdata' => Schema::TYPE_STRING . '(255) NOT NULL',
            'begintime' => Schema::TYPE_DATETIME . ' NOT NULL',
            'endtime' => Schema::TYPE_DATETIME . ' NOT NULL',
        ], $tableOptions);
    }

    public function down()
    {
        echo "m150924_100758_create_voteval_table cannot be reverted.\n";

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
