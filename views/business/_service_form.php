<?php

use app\widgets\Card;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var yii\web\View $this */
/* @var app\models\Service|app\models\Rule|app\models\Resource $relationModel */
/* @var app\models\Business $model */

$resourceTypes = ArrayHelper::map($model->getResources()->andWhere(['deleted_at' => null])->all(), 'resource_type', 'resource_type');
//$expertTypes = ArrayHelper::map(ExpertType::find()->all(), 'id', 'name');    

$form = ActiveForm::begin();
$content = $form->field($relationModel, 'name')->textInput(['maxlength' => true]);
//$content .= $form->field($relationModel, 'resource_type')->textInput(['maxlength' => true]);
$content .= $form->field($relationModel, 'resource_type')->dropDownList($resourceTypes, ['prompt' => Yii::t('app', 'Select Resource Type')]);
$content .= $form->field($relationModel, 'expert_type')->textInput(['maxlength' => true]);
//$content .= $form->field($relationModel, 'duration')->textInput(['maxlength' => true]);
//$content .= '<input type="range" class="form-range" min="5" max="600" step="1" id="customRange3">';

// Add a Bootstrap 5 range slider for duration
$content .= $form->field($relationModel, 'duration')->input('range', [
    'min' => 5,
    'max' => 300,
    'step' => 1,
    'oninput' => 'updateDurationValue(this.value)',
    'class' => 'form-range'
]);
// Display the current value of the slider
$content .= '<div id="duration-value-display">' . Html::encode($relationModel->duration ?: 5) . '</div>';

$content .= Html::submitButton(
    $relationModel->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'),
    ['class' => 'btn btn-primary']
);
echo Card::widget([
    'title' => '', 
    'content' => $content,
]);
ActiveForm::end();
// Include JavaScript to update the display as the slider value changes
$this->registerJs("
    function updateDurationValue(value) {
        document.getElementById('duration-value-display').textContent = value;
    }
", \yii\web\View::POS_HEAD);
