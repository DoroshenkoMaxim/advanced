<?php

use frontend\models\PostsVisitors;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var frontend\models\PostsVisitorsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Posts Visitors';
$this->params['breadcrumbs'][] = $this->title;

// Получаем максимум 20 записей (id DESC)
$models = $dataProvider->getModels();

// Находим id последней записи
$lastId = null;
if (!empty($models)) {
    /** @var PostsVisitors $lastModel */
    $lastModel = end($models);
    $lastId = $lastModel->id;
}
?>
<div class="posts-visitors-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Posts Visitors', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <!-- GridView без пагинации и фильтров -->
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // нет searchModel, т.к. фильтр не нужен
        'columns' => [
            [
                'attribute' => 'id',
                'enableSorting' => false,
            ],
            [
                'attribute' => 'post_id',
                'label'     => 'Post',
                'value'     => function($model) {
                    return $model->post ? $model->post->name : 'Unknown';
                },
                'enableSorting' => false,
            ],
            [
                'attribute' => 'visitor_id',
                'label'     => 'Visitor',
                'value'     => function($model) {
                    return $model->visitor ? $model->visitor->username : 'Unknown';
                },
                'enableSorting' => false,
            ],
            [
                'attribute' => 'view_at',
                'label'     => 'Visit Time',
                'format'    => ['datetime', 'php:Y-m-d H:i:s'],
                'enableSorting' => false,
            ],
            // ActionColumn, если нужно
            [
                'class'     => 'yii\grid\ActionColumn',
                'urlCreator' => function ($action, PostsVisitors $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
        'summary' => false,
        'pager'   => false,
    ]); ?>

    <?php if ($lastId && count($models) === 20): ?>
        <?php
            // Формируем ссылку "Next page", дополнив GET-параметры
            $params = array_merge(
                Yii::$app->request->get(),
                ['lastSeenId' => $lastId]
            );
            $url = Url::to(['posts-visitors/index'] + $params);
        ?>
        <p>
            <?= Html::a('Next page →', $url, ['class' => 'btn btn-primary']) ?>
        </p>
    <?php else: ?>
        <p><em>No more results.</em></p>
    <?php endif; ?>

</div>