<?php

use yii\db\Schema;
use yii\db\Migration;

class m150914_004931_create_customer_service_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }


        $this->createTable('{{%customer_service}}', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . '(32) NOT NULL',
            'account' => Schema::TYPE_STRING . '(255) NOT NULL',
            'begintime'=>Schema::TYPE_SMALLINT . ' NOT NULL',
            'endtime'=>Schema::TYPE_SMALLINT . ' NOT NULL',
            'status' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 0',
        ], $tableOptions);
    }

    public function down()
    {
        echo "m150914_004931_create_customer_service_table cannot be reverted.\n";

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
