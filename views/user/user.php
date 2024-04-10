<?php

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
    <h1><?php echo yii\helpers\Html::encode($this->title); ?></h1>
    <div class="row justify-content-md-center mt-5">
        <div class="col-md-6 col-lg-4">
            <?php $form = yii\bootstrap5\ActiveForm::begin(['id' => 'user-form', 'enableAjaxValidation' => true]); ?>
            <?php 
                switch ($model->scenario) {
                    case UserForm::SCENARIO_REGISTER:
                        echo \app\widgets\Card::widget([
                            'title' => $this->title,
                            'content' => 
                                $this->render('_update', ['form' => $form, 'model' => $model]).
                                $this->render('_password', ['form' => $form, 'model' => $model]).
                                $this->render('_submit', ['form' => $form, 'model' => $model]),
                        ]);
                        break;
                    case UserForm::SCENARIO_UPDATE:
                        echo \app\widgets\Card::widget([
                            'title' => $this->title,
                            'content' => $this->render('_update', ['form' => $form, 'model' => $model]).
                                $this->render('_submit', ['form' => $form, 'model' => $model]),
                        ]);
                        break;
                    case UserForm::SCENARIO_PASSWORD:
                        echo \app\widgets\Card::widget([
                            'title' => $this->title,
                            'content' => $this->render('_password', ['form' => $form, 'model' => $model]).
                                $this->render('_submit', ['form' => $form, 'model' => $model]),
                        ]);
                        break;
                }
            ?>
            <?php yii\bootstrap5\ActiveForm::end(); ?>
        </div>
    </div>
</div>
