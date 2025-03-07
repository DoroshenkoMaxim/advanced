<?php

use frontend\models\PostsTrack;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var frontend\models\PostsTrackSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Posts Tracks';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="posts-track-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Posts Track', ['create'], ['class' => 'btn btn-success']) ?>
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
                'value' => function ($model) {
                    return $model->post ? $model->post->name : 'Unknown';
                }
            ],
            [
                'attribute' => 'user_id',
                'label' => 'User',
                'enableSorting' => false,
                'value' => function ($model) {
                    return $model->user ? $model->user->username : 'Unknown';
                }
            ],
            [
                'attribute' => 'track_at',
                'label' => 'Track',
                'enableSorting' => false,
                'format' => ['datetime', 'php:Y-m-d H:i:s']
            ],
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, PostsTrack $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
