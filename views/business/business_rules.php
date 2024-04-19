<?php 
use app\components\MyUrl;
use yii\bootstrap5\Html;

/** @var \app\models\Business $model */
/** @var \yii\data\ActiveDataProvider $dataProvider */

$this->title = Html::encode($model->name).' '.Yii::t('app', 'Rules');
?>

<div class="row justify-content-md-center ">
    <div class="col-md-8 col-lg-6 ">
        <?= $this->render('_rule_widget', ['model' => $model, 'dataProvider' => $dataProvider]) ?>
<?php /*        <?= $this->render('_resource_search_widget', ['model' => $model]) ?> */ ?>
        <?= Html::a(Yii::t('app', 'Add Rule'), MyUrl::to(["rule/create/$model->slug"]), ['class' => 'btn btn-primary btn-outline-light']) ?>
    </div>
</div>
