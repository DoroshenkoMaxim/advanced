<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * PostsSearch - модель поиска для Posts
 * (поиск только по name, сортировки по
 *  id, name, views_count, followers_count, created_at, createdByUsername).
 * 
 * Использует keyset pagination: отключаем offset/limit,
 * вместо этого берём lastSeenId из GET, делаем WHERE p.id < lastSeenId.
 */
class PostsSearch extends Posts
{
    /**
     * Свойство для сортировки по user.username
     * (не фильтруем по нему, но сортируем).
     */
    public $createdByUsername;

    public function rules()
    {
        return [
            // Поиск ТОЛЬКО по name
            [['name'], 'safe'],
        ];
    }

    /**
     * Выполняет поиск с keyset pagination (не используем обычный offset/limit).
     */
    public function searchKeyset($params)
    {
        // Подключаем alias, делаем JOIN, чтобы сортировать по user.username
        $query = Posts::find()->alias('p')
            ->joinWith(['createdBy user']);

        // Создаём DataProvider
        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            // Отключаем стандартную пагинацию
            'pagination' => false,
            // Определяем поля сортировки
            'sort'       => [
                'defaultOrder' => ['id' => SORT_DESC],
                'attributes'   => [
                    'id' => [
                        'asc'  => ['p.id' => SORT_ASC],
                        'desc' => ['p.id' => SORT_DESC],
                    ],
                    'name' => [
                        'asc'  => ['p.name' => SORT_ASC],
                        'desc' => ['p.name' => SORT_DESC],
                    ],
                    'views_count' => [
                        'asc'  => ['p.views_count' => SORT_ASC],
                        'desc' => ['p.views_count' => SORT_DESC],
                    ],
                    'followers_count' => [
                        'asc'  => ['p.followers_count' => SORT_ASC],
                        'desc' => ['p.followers_count' => SORT_DESC],
                    ],
                    'created_at' => [
                        'asc'  => ['p.created_at' => SORT_ASC],
                        'desc' => ['p.created_at' => SORT_DESC],
                    ],
                    // сортируем по user.username
                    'createdByUsername' => [
                        'asc'  => ['user.username' => SORT_ASC],
                        'desc' => ['user.username' => SORT_DESC],
                    ],
                ],
            ],
        ]);

        // Загружаем GET/POST-параметры
        $this->load($params);

        // Если валидация не пройдена, покажем пустую выборку
        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        // Фильтр только по name
        if ($this->name) {
            $query->andWhere(['like', 'p.name', $this->name]);
        }

        // keyset pagination: берём lastSeenId из GET
        $lastSeenId = \Yii::$app->request->get('lastSeenId');
        if ($lastSeenId) {
            // Показываем записи, у которых p.id < lastSeenId
            $query->andWhere(['<', 'p.id', $lastSeenId]);
        }

        // Лимит 20 строк (pageSize)
        $query->limit(20);

        return $dataProvider;
    }
}