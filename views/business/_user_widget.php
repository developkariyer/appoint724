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
            'myGsm',
            [
                'class' => 'yii\grid\Column',
                'content' => function ($model, $key, $index, $column) use ($role, $slug) {
                    $url2 = MyUrl::to(["user/update/$model->id"]);
                    return 
                        Html::a('Edit User', $url2, ['class' => 'btn btn-light border border-primary btn-sm mb-1']);
                },
            ],
            [
                'class' => 'yii\grid\Column',
                'header' => Yii::t('app', 'Role'),
                'content' => function ($model, $key, $index, $column) use ($role, $slug) {
                    $url1 = MyUrl::to(["business/user/$role/$slug"]);
                    return 
                        Html::beginForm($url1, 'post', ['onchange' => 'this.submit();']).
                        Html::hiddenInput('id', $model->id).
                        Html::dropDownList('role', $role, array_merge(['delete' => Yii::t('app', 'No Role')], Yii::$app->params['roles']), ['class' => 'form-control form-select form-select-sm']).
                        Html::endForm();
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

