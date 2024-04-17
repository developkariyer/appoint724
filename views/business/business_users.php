<?php 
use yii\bootstrap5\Html;

/** @var \app\models\Business $model */
/** @var \yii\data\ActiveDataProvider $dataProvider */
/** @var string $role */

$this->title = Html::encode($model->name).' '.Yii::t('app', Yii::$app->params['roles'][$role]);
?>

<div class="row justify-content-md-center ">
    <div class="col-md-8 col-lg-6 ">
        <?= $this->render('_user_widget', ['model' => $model, 'role' => $role, 'dataProvider' => $dataProvider]) ?>
        <?= $this->render('_user_search_widget', ['model' => $model, 'role' => $role]) ?>
    </div>
</div>
