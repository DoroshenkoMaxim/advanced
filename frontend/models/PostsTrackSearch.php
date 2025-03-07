<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * PostsTrackSearch - "поисковая" модель без реального поиска/сортировки,
 * использует keyset pagination (нет offset/limit).
 */
class PostsTrackSearch extends PostsTrack
{
    public function rules()
    {
        // Можно указать поля как integer, но реального фильтра у нас нет
        return [
            [['id', 'post_id', 'user_id', 'track_at'], 'integer'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * Метод с keyset-пагинацией:
     *  - Отключаем обычную пагинацию
     *  - Принимаем lastSeenId из GET
     *  - WHERE id < lastSeenId, ORDER BY id DESC, LIMIT 20
     */
    public function searchKeyset($params)
    {
        $query = PostsTrack::find();

        // Отключаем стандартную пагинацию
        $dataProvider = new ActiveDataProvider([
            'query'      => $query,
            'pagination' => false,
            // Если не хотим никакой пользовательской сортировки,
            // можно отключить sort вообще:
            'sort'       => false,
        ]);

        // Загружаем параметры (хотя у нас реального фильтра нет)
        $this->load($params);
        if (!$this->validate()) {
            $query->where('0=1'); // если вдруг валидация не пройдена
            return $dataProvider;
        }

        // keyset pagination
        $lastSeenId = \Yii::$app->request->get('lastSeenId');
        if ($lastSeenId) {
            $query->andWhere(['<', 'id', $lastSeenId]);
        }

        // Фиксированный порядок: id DESC
        $query->orderBy(['id' => SORT_DESC]);
        // Показываем максимум 20 записей
        $query->limit(20);

        return $dataProvider;
    }
}