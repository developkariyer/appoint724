<?php
use app\components\MyUrl;
use app\widgets\Card;
use yii\bootstrap5\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var \yii\data\ActiveDataProvider $dataProvider */
/** @var \app\models\Business $model */

Pjax::begin([
    'id' => "ResourceGrid".$model->id, // unique ID for the Pjax widget to target this specific grid
    'timeout' => 10000, // timeout in milliseconds, adjust as needed
    'enablePushState' => false, // do not change URL
    'clientOptions' => ['method' => 'POST'] // use POST method for the requests, adjust as needed
]);

$slug = $model->slug;

try {
    $content = GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'name',
            'resource_type',
            [
                'class' => 'yii\grid\Column',
                'content' => function ($model, $key, $index, $column) use ($slug) {
                    $url2 = MyUrl::to(["user/update/$model->id"]);
                    return 
                        Html::a(Yii::t('app', 'Edit'), $url2, ['class' => 'btn btn-light border border-primary btn-sm mb-1']);
                },
            ],
        ],
        'pager' => [
            'class' => yii\bootstrap5\LinkPager::class,  // Use Bootstrap 5 LinkPager
        ],
    
    ]);
} catch (Throwable $e) {
}

echo Card::widget([
    'title' => $model->name.' '.Yii::t('app', 'Resources'),
    'content' => $content,
]);

Pjax::end();

