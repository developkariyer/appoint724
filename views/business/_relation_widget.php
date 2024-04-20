<?php
use app\components\MyUrl;
use app\widgets\Card;
use yii\bootstrap5\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var \yii\data\ActiveDataProvider $dataProvider */
/** @var \app\models\Business $model */
/** @var string $relationTitle */
/** @var array $relationColumns */
/** @var string $relation */

Pjax::begin([
    'id' => "ResourceGrid".$model->id,
    'timeout' => 10000,
    'enablePushState' => false,
    'clientOptions' => ['method' => 'GET']
]);

$slug = $model->slug;
$relation = true ? $relation : '';

try {
    $content = GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ...$relationColumns,
            [
                'class' => 'yii\grid\Column',
                'content' => function ($model, $key, $index, $column) use ($relation, $slug) {/** @var string $relation */
                    $editUrl = MyUrl::to(["business/$relation/$slug/$model->id"]);
                    $deleteUrl = MyUrl::to(["business/$relation/$slug/$model->id", 'delete' => $model->id]);
                    $editButton = Html::a(Yii::t('app', 'Edit'), $editUrl, [
                        'class' => 'btn btn-light border border-primary btn-sm mb-1',
                        'data' => [
                            'pjax' => 0,
                        ]
                    ]);
                    $deleteButton = Html::a(Yii::t('app', 'Delete'), $deleteUrl, [
                        'class' => 'btn btn-danger btn-sm mb-1 ml-2',
                        'data' => [
                            'confirm' => Yii::t('app', 'Are you sure you want to delete this?'),
                            'method' => 'post',
                            'pjax' => 0,
                        ],
                    ]);
                    return "$editButton $deleteButton";
                },
            ],
        ],
        'pager' => [
            'class' => yii\bootstrap5\LinkPager::class, 
        ],
    
    ]);
} catch (Throwable $e) {
}

echo Card::widget([
    'title' => $model->name.' '.$relationTitle,
    'content' => $content,
]);

Pjax::end();