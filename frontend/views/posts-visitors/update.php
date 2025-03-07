<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var frontend\models\PostsVisitors $model */

$this->title = 'Update Posts Visitors: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Posts Visitors', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="posts-visitors-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
