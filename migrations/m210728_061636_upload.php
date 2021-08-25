<?php

use app\migrations\Migration;

class m210728_061636_upload extends Migration
{
    public function up()
    {
        $this->createTable('{{%upload}}', [
            'id'               => $this->primaryKey(),
            'name'             => $this->string(255)->notNull(),
            'size'             => $this->string(255)->notNull(),
            'type'             => $this->integer()->null(),
            'date'             => $this->string(255)->null(),
            'user_id'          => $this->integer()->null(),
            'unique_name'      => $this->string(255)->null(),
            
        ], $this->tableOptions);
        $this->addForeignKey('{{%fk_user_upload}}', '{{%upload}}', 'user_id', '{{%user}}', 'id', $this->cascade, $this->restrict);
    }

    public function down()
    {
        $ $this->dropTable('{{%opload}}');
    }
}
