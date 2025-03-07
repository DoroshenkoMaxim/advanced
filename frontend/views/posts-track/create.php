<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var frontend\models\PostsTrack $model */

$this->title = 'Create Posts Track';
$this->params['breadcrumbs'][] = ['label' => 'Posts Tracks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="posts-track-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
