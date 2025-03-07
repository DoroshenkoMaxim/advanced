<?php

use frontend\models\Posts;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var frontend\models\PostsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Posts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="posts-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Posts', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); 
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            'text:ntext',
            'fields:ntext',
            [
                'attribute' => 'viewsCount',
                'label' => 'Views',
                'value' => function ($model) {
                    return count($model->postsVisitors);
                }
            ],
            [
                'attribute' => 'followersCount',
                'label' => 'Followers',
                'value' => function ($model) {
                    return count($model->postsTracks);
                }
            ],
            [
                'attribute' => 'createdByUsername',
                'label' => 'User',
                'value' => function ($model) {
                    return $model->createdBy ? $model->createdBy->username : null;
                }
            ],            
            [
                'attribute' => 'created_at',
                'label' => 'Created',
                'format' => ['datetime', 'php:Y-m-d H:i:s']
            ],
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Posts $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>


</div>