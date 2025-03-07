<?php

use frontend\models\PostsTrack;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\grid\ActionColumn;

/** @var yii\web\View $this */
/** @var frontend\models\PostsTrackSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Posts Track';
$this->params['breadcrumbs'][] = $this->title;

// Получаем все записи (максимум 20)
$models = $dataProvider->getModels();
$lastId = null;
if (!empty($models)) {
    /** @var PostsTrack $lastModel */
    $lastModel = end($models);
    $lastId = $lastModel->id;
}
?>
<div class="posts-track-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Posts Track', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // нет 'filterModel' - поиск не нужен
        'columns' => [
            [
                'attribute' => 'id',
                'enableSorting' => false,
            ],
            // Пост
            [
                'attribute' => 'post_id',
                'label' => 'Post',
                'enableSorting' => false,
                'value' => function ($model) {
                    return $model->post ? $model->post->name : 'Unknown';
                }
            ],
            // Юзер
            [
                'attribute' => 'user_id',
                'label'     => 'Follower',
                'enableSorting' => false,
                'value' => function ($model) {
                    return $model->user ? $model->user->username : 'Unknown';
                }
            ],
            // track_at
            [
                'attribute' => 'track_at',
                'label' => 'Follow Time',
                'format' => ['datetime', 'php:Y-m-d H:i:s'],
                'enableSorting' => false,
            ],

            // Экшен-колонка, если хотите редактировать/удалять
            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, PostsTrack $model, $key, $index, $column) {
                    return Url::to([$action, 'id' => $model->id]);
                }
            ],
        ],
        // Отключаем сводку и стандартный пагинатор
        'summary' => false,
        'pager'   => false,
    ]); ?>

    <?php if ($lastId && count($models) === 20): ?>
        <?php
            // Формируем ссылку Next page?lastSeenId=...
            $params = array_merge(
                Yii::$app->request->get(),
                ['lastSeenId' => $lastId]
            );
            $url = Url::to(['posts-track/index'] + $params);
        ?>
        <p>
            <?= Html::a('Next page →', $url, ['class' => 'btn btn-primary']) ?>
        </p>
    <?php else: ?>
        <p><em>No more results.</em></p>
    <?php endif; ?>

</div>