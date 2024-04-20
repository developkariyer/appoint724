<?php

use app\components\MyUrl;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var yii\web\View $this */
/* @var app\models\Business $model */
/* @var yii\widgets\ActiveForm $form */
?>

<div class="business-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php echo $form->field($model, 'name')->textInput(['maxlength' => true]); ?>

    <?php $timezones = DateTimeZone::listIdentifiers();
    $timezoneItems = ArrayHelper::map($timezones, function ($tz) { return $tz; }, function ($tz) { return $tz; });
    echo $form->field($model, 'timezone')->dropDownList($timezoneItems, ['prompt' => 'Select Timezone']);
    ?>
    
    <div class="form-group">
        <?php echo Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']); ?>
        <?php echo Html::a(Yii::t('app', 'Delete'), MyUrl::to(["business/delete/$model->slug", 'id' => $model->id]), [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this?'),
                'method' => 'post',
            ],
        ]); ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
