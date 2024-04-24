<?php

use app\widgets\Card;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var yii\web\View $this */
/* @var app\models\Service|app\models\Rule|app\models\Resource $relationModel */
/* @var app\models\Business $model */

$resourceTypes = json_decode($model->expert_type_list, true) ?? [];
$resourceTypes = array_combine($resourceTypes, $resourceTypes);

$expertTypeList = json_decode($model->expert_type_list, true) ?? [];
$expertTypeList = array_combine($expertTypeList, $expertTypeList);

$form = ActiveForm::begin();
$content = $form->field($relationModel, 'name')->textInput(['maxlength' => true]);
$content .= $form->field($relationModel, 'resource_type')->dropDownList($resourceTypes);
$content .= $form->field($relationModel, 'expert_type')->dropDownList($expertTypeList);


$content .= '<div class="row">';
$content .= '<div class="col-md-9">';
$content .= $form->field($relationModel, 'duration')->input('range', [
    'id' => 'duration-slider',
    'min' => 5,
    'max' => 300,
    'step' => 1,
    'class' => 'form-range',
    'oninput' => 'updateDurationValueDisplay(this.value);'
]);
$content .= '</div><div class="col-md-3 d-flex align-items-center justify-content-center h-100">';

$content .= $form->field($relationModel, 'duration', [
    'template' => "{input}\n{error}",  // Customizing to show only input and error, no labels
])->textInput([
    'id' => 'duration-input',
    'class' => 'form-control',
    'oninput' => 'updateDurationSlider(this.value);'
]);

$content .= '</div></div>';  // Close the row and column divs

$content .= Html::submitButton(
    $relationModel->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'),
    ['class' => 'btn btn-primary']
);
echo Card::widget([
    'title' => '', 
    'content' => $content,
]);
ActiveForm::end();

$this->registerJs("
function updateDurationValueDisplay(value) {
    document.getElementById('duration-input').value = value; // Update the input box when slider changes
}

function updateDurationSlider(value) {
    var slider = document.getElementById('duration-slider');
    var max = parseInt(slider.max, 10);
    var min = parseInt(slider.min, 10);
    value = parseInt(value, 10) || min; // Ensure we have a number, default to min if not
    value = Math.max(min, Math.min(max, value)); // Constrain the value between min and max
    slider.value = value; // Update the slider
    document.getElementById('duration-input').value = value; // Ensure input box matches constrained value
}
", \yii\web\View::POS_END);


/*
$content .= '<div class="row"><div class="col-md-9">';
$content .= $form->field($relationModel, 'duration')->input('range', [
    'min' => 5,
    'max' => 300,
    'step' => 1,
    'oninput' => 'updateDurationValue(this.value)',
    'class' => 'form-range'
]);
$content .= '</div><div class="col-md-3">';

$content .= '<div class="d-flex align-items-center justify-content-center h-100"><span id="duration-value-display">' . Html::encode($relationModel->duration ?: 5) .'</span></div>';
$content .= '</div></div>';
*/