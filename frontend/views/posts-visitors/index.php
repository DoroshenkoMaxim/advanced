<?php

use frontend\models\PostsVisitors;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var frontend\models\PostsVisitorsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Posts Visitors';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="posts-visitors-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Posts Visitors', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'post_id',
                'label' => 'Post',
                'enableSorting' => false,
                'value' => function($model) {
                    return $model->post ? $model->post->name : 'Unknown';
                }
            ],
            [
                'attribute' => 'visitor_id',
                'label' => 'User',
                'enableSorting' => false,
                'value' => function($model) {
                    return $model->visitor ? $model->visitor->username : 'Unknown';
                }
            ],
            [
                'attribute' => 'view_at',
                'label' => 'Visit',
                'enableSorting' => false,
                'format' => ['datetime', 'php:Y-m-d H:i:s']
            ],
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, PostsVisitors $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
