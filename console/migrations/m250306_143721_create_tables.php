<?php

use yii\db\Migration;
use Faker\Factory as Faker;

/**
 * Class m250306_143721_create_tables
 */
class m250306_143721_create_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Таблица posts
        $this->createTable('{{%posts}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'text' => $this->text()->notNull(),
            'fields' => $this->text(),
            'created_by' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        // Внешний ключ на пользователя (автора поста)
        $this->addForeignKey(
            'fk-posts-created_by',
            '{{%posts}}',
            'created_by',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // Таблица posts_visitors (просмотры постов)
        $this->createTable('{{%posts_visitors}}', [
            'id' => $this->primaryKey(),
            'post_id' => $this->integer()->notNull(),
            'visitor_id' => $this->integer()->notNull(),
            'view_at' => $this->integer()->notNull(),
        ]);

        // Внешние ключи для posts_visitors
        $this->addForeignKey('fk-posts_visitors-post_id', '{{%posts_visitors}}', 'post_id', '{{%posts}}', 'id', 'CASCADE');
        $this->addForeignKey('fk-posts_visitors-visitor_id', '{{%posts_visitors}}', 'visitor_id', '{{%user}}', 'id', 'CASCADE');

        // Таблица posts_track (подписки на посты)
        $this->createTable('{{%posts_track}}', [
            'id' => $this->primaryKey(),
            'post_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'track_at' => $this->integer()->notNull(),
        ]);

        // Внешние ключи для posts_track
        $this->addForeignKey('fk-posts_track-post_id', '{{%posts_track}}', 'post_id', '{{%posts}}', 'id', 'CASCADE');
        $this->addForeignKey('fk-posts_track-user_id', '{{%posts_track}}', 'user_id', '{{%user}}', 'id', 'CASCADE');

        // Генерация данных с использованием Faker
        $this->generateFakeData();
    }

    public function safeDown()
    {
        // Удаление таблиц в порядке зависимости
        $this->dropForeignKey('fk-posts_track-user_id', '{{%posts_track}}');
        $this->dropForeignKey('fk-posts_track-post_id', '{{%posts_track}}');
        $this->dropTable('{{%posts_track}}');

        $this->dropForeignKey('fk-posts_visitors-visitor_id', '{{%posts_visitors}}');
        $this->dropForeignKey('fk-posts_visitors-post_id', '{{%posts_visitors}}');
        $this->dropTable('{{%posts_visitors}}');

        $this->dropForeignKey('fk-posts-created_by', '{{%posts}}');
        $this->dropTable('{{%posts}}');
    }

    private function generateFakeData()
    {
        $faker = Faker::create();
        $userIds = $this->getDb()->createCommand('SELECT id FROM {{%user}}')->queryColumn();

        $batchSize = 10000; // Размер пакета
        $postData = [];
        $postViewsData = [];
        $postSubscribersData = [];

        for ($i = 1; $i <= 1000000; $i++) {
            $createdAt = $faker->dateTimeBetween('-1 year', 'now')->getTimestamp();
            $updatedAt = $faker->dateTimeBetween('-1 year', 'now')->getTimestamp();
            $userId = $faker->randomElement($userIds);

            $postData[] = [
                'name' => $faker->sentence(6),
                'text' => $faker->paragraph(10),
                'fields' => $faker->text(50),
                'created_by' => $userId,
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ];

            // Генерация просмотров
            $viewsCount = $faker->numberBetween(100, 150);
            for ($view = 0; $view < $viewsCount; $view++) {
                $postViewsData[] = [
                    'post_id' => $i,
                    'visitor_id' => $faker->randomElement($userIds),
                    'view_at' => $faker->dateTimeBetween('-1 year', 'now')->getTimestamp(),
                ];
            }

            // Генерация подписчиков
            $subscriberCount = $faker->numberBetween(10, 15);
            $subscribers = $faker->randomElements($userIds, $subscriberCount, false);
            foreach ($subscribers as $subscriber) {
                $postSubscribersData[] = [
                    'post_id' => $i,
                    'user_id' => $subscriber,
                    'track_at' => $faker->dateTimeBetween('-1 year', 'now')->getTimestamp(),
                ];
            }

            // Вставляем пакетами
            if ($i % $batchSize === 0) {
                $this->batchInsert('{{%posts}}', ['name', 'text', 'fields', 'created_by', 'created_at', 'updated_at'], $postData);
                $this->batchInsert('{{%posts_visitors}}', ['post_id', 'visitor_id', 'view_at'], $postViewsData);
                $this->batchInsert('{{%posts_track}}', ['post_id', 'user_id', 'track_at'], $postSubscribersData);

                $postData = [];
                $postViewsData = [];
                $postSubscribersData = [];
            }
        }

        // Вставляем оставшиеся записи
        if (!empty($postData)) {
            $this->batchInsert('{{%posts}}', ['name', 'text', 'fields', 'created_by', 'created_at', 'updated_at'], $postData);
        }
        if (!empty($postViewsData)) {
            $this->batchInsert('{{%posts_visitors}}', ['post_id', 'visitor_id', 'view_at'], $postViewsData);
        }
        if (!empty($postSubscribersData)) {
            $this->batchInsert('{{%posts_track}}', ['post_id', 'user_id', 'track_at'], $postSubscribersData);
        }
    }
}
