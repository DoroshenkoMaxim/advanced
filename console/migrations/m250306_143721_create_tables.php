<?php

use yii\db\Migration;

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
}
