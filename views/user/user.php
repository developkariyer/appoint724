<?php

use app\widgets\Card;
use yii\bootstrap5\ActiveForm;
use app\models\form\UserForm;

/** @var yii\web\View $this */
/** @var app\models\form\UserForm $model */
/** @var ActiveForm $form */

?>
<div class="user-operations">
    <div class="row justify-content-md-center mt-5">
        <div class="col-md-8 col-lg-6">
            <?php $form = ActiveForm::begin(['id' => 'user-form', 'enableAjaxValidation' => true]); ?>
            <?php 
                switch ($model->scenario) {
                    case UserForm::SCENARIO_REGISTER:
                        $this->title = Yii::t('app', 'New User');
                        $content =  $this->render('_update', ['form' => $form, 'model' => $model, 'restricted' => false]) .
                                    $this->render('_password', ['form' => $form, 'model' => $model]);
                        break;
                    case UserForm::SCENARIO_UPDATE:
                        $this->title = Yii::t('app', 'Update User');
                        $content =  $this->render('_update', ['form' => $form, 'model' => $model, 'restricted' => $restricted ?? true ]);
                        break;
                    case UserForm::SCENARIO_ADD:
                        $this->title = Yii::t('app', 'Add User');
                        $content =  $this->render('_update', ['form' => $form, 'model' => $model, 'restricted' => false]);
                        break;
                    case UserForm::SCENARIO_PASSWORD:
                        $this->title = Yii::t('app', 'Change Password');
                        $content =  $this->render('_password', ['form' => $form, 'model' => $model]);
                        break;
                }
                echo Card::widget([
                    'title' => $this->title,
                    'content' => $content.
                        $this->render('_submit', ['form' => $form, 'model' => $model]),
                ]);

            ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
