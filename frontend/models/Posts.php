<?php

namespace frontend\models;

use common\models\User;
use yii\db\ActiveRecord;

/**
 * Модель для таблицы posts.
 *
 * @property int $id
 * @property string $name
 * @property string $text
 * @property string|null $fields
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 * @property int $views_count
 * @property int $followers_count
 *
 * @property User $createdBy
 */
class Posts extends ActiveRecord
{
    public static function tableName()
    {
        return 'posts';
    }

    public function rules()
    {
        return [
            [['name', 'text', 'created_by', 'created_at', 'updated_at'], 'required'],
            [['text', 'fields'], 'string'],
            [['created_by', 'created_at', 'updated_at', 'views_count', 'followers_count'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    public function getCreatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }
}