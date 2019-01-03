<?php

use yii\db\Schema;
use yii\db\Migration;

class m150911_094352_create_configcategory_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%config_category}}', [
            'id' => Schema::TYPE_PK,
            'parentid' => Schema::TYPE_INTEGER . '(11) NULL DEFAULT 0',
            'name' => Schema::TYPE_STRING . '(255) NOT NULL',
            'alias' => Schema::TYPE_STRING . '(255) NOT NULL',
            'status'=>Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 1',
        ], $tableOptions);
    }

    public function down()
    {
        echo "m150911_094352_create_configcategory_table cannot be reverted.\n";

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
