<?php

namespace console\controllers;

use yii\console\Controller;
use Faker\Factory as Faker;
use Yii;

/**
 * Управление пользователями через консоль.
 */
class UsersController extends Controller
{
    /**
     * Генерация пользователей в БД.
     * @param int $start Начальный индекс пользователя
     * @param int $end Конечный индекс пользователя
     */
    public function actionGenerateUsers($start = 1, $end = 100)
    {
        $faker = Faker::create();
        $batchSize = 10; // Размер партии для одновременной записи
        $rows = [];
        
        echo "Генерация пользователей с ID $start до $end...\n";
        
        for ($i = $start; $i <= $end; $i++) {
            $username = 'user' . $i;
            $email = $faker->email;
            $passwordHash = Yii::$app->security->generatePasswordHash('password');
            $authKey = Yii::$app->security->generateRandomString();
            $createdAt = time();
            $updatedAt = time();
            $lastLoginAt = time() - rand(0, 30 * 24 * 60 * 60);
            $registrationIp = $faker->ipv4;
            
            echo "{$username}\n";
            
            $rows[] = [
                $username,
                $email,
                $passwordHash,
                $authKey,
                null,  // password_reset_token
                10,    // status (по умолчанию)
                $createdAt,
                $updatedAt,
                $lastLoginAt,
                $registrationIp,
            ];
            
            // Если собрано 100 записей или это последняя итерация, делаем вставку
            if (count($rows) >= $batchSize || $i == $end) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    Yii::$app->db->createCommand()->batchInsert('{{%user}}', [
                        'username',
                        'email',
                        'password_hash',
                        'auth_key',
                        'password_reset_token',
                        'status',
                        'created_at',
                        'updated_at',
                        'last_login_at',
                        'registration_ip',
                    ], $rows)->execute();
                    $transaction->commit();
                    echo "Вставлено " . count($rows) . " пользователей (транзакция завершена).\n";
                    // Очищаем массив для следующей партии
                    $rows = [];
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    echo "Ошибка при вставке пользователей до #$i: " . $e->getMessage() . "\n";
                }
            }
        }
        
        echo "Генерация завершена!\n";
    }
}