<?php
use app\components\MyUrl;
use app\widgets\Card;
use yii\bootstrap5\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var string $role */
/** @var \yii\data\ActiveDataProvider $dataProvider */
/** @var \app\models\Business $model */

Pjax::begin([
    'id' => "UsersGrid".$model->id, // unique ID for the Pjax widget to target this specific grid
    'timeout' => 10000, // timeout in milliseconds, adjust as needed
    'enablePushState' => false, // do not change URL
    'clientOptions' => ['method' => 'POST'] // use POST method for the requests, adjust as needed
]);

$slug = $model->slug;

try {
    $content = GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'fullname',
            'email',
            'gsm',
            [
                'class' => 'yii\grid\Column',
                'header' => Yii::t('app', 'Remove'),
                'content' => function ($model, $key, $index, $column) use ($role, $slug) {
                    $url = MyUrl::to(["business/user/$role/$slug"]);
                    return Html::beginForm($url, 'post')
                        . Html::hiddenInput('id', $model->id)
                        . Html::hiddenInput('action', '1')
                        . Html::submitButton('<i class="bi bi-person-dash"></i>', [
                            'class' => 'btn btn-primary btn-sm',
                            'data' => [
                                'confirm' => Yii::t('app', 'Are you sure you want to remove this user?'),
                                'method' => 'post',
                            ],
                        ])
                        . Html::endForm();
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
    'title' => $model->name.' '.Yii::t('app', Yii::$app->params['roles'][$role]),
    'content' => $content,
]);

Pjax::end();

