<?php

use yii\db\Migration;
use Faker\Factory as Faker;

/**
 * Class m250305_154153_generate_users
 */
class m250305_154153_generate_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Генерация тестовых пользователей
        $this->generateFakeUsers();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }

    private function generateFakeUsers()
    {
        $faker = Faker::create();

        for ($i = 1; $i <= 100; $i++) {
            $username = 'user' . $i;
            $email = $faker->unique()->email;
            $passwordHash = Yii::$app->security->generatePasswordHash('password');
            $authKey = Yii::$app->security->generateRandomString();
            $createdAt = $faker->dateTimeBetween('-1 year', 'now')->getTimestamp();
            $updatedAt = $faker->dateTimeBetween('-1 year', 'now')->getTimestamp();
            $lastLoginAt = time() - rand(0, 30 * 24 * 60 * 60);
            $registrationIp = $faker->ipv4;

            $this->insert('{{%user}}', [
                'username' => $username,
                'email' => $email,
                'password_hash' => $passwordHash,
                'auth_key' => $authKey,
                'password_reset_token' => null,
                'status' => 10,
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
                'last_login_at' => $lastLoginAt,
                'registration_ip' => $registrationIp,
            ]);
        }
    }
}