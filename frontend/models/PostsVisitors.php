<?php

namespace frontend\models;

use common\models\User;
use yii\db\ActiveRecord;

/**
 * Модель для таблицы posts_visitors (просмотры).
 *
 * @property int $id
 * @property int $post_id
 * @property int $visitor_id
 * @property int $view_at
 *
 * @property Posts $post
 * @property User $visitor
 */
class PostsVisitors extends ActiveRecord
{
    public static function tableName()
    {
        return 'posts_visitors';
    }

    public function rules()
    {
        return [
            [['post_id', 'visitor_id', 'view_at'], 'required'],
            [['post_id', 'visitor_id', 'view_at'], 'integer'],
        ];
    }

    public function getPost()
    {
        return $this->hasOne(Posts::class, ['id' => 'post_id']);
    }

    public function getVisitor()
    {
        return $this->hasOne(User::class, ['id' => 'visitor_id']);
    }
}