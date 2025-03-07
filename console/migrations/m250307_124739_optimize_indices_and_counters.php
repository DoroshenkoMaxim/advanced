<?php

use yii\db\Migration;

/**
 * Class m250307_124739_optimize_indices_and_counters
 */
class m250307_124739_optimize_indices_and_counters extends Migration
{
    public function safeUp()
    {
        // 1) Добавляем столбцы для хранения счётчиков
        $this->addColumn('{{%posts}}', 'views_count', $this->integer()->notNull()->defaultValue(0));
        $this->addColumn('{{%posts}}', 'followers_count', $this->integer()->notNull()->defaultValue(0));

        // 2) Создаём индексы по полям сортировок/поиска
        $this->createIndex('idx-posts-name', '{{%posts}}', 'name');
        $this->createIndex('idx-posts-created_at', '{{%posts}}', 'created_at');
        $this->createIndex('idx-posts-created_by', '{{%posts}}', 'created_by');
        $this->createIndex('idx-posts-views_count', '{{%posts}}', 'views_count');
        $this->createIndex('idx-posts-followers_count', '{{%posts}}', 'followers_count');

        // Индексы для posts_visitors
        $this->createIndex('idx-posts_visitors-post_id-visitor_id', '{{%posts_visitors}}', ['post_id', 'visitor_id']);
        $this->createIndex('idx-posts_visitors-visitor_id', '{{%posts_visitors}}', 'visitor_id');

        // Индексы для posts_track
        $this->createIndex('idx-posts_track-post_id-user_id', '{{%posts_track}}', ['post_id', 'user_id']);
        $this->createIndex('idx-posts_track-user_id', '{{%posts_track}}', 'user_id');

        // 3) Инициализируем поля счётчиков реальными значениями
        $this->execute("
            UPDATE posts p
            LEFT JOIN (
                SELECT post_id, COUNT(*) as cnt
                FROM posts_visitors
                GROUP BY post_id
            ) v ON v.post_id = p.id
            LEFT JOIN (
                SELECT post_id, COUNT(*) as cnt
                FROM posts_track
                GROUP BY post_id
            ) t ON t.post_id = p.id
            SET p.views_count = IFNULL(v.cnt, 0),
                p.followers_count = IFNULL(t.cnt, 0)
        ");

        // 4) Создаём триггеры для обновления счётчиков (пример для InnoDB/MySQL)
        // Триггер на вставку в posts_visitors
        $this->execute("
            CREATE TRIGGER tr_posts_visitors_insert
            AFTER INSERT ON posts_visitors
            FOR EACH ROW
            BEGIN
                UPDATE posts
                SET views_count = views_count + 1
                WHERE id = NEW.post_id;
            END;
        ");

        // Триггер на удаление из posts_visitors
        $this->execute("
            CREATE TRIGGER tr_posts_visitors_delete
            AFTER DELETE ON posts_visitors
            FOR EACH ROW
            BEGIN
                UPDATE posts
                SET views_count = views_count - 1
                WHERE id = OLD.post_id
                  AND views_count > 0;
            END;
        ");

        // Триггер на вставку в posts_track
        $this->execute("
            CREATE TRIGGER tr_posts_track_insert
            AFTER INSERT ON posts_track
            FOR EACH ROW
            BEGIN
                UPDATE posts
                SET followers_count = followers_count + 1
                WHERE id = NEW.post_id;
            END;
        ");

        // Триггер на удаление из posts_track
        $this->execute("
            CREATE TRIGGER tr_posts_track_delete
            AFTER DELETE ON posts_track
            FOR EACH ROW
            BEGIN
                UPDATE posts
                SET followers_count = followers_count - 1
                WHERE id = OLD.post_id
                  AND followers_count > 0;
            END;
        ");
    }

    public function safeDown()
    {
        // Удаляем триггеры
        $this->execute("DROP TRIGGER IF EXISTS tr_posts_visitors_insert");
        $this->execute("DROP TRIGGER IF EXISTS tr_posts_visitors_delete");
        $this->execute("DROP TRIGGER IF EXISTS tr_posts_track_insert");
        $this->execute("DROP TRIGGER IF EXISTS tr_posts_track_delete");

        // Удаляем индексы
        $this->dropIndex('idx-posts-name', '{{%posts}}');
        $this->dropIndex('idx-posts-created_at', '{{%posts}}');
        $this->dropIndex('idx-posts-created_by', '{{%posts}}');
        $this->dropIndex('idx-posts-views_count', '{{%posts}}');
        $this->dropIndex('idx-posts-followers_count', '{{%posts}}');

        $this->dropIndex('idx-posts_visitors-post_id-visitor_id', '{{%posts_visitors}}');
        $this->dropIndex('idx-posts_visitors-visitor_id', '{{%posts_visitors}}');
        $this->dropIndex('idx-posts_track-post_id-user_id', '{{%posts_track}}');
        $this->dropIndex('idx-posts_track-user_id', '{{%posts_track}}');

        // Удаляем новые поля
        $this->dropColumn('{{%posts}}', 'views_count');
        $this->dropColumn('{{%posts}}', 'followers_count');
    }
}
