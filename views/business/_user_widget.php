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
    'id' => "UsersGrid".$model->id,
    'timeout' => 10000,
    'enablePushState' => false,
    'clientOptions' => ['method' => 'GET']
]);

$slug = $model->slug;

$columns = ($role === 'expert') ? [
    [
        'attribute' => 'expert_type',
        //'class' => 'yii\grid\Column',
        'header' => Yii::t('app', 'Expertise'),
        'content' => function ($fmodel, $key, $index, $column) use ($model, $slug) {
            $expertTypeList = json_decode($model->expert_type_list, true) ?? [];
            $expertTypeList = array_combine($expertTypeList, $expertTypeList);

            return 
                Html::beginForm(MyUrl::to(["business/user/$slug/expert"]), 'post', ['onchange' => 'this.submit();']).
                Html::hiddenInput('id', $fmodel->id).
                Html::dropDownList('expert_type', $fmodel->expert_type, $expertTypeList, ['class' => 'form-control form-select form-select-sm']).
                Html::endForm();
        },
    ],
] : [];

$content = GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'fullname',
        'email',
        //'myGsm',
        [
            'attribute' => 'role',
            //'class' => 'yii\grid\Column',
            'header' => Yii::t('app', 'Edit'),
            'content' => function ($model, $key, $index, $column) use ($role, $slug) {
                $url2 = MyUrl::to(["user/update/$model->id"]);
                return 
                    Html::a(Yii::t('app', 'Edit'), $url2, ['class' => 'btn btn-light border border-primary btn-sm mb-1']);
            },
        ],
        [
            'class' => 'yii\grid\Column',
            'header' => Yii::t('app', 'Role'),
            'content' => function ($model, $key, $index, $column) use ($role, $slug) {
                return 
                    Html::beginForm(MyUrl::to(["business/user/$slug/$role"]), 'post', ['onchange' => 'this.submit();']).
                    Html::hiddenInput('id', $model->id).
                    Html::dropDownList('role', $role, array_merge(['delete' => Yii::t('app', 'No Role')], Yii::$app->params['roles']), ['class' => 'form-control form-select form-select-sm']).
                    Html::endForm();
            },
        ],
        ...$columns
    ],
    'pager' => [
        'class' => yii\bootstrap5\LinkPager::class,  // Use Bootstrap 5 LinkPager
    ],    
]);

echo Card::widget([
    'title' => $model->name.' '.Yii::t('app', Yii::$app->params['roles'][$role]),
    'content' => $content,
]);



Pjax::end();

