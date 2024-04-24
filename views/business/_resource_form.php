<?php

use app\widgets\Card;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var yii\web\View $this */
/* @var app\models\Resource $relationModel */

$resourceTypes = json_decode($model->resource_type_list, true) ?? [];
$resourceTypes = array_combine($resourceTypes, $resourceTypes);

$form = ActiveForm::begin();
$content = $form->field($relationModel, 'name')->textInput(['maxlength' => true]);
$content .= $form->field($relationModel, 'resource_type')->dropDownList($resourceTypes);
$content .= Html::submitButton(
    $relationModel->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'),
    ['class' => 'btn btn-primary']
);
echo Card::widget([
    'title' => '', 
    'content' => $content,
]);
ActiveForm::end();