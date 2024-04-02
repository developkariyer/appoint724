<?php

/* @var yii\web\View $this */
/* @var yii\bootstrap5\ActiveForm $form */

/* @var app\models\LoginForm $model */

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;

$tabLabels = ['active', '', '', ''];
$tabs = ['show active', '', '', ''];

?>

<div class="site-login">
    <h1><?php echo yii\helpers\Html::encode($this->title); ?></h1>
    <?php $form = yii\bootstrap5\ActiveForm::begin(['id' => 'login-form', 'enableAjaxValidation' => true]); ?>
    <div class="row justify-content-md-center mt-5">
        <div class="col-md-6 col-lg-4">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link <?php echo $tabLabels[0]; ?>" id="nav-passwordlogin-tab" data-bs-toggle="tab" data-bs-target="#nav-passwordlogin" type="button" role="tab" aria-controls="nav-passwordlogin" aria-selected="true">Password</button>
                    <button class="nav-link <?php echo $tabLabels[1]; ?>" id="nav-smslogin-tab" data-bs-toggle="tab" data-bs-target="#nav-smslogin" type="button" role="tab" aria-controls="nav-smslogin" aria-selected="false">SMS</button>
                    <button class="nav-link <?php echo $tabLabels[2]; ?>" id="nav-linklogin-tab" data-bs-toggle="tab" data-bs-target="#nav-linklogin" type="button" role="tab" aria-controls="nav-linklogin" aria-selected="false">Link</button>
                    <button class="nav-link <?php echo $tabLabels[3]; ?>" id="nav-otherlogin-tab" data-bs-toggle="tab" data-bs-target="#nav-otherlogin" type="button" role="tab" aria-controls="nav-otherlogin" aria-selected="false">Other</button>
                </div>
            </nav>
            <div class="card shadow p-1 mb-5 bg-white rounded">
                <div class="card-body">
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade <?php echo $tabs[0]; ?> p-3" id="nav-passwordlogin" role="tabpanel" aria-labelledby="nav-passwordlogin-tab">
                            <h4 class="p-3 text-center mb-3">Login with Password</h4>
                            <?php echo $form->field($model, 'email')->textInput(); ?>
                            <?php echo $form->field($model, 'password')->passwordInput(); ?>
                            <?php echo yii\helpers\Html::submitButton('Login', ['name' => 'action', 'value' => 'password', 'class' => 'btn btn-primary']); ?>
                        </div>
                        <div class="tab-pane fade <?php echo $tabs[1]; ?> p-3" id="nav-smslogin" role="tabpanel" aria-labelledby="nav-smslogin-tab">
                            <h4 class="p-3 text-center mb-3">Login with SMS</h4>

                                <?php //echo $form->field($model, 'gsm')->hiddenInput()->label(false);?>
                                <?php //echo $form->field($model, 'sms')->textInput();?>
                                <?php //echo yii\helpers\Html::submitButton('Verify SMS', ['name' => 'scenario', 'value' => 'sms_verify', 'class' => 'btn btn-primary']);?>
                                <?php //echo yii\helpers\Html::a('Try other methods', ['/login'], ['style' => 'float: right;']);?>

                                <?php echo $form->field($model, 'gsm')->textInput(); ?>
                                <?php echo yii\helpers\Html::submitButton('Send SMS', ['name' => 'action', 'value' => 'sms_request', 'class' => 'btn btn-primary']); ?>
                        </div>
                        <div class="tab-pane fade <?php echo $tabs[2]; ?> p-3" id="nav-linklogin" role="tabpanel" aria-labelledby="nav-linklogin-tab">
                            <h4 class="p-3 text-center mb-3">Login with Link</h4>
                            <?php echo $form->field($model, 'emaillink')->textInput(); ?>
                            <?php echo yii\helpers\Html::submitButton('Send Link', ['name' => 'action', 'value' => 'link', 'class' => 'btn btn-primary']); ?>
                        </div>
                        <div class="tab-pane fade <?php echo $tabs[3]; ?> p-3" id="nav-otherlogin" role="tabpanel" aria-labelledby="nav-otherlogin-tab">
                            <h4 class="p-3 text-center mb-3">Other Login Options</h4>
                            <div class="text-center">
                                <p>Coming soon...</p>
                            </div>
                        </div>
                    </div>
                    <?php echo $form->errorSummary($model); ?>
                </div>
            </div>
        </div>
    </div>
    <?php yii\bootstrap5\ActiveForm::end(); ?>
</div>


