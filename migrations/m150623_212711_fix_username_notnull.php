<?php

use app\migrations\Migration;

class m150623_212711_fix_username_notnull extends Migration
{
    public function up()
    {
        if ($this->dbType == 'pgsql') {
            $this->alterColumn('{{%user}}', 'username', 'SET NOT NULL');
        } else {
            if ($this->dbType == 'sqlsrv') {
                $this->dropIndex('{{%user_unique_username}}', '{{%user}}');
            }
            $this->alterColumn('{{%user}}', 'username', $this->string(255)->notNull());
            if ($this->dbType == 'sqlsrv') {
                $this->createIndex('{{%user_unique_username}}', '{{%user}}', 'username', true);
            }
        }
    }

    public function down()
    {
        if ($this->dbType == "pgsql") {
            $this->alterColumn('{{%user}}', 'username', 'DROP NOT NULL');
        } else {
            if ($this->dbType == 'sqlsrv') {
                $this->dropIndex('{{%user_unique_username}}', '{{%user}}');
            }
            $this->alterColumn('{{%user}}', 'username', $this->string(255)->null());
            if ($this->dbType == 'sqlsrv') {
                $this->createIndex('{{%user_unique_username}}', '{{%user}}', 'username', true);
            }
        }
    }
}
