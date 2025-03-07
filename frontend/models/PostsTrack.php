<?php

namespace frontend\models;

use common\models\User;
use yii\db\ActiveRecord;

/**
 * Модель для таблицы posts_track (подписки).
 *
 * @property int $id
 * @property int $post_id
 * @property int $user_id
 * @property int $track_at
 *
 * @property Posts $post
 * @property User $user
 */
class PostsTrack extends ActiveRecord
{
    public static function tableName()
    {
        return 'posts_track';
    }

    public function rules()
    {
        return [
            [['post_id', 'user_id', 'track_at'], 'required'],
            [['post_id', 'user_id', 'track_at'], 'integer'],
        ];
    }

    public function getPost()
    {
        return $this->hasOne(Posts::class, ['id' => 'post_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}