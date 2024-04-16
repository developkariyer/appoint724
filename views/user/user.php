<?php

use app\widgets\Card;
use yii\widgets\ActiveForm;
use app\models\UserForm;

/** @var yii\web\View $this */
/** @var app\models\UserForm $model */
/** @var ActiveForm $form */

switch ($model->scenario) {
    case UserForm::SCENARIO_REGISTER:
        $this->title = Yii::t('app', 'New User');
        break;
    case UserForm::SCENARIO_UPDATE:
        $this->title = Yii::t('app', 'Update User');
        break;
    case UserForm::SCENARIO_PASSWORD:
        $this->title = Yii::t('app', 'Change Password');
        break;
}

?>
<div class="user-operations">
    <div class="row justify-content-md-center mt-5">
        <div class="col-md-8 col-lg-6">
            <?php $form = yii\bootstrap5\ActiveForm::begin(['id' => 'user-form', 'enableAjaxValidation' => true]); ?>
            <?php 
                switch ($model->scenario) {
                    case UserForm::SCENARIO_REGISTER:
                        try {
                            echo Card::widget([
                                'title' => $this->title,
                                'content' =>
                                    $this->render('_update', ['form' => $form, 'model' => $model]) .
                                    $this->render('_password', ['form' => $form, 'model' => $model]) .
                                    $this->render('_submit', ['form' => $form, 'model' => $model]),
                            ]);
                        } catch (Throwable $e) {
                        }
                        break;
                    case UserForm::SCENARIO_UPDATE:
                        try {
                            echo Card::widget([
                                'title' => $this->title,
                                'content' => $this->render('_update', ['form' => $form, 'model' => $model]) .
                                    $this->render('_submit', ['form' => $form, 'model' => $model]),
                            ]);
                        } catch (Throwable $e) {
                        }
                        break;
                    case UserForm::SCENARIO_PASSWORD:
                        try {
                            echo Card::widget([
                                'title' => $this->title,
                                'content' => $this->render('_password', ['form' => $form, 'model' => $model]) .
                                    $this->render('_submit', ['form' => $form, 'model' => $model]),
                            ]);
                        } catch (Throwable $e) {
                        }
                        break;
                }
            ?>
            <?php yii\bootstrap5\ActiveForm::end(); ?>
        </div>
    </div>
</div>
