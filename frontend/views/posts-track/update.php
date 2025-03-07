<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var frontend\models\PostsTrack $model */

$this->title = 'Update Posts Track: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Posts Tracks', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="posts-track-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
