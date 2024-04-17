<?php 

use app\widgets\Card;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var string $role */
/** @var \app\models\Business $model */

echo '<div class="row justify-content-md-center "><div class="col-md-8 col-lg-6 ">';

Pjax::begin([
    'id' => "UsersGrid".$model->id, // unique ID for the Pjax widget to target this specific grid
    'timeout' => 10000, // timeout in milliseconds, adjust as needed
    'enablePushState' => false, // do not change URL
    'clientOptions' => ['method' => 'POST'] // use POST method for the requests, adjust as needed
]);

try {
    $content = GridView::widget([
        'dataProvider' => $dataProvider,
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

echo Card::widget([
    'title' => $model->name.' '.Yii::t('app', Yii::$app->params['userTypes'][$userType]),
    'content' => $content,
]);

echo $this->render('_business_user_form');

Pjax::end();

echo '</div></div>';
