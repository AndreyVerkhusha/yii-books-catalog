<?php

use yii\db\Migration;

class m260304_023046_insert_demo_user extends Migration
{
    /**
     * {@inheritdoc}
     * @throws Exception
     */
    public function safeUp()
    {
        $currentTime = time();

        $this->insert('user', [
            'username' => 'admin',
            'password_hash' => Yii::$app->security->generatePasswordHash('pass'),
            'auth_key' => Yii::$app->security->generateRandomString(),
            'created_at' => $currentTime,
            'updated_at' => $currentTime,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('user', ['username' => 'admin']);
    }
}
