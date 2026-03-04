<?php

use yii\db\Migration;

/**
 * Handles the creation of table `book`.
 */
class m260303_205859_create_book_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('book', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'publish_year' => $this->integer()->notNull(),
            'description' => $this->text()->null(),
            'isbn' => $this->string(32)->notNull()->unique(),
            'image_path' => $this->string()->null(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx_book_title', 'book', 'title');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('book');
    }
}
