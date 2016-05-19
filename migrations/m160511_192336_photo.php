<?php

use yii\db\Migration;

class m160511_192336_photo extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%photo}}',[
                'id' => $this->primaryKey(),
                'parent_id' => $this->integer(),
                'title' => $this->string()->notNull(),
                'address' => $this->string()->notNull(),
                'file' => $this->string()->notNull(),
                'thumbnail' => $this->string()->notNull(),
                'created_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('idx-photo-parent_id','{{%photo}}','parent_id');
        $this->addForeignKey(
            'fk_photo_parent_id',
            '{{%photo}}',
            'parent_id',
            '{{%album}}',
            'id',
            'SET NULL',
            'RESTRICT'
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%photo}}');
    }
}
