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
        $batchSize = 10000; // Размер пакета вставки
        $userData = [];

        $passwordHash = Yii::$app->security->generatePasswordHash('password');

        for ($i = 0; $i <= 100000; $i++) {
            $username = 'user' . $i;
            $email = $faker->unique()->email;
            $authKey = Yii::$app->security->generateRandomString();
            $createdAt = $faker->dateTimeBetween('-1 year', 'now')->getTimestamp();
            $updatedAt = $faker->dateTimeBetween('-1 year', 'now')->getTimestamp();
            $lastLoginAt = time() - rand(0, 30 * 24 * 60 * 60);
            $registrationIp = $faker->ipv4;

            $userData[] = [
                $username,
                $email,
                $passwordHash,
                $authKey,
                null, // password_reset_token
                10,   // статус (10 - активный)
                $createdAt,
                $updatedAt,
                $lastLoginAt,
                $registrationIp
            ];

            // Вставляем пакетами
            if ($i % $batchSize === 0 && $i > 0) {
                $this->batchInsert('{{%user}}', [
                    'username',
                    'email',
                    'password_hash',
                    'auth_key',
                    'password_reset_token',
                    'status',
                    'created_at',
                    'updated_at',
                    'last_login_at',
                    'registration_ip'
                ], $userData);

                $userData = []; // очищаем массив после вставки
            }
        }

        // Вставляем оставшиеся записи
        if (!empty($userData)) {
            $this->batchInsert('{{%user}}', [
                'username',
                'email',
                'password_hash',
                'auth_key',
                'password_reset_token',
                'status',
                'created_at',
                'updated_at',
                'last_login_at',
                'registration_ip'
            ], $userData);
        }
    }
}
