<?php

namespace frontend\models;
use common\models\User;

use Yii;

/**
 * This is the model class for table "posts".
 *
 * @property int $id
 * @property string $name
 * @property string $text
 * @property string|null $fields
 * @property int $created_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property User $createdBy
 * @property PostsTrack[] $postsTracks
 * @property PostsVisitors[] $postsVisitors
 */
class Posts extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'posts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fields'], 'default', 'value' => null],
            [['name', 'text', 'created_by', 'created_at', 'updated_at'], 'required'],
            [['text', 'fields'], 'string'],
            [['created_by', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'text' => 'Text',
            'fields' => 'Fields',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * Gets query for [[PostsTracks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPostsTracks()
    {
        return $this->hasMany(PostsTrack::class, ['post_id' => 'id']);
    }

    /**
     * Gets query for [[PostsVisitors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPostsVisitors()
    {
        return $this->hasMany(PostsVisitors::class, ['post_id' => 'id']);
    }

}
