<?php

use yii\db\Migration;
use Faker\Factory as Faker;

/**
 * Class m250307_185912_generate_posts
 */
class m250307_185912_generate_posts extends Migration
{
    public function safeUp()
    {
        $faker = Faker::create();
        $userIds = $this->getDb()->createCommand('SELECT id FROM {{%user}} LIMIT 10000')->queryColumn();
        $batchSize = 10000;
        $postData = [];

        $this->execute('SET FOREIGN_KEY_CHECKS=0;');
        $this->execute('ALTER TABLE {{%posts}} DISABLE KEYS;');

        for ($i = 1; $i <= 1000000; $i++) {
            $postData[] = [
                'name' => $faker->sentence(6),
                'text' => $faker->paragraph(10),
                'fields' => $faker->text(50),
                'created_by' => $faker->randomElement($userIds),
                'created_at' => time(),
                'updated_at' => time(),
            ];

            if ($i % $batchSize === 0) {
                $this->batchInsert('{{%posts}}', ['name', 'text', 'fields', 'created_by', 'created_at', 'updated_at'], $postData);
                $postData = [];
            }
        }

        if (!empty($postData)) {
            $this->batchInsert('{{%posts}}', ['name', 'text', 'fields', 'created_by', 'created_at', 'updated_at'], $postData);
        }

        $this->execute('ALTER TABLE {{%posts}} ENABLE KEYS;');
        $this->execute('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function safeDown()
    {
        $this->delete('{{%posts}}');
    }
}
