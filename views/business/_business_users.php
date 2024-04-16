<?php 

use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\data\ActiveDataProvider;

/** @var string $role */
/** @var \app\models\Business $model */

Pjax::begin([
    'id' => $role."UsersGrid".$model->id, // unique ID for the Pjax widget to target this specific grid
    'timeout' => 10000, // timeout in milliseconds, adjust as needed
    'enablePushState' => false, // do not change URL
    'clientOptions' => ['method' => 'POST'] // use POST method for the requests, adjust as needed
]);

try {
    echo GridView::widget([
        'dataProvider' => new ActiveDataProvider([
            'query' => $model->getUsers($role),
            'pagination' => [
                'pageSize' => 20, // Adjust as needed
            ],
            'sort' => [
                'defaultOrder' => [
                    'first_name' => SORT_ASC, // Adjust according to your User model attributes and needs
                ]
            ],
        ]),
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'first_name',  // Adjust according to your User model attributes
            'last_name',  // Adjust according to your User model attributes
            'email',     // Adjust according to your User model attributes
            // Other columns as necessary
        ],
    ]);
} catch (Throwable $e) {
}

Pjax::end();
