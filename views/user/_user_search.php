<?php

use yii\bootstrap5\Html;
use yii\grid\GridView;
use app\components\MyUrl;

/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $role string */
/* @var $slug string */

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'first_name',
        'last_name',
        'email',
        'gsm',
        [
            'class' => 'yii\grid\Column', // Default grid column
            'content' => function ($model, $key, $index, $column) use ($role, $slug) {
                $url = MyUrl::to(["business/user/$role/$slug"]);
                return Html::beginForm($url, 'post')
                    . Html::hiddenInput('id', $model->id)
                    . Html::hiddenInput('action', '2')
                    . Html::submitButton('<i class="bi bi-person-fill-add"></i>', [
                        'class' => 'btn btn-primary btn-sm',
                        'data' => [
                            'confirm' => Yii::t('app', 'Are you sure you want to add this user?'),
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
    'options' => [
        'tag' => 'div',
        'class' => 'search-list',
        'id' => 'list-wrapper',
    ],
    'layout' => "{items}\n{pager}", // Adjust the layout to remove the sort links
]);
