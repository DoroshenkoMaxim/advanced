<?php

use yii\db\Migration;
use Faker\Factory as Faker;

/**
 * Class m250307_190055_generate_posts_visitors_track
 */
class m250307_190055_generate_posts_visitors_track extends Migration
{
    public function safeUp()
    {
        $faker = Faker::create();
        $postIds = $this->getDb()->createCommand('SELECT id FROM {{%posts}}')->queryColumn();
        $userIds = $this->getDb()->createCommand('SELECT id FROM {{%user}} LIMIT 10000')->queryColumn();
        $batchSize = 50000;
        $postViewsData = [];
        $postSubscribersData = [];

        $this->execute('SET FOREIGN_KEY_CHECKS=0;');
        $this->execute('ALTER TABLE {{%posts_visitors}} DISABLE KEYS;');
        $this->execute('ALTER TABLE {{%posts_track}} DISABLE KEYS;');

        foreach ($postIds as $postId) {
            for ($view = 0; $view < 100; $view++) {
                $postViewsData[] = [
                    'post_id' => $postId,
                    'visitor_id' => $faker->randomElement($userIds),
                    'view_at' => time(),
                ];
            }

            foreach ($faker->randomElements($userIds, 10) as $subscriber) {
                $postSubscribersData[] = [
                    'post_id' => $postId,
                    'user_id' => $subscriber,
                    'track_at' => time(),
                ];
            }

            if (count($postViewsData) >= $batchSize) {
                $this->batchInsert('{{%posts_visitors}}', ['post_id', 'visitor_id', 'view_at'], $postViewsData);
                $postViewsData = [];
            }

            if (count($postSubscribersData) >= $batchSize) {
                $this->batchInsert('{{%posts_track}}', ['post_id', 'user_id', 'track_at'], $postSubscribersData);
                $postSubscribersData = [];
            }
        }

        $this->execute('ALTER TABLE {{%posts_visitors}} ENABLE KEYS;');
        $this->execute('ALTER TABLE {{%posts_track}} ENABLE KEYS;');
        $this->execute('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function safeDown()
    {
        $this->delete('{{%posts_visitors}}');
        $this->delete('{{%posts_track}}');
    }
}
