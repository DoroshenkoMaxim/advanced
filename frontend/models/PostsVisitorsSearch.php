<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * PostsVisitorsSearch - модель "поиска", но без реального фильтра и сортировки.
 * Вместо этого используем keyset pagination (нет offset/limit).
 */
class PostsVisitorsSearch extends PostsVisitors
{
    /**
     * Переопределяем rules, но фактически ничего не фильтруем.
     */
    public function rules()
    {
        return [
            // Здесь можно ничего не указывать, либо оставить "integer" для полей
            [['id', 'post_id', 'visitor_id', 'view_at'], 'integer'],
        ];
    }

    /**
     * Если всё же требуется scenarios, оставляем по умолчанию
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * Метод, возвращающий DataProvider с keyset pagination.
     */
    public function searchKeyset($params)
    {
        // Базовый запрос
        $query = PostsVisitors::find();

        // Выключаем обычную пагинацию
        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'pagination' => false, // <--- нет offset/limit
        ]);

        // Загружаем (если есть) - но по факту фильтровать мы не будем
        $this->load($params);
        if (!$this->validate()) {
            // Если вдруг есть ошибки валидации
            $query->where('0=1');
            return $dataProvider;
        }

        // keyset pagination: возьмём lastSeenId из GET
        $lastSeenId = \Yii::$app->request->get('lastSeenId');
        if ($lastSeenId) {
            // Показываем записи, у которых id < lastSeenId
            $query->andWhere(['<', 'id', $lastSeenId]);
        }

        // Сортируем по id DESC и берем limit(20)
        $query->orderBy(['id' => \SORT_DESC]);
        $query->limit(20);

        return $dataProvider;
    }
}