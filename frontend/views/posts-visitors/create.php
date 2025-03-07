<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var frontend\models\PostsVisitors $model */

$this->title = 'Create Posts Visitors';
$this->params['breadcrumbs'][] = ['label' => 'Posts Visitors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="posts-visitors-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
