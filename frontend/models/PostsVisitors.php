<?php

namespace frontend\models;

use common\models\User;

use Yii;

/**
 * This is the model class for table "posts_visitors".
 *
 * @property int $id
 * @property int $post_id
 * @property int $visitor_id
 * @property int $view_at
 *
 * @property Posts $post
 * @property User $visitor
 */
class PostsVisitors extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'posts_visitors';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['post_id', 'visitor_id', 'view_at'], 'required'],
            [['post_id', 'visitor_id', 'view_at'], 'integer'],
            [['post_id'], 'exist', 'skipOnError' => true, 'targetClass' => Posts::class, 'targetAttribute' => ['post_id' => 'id']],
            [['visitor_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['visitor_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'post_id' => 'Post ID',
            'visitor_id' => 'Visitor ID',
            'view_at' => 'View At',
        ];
    }

    /**
     * Gets query for [[Post]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(Posts::class, ['id' => 'post_id']);
    }

    /**
     * Gets query for [[Visitor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVisitor()
    {
        return $this->hasOne(User::class, ['id' => 'visitor_id']);
    }

}
