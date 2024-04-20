<?php

use app\widgets\Card;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var yii\web\View $this */
/* @var app\models\Rule $relationModel */

$form = ActiveForm::begin();
$content = $form->field($relationModel, 'name')->textInput(['maxlength' => true]);
$content .= $form->field($relationModel, 'ruleset')->textInput(['maxlength' => true]); // TODO predefined rulesets and custom ruleset builder will be implemented
$content .= Html::submitButton(
    $relationModel->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'),
    ['class' => 'btn btn-primary']
);
echo Card::widget([
    'title' => '', 
    'content' => $content,
]);
ActiveForm::end();