<?php

use yii\db\Migration;

/**
 * Handles the creation of table `subscription`.
 */
class m260303_205953_create_subscription_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('subscription', [
            'id' => $this->primaryKey(),
            'author_id' => $this->integer()->notNull(),
            'phone' => $this->string(32)->notNull(),
            'created_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex(
            'idx_subscription_author_id_phone',
            'subscription',
            ['author_id', 'phone'],
            true
        );

        $this->addForeignKey(
            'fk_subscription_author_id',
            'subscription',
            'author_id',
            'author',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('subscription');
    }
}
