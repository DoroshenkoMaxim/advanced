<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Posts;

class PostsSearch extends Posts
{
    public $viewsCount;
    public $followersCount;
    public $createdByUsername;

    public function rules()
    {
        return [
            [[], 'integer'],
            [['name'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params, $formName = null)
    {
        $query = Posts::find()->joinWith(['createdBy user', 'postsTracks postsTracksAlias', 'postsVisitors postsVisitorsAlias']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'id',
                    'name',
                    'text',
                    'fields',
                    'created_at',
                    'updated_at',
                    'viewsCount' => [
                        'asc' => ['COUNT(postsVisitorsAlias.id)' => SORT_ASC],
                        'desc' => ['COUNT(postsVisitorsAlias.id)' => SORT_DESC],
                    ],
                    'followersCount' => [
                        'asc' => ['COUNT(postsTracksAlias.id)' => SORT_ASC],
                        'desc' => ['COUNT(postsTracksAlias.id)' => SORT_DESC],
                    ],
                    'createdByUsername' => [
                        'asc' => ['user.username' => SORT_ASC],
                        'desc' => ['user.username' => SORT_DESC],
                    ],
                ]
            ]
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'fields', $this->fields])
            ->andFilterWhere(['like', 'user.username', $this->createdByUsername]);

        $query->groupBy(['posts.id']);
        
        $dataProvider->setSort([
            'attributes' => [
                'id',
                'name',
                'text',
                'fields',
                'created_at',
                'updated_at',
                'viewsCount' => [
                    'asc' => ['COUNT(postsVisitorsAlias.id)' => SORT_ASC],
                    'desc' => ['COUNT(postsVisitorsAlias.id)' => SORT_DESC],
                ],
                'followersCount' => [
                    'asc' => ['COUNT(postsTracksAlias.id)' => SORT_ASC],
                    'desc' => ['COUNT(postsTracksAlias.id)' => SORT_DESC],
                ],
                'createdByUsername' => [
                    'asc' => ['user.username' => SORT_ASC],
                    'desc' => ['user.username' => SORT_DESC],
                ],
                'created_by' => [
                    'asc' => ['posts.created_by' => SORT_ASC],
                    'desc' => ['posts.created_by' => SORT_DESC],
                ],
            ]
        ]);

        return $dataProvider;
    }
}