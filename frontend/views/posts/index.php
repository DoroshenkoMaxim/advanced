<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use frontend\models\Posts;
use yii\data\Sort;

/** @var yii\web\View $this */
/** @var frontend\models\PostsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var Sort $sort */

$this->title = 'Posts';
$this->params['breadcrumbs'][] = $this->title;

// Текущий объект сортировки
$sort = $dataProvider->getSort();
$currentOrder = $sort->getAttributeOrders();

// Проверяем, что сортировка ТОЛЬКО по id DESC
$isSortedByIdDesc = ($currentOrder === ['id' => SORT_DESC]);

// Получаем текущие записи (максимум 20 штук), выбранные dataProvider'ом
$models = $dataProvider->getModels();

// Определим $lastId = id последней записи (если есть записи)
$lastId = null;
if (!empty($models)) {
    /** @var Posts $lastModel */
    $lastModel = end($models);
    $lastId = $lastModel->id;
}
?>
<div class="posts-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Post', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <!-- GridView БЕЗ offset/limit-пагинации -->
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        // Подключаем model поиска - у нас фильтр ТОЛЬКО по name
        'filterModel'  => $searchModel,
        'columns' => [
            // Сортируем по id, но фильтра по id нет - это нормально
            'id',
            // Фильтр по name (поиск по name)
            'name',
            // Сортировка по views_count
            'views_count',
            // Сортировка по followers_count
            'followers_count',
            [
                'attribute' => 'createdByUsername',
                'label'     => 'Author',
                'value'     => function (Posts $model) {
                    return $model->createdBy ? $model->createdBy->username : null;
                }
            ],
            [
                'attribute' => 'created_at',
                'format'    => ['date', 'php:Y-m-d H:i:s'],
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
        // Отключаем стандартный вывод "Показано X из Y" и постраничную навигацию
        'summary' => false,
        'pager'   => false,
    ]); ?>

    <?php if ($isSortedByIdDesc && $lastId && count($models) === 20): ?>
        <?php
        $params = array_merge(Yii::$app->request->get(), ['lastSeenId' => $lastId]);
        $url = Url::to(['posts/index'] + $params);
        ?>
        <p>
            <?= Html::a('Next page →', $url, ['class' => 'btn btn-primary']) ?>
        </p>
    <?php else: ?>
        <p><em>For pagination sort ID by DESC</em></p>
    <?php endif; ?>

</div>