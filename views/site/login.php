<?php

/* @var yii\web\View $this */
/* @var yii\bootstrap5\ActiveForm $form */

/* @var app\models\LoginForm $model */

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss("
    .nav-tabs .nav-link {
        padding: 5px 15px;
        font-size: 0.85rem;
    }
");

if ($model->smsform) {
    $tabLabels = ['disabled', 'active', 'disabled', 'disabled'];
    $tabs = ['', 'show active', '', ''];
} else {
    $tabLabels = ['active', '', '', ''];
    $tabs = ['show active', '', '', ''];    
}

?>

<div class="site-login">
    <h1><?php echo yii\helpers\Html::encode($this->title); ?></h1>
    <?php $form = yii\bootstrap5\ActiveForm::begin(['id' => 'login-form', 'enableAjaxValidation' => true]); ?>
    <div class="row justify-content-md-center mt-5">
        <div class="col-md-6 col-lg-4">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link <?php echo $tabLabels[0]; ?>" id="nav-passwordlogin-tab" data-bs-toggle="tab" data-bs-target="#nav-passwordlogin" type="button" role="tab" aria-controls="nav-passwordlogin" aria-selected="true"><?= Yii::t('app', 'Password') ?></button>
                    <button class="nav-link <?php echo $tabLabels[1]; ?>" id="nav-smslogin-tab" data-bs-toggle="tab" data-bs-target="#nav-smslogin" type="button" role="tab" aria-controls="nav-smslogin" aria-selected="false"><?= Yii::t('app', 'SMS') ?></button>
                    <button class="nav-link <?php echo $tabLabels[2]; ?>" id="nav-linklogin-tab" data-bs-toggle="tab" data-bs-target="#nav-linklogin" type="button" role="tab" aria-controls="nav-linklogin" aria-selected="false"><?= Yii::t('app', 'Link') ?></button>
                    <button class="nav-link <?php echo $tabLabels[3]; ?>" id="nav-otherlogin-tab" data-bs-toggle="tab" data-bs-target="#nav-otherlogin" type="button" role="tab" aria-controls="nav-otherlogin" aria-selected="false"><?= Yii::t('app', 'Other') ?></button>
                </div>
            </nav>
            <div class="card shadow p-1 mb-5 bg-white rounded">
                <div class="card-body">
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade <?php echo $tabs[0]; ?> p-3" id="nav-passwordlogin" role="tabpanel" aria-labelledby="nav-passwordlogin-tab">
                            <h5 class="p-3 text-center mb-3"><?= Yii::t('app', 'Login with Password') ?></h5>
                            <?php echo $form->field($model, 'email')->textInput(); ?>
                            <?php echo $form->field($model, 'password')->passwordInput(); ?>
                            <?php echo yii\helpers\Html::submitButton(Yii::t('app', 'Login'), ['name' => 'action', 'value' => 'password', 'class' => 'btn btn-primary']); ?>
                        </div>
                        <div class="tab-pane fade <?php echo $tabs[1]; ?> p-3" id="nav-smslogin" role="tabpanel" aria-labelledby="nav-smslogin-tab">
                            <h5 class="p-3 text-center mb-3"><?= Yii::t('app', 'Login with SMS') ?></h5>
                            <?php echo $form->field($model, 'gsm')->textInput(['readonly' => $model->smsform ? true : false]); ?>
                            <?php if ($model->smsform) { ?>
                                <div class="text-center"><h4><span id="countdown"></span></h4></div>
                                <?php echo $form->field($model, 'sms')->textInput(); ?>
                                <?php echo yii\helpers\Html::submitButton(Yii::t('app', 'Verify SMS'), ['name' => 'action', 'value' => 'sms_verify', 'class' => 'btn btn-primary']);?>
                                <?php echo yii\helpers\Html::a(Yii::t('app', 'Try other methods'), ['/login'], ['style' => 'float: right;']);?>
                            <?php } else { ?>
                                <?php echo $form->field($model, 'sms')->hiddenInput()->label(false); ?>
                                <?php echo yii\helpers\Html::submitButton(Yii::t('app', 'Send SMS'), ['name' => 'action', 'value' => 'sms_request', 'class' => 'btn btn-primary']); ?>
                            <?php } ?>
                        </div>
                        <div class="tab-pane fade <?php echo $tabs[2]; ?> p-3" id="nav-linklogin" role="tabpanel" aria-labelledby="nav-linklogin-tab">
                            <h4 class="p-3 text-center mb-3"><?= Yii::t('app', 'Login with Link') ?></h4>
                            <?php echo $form->field($model, 'emaillink')->textInput(); ?>
                            <?php echo yii\helpers\Html::submitButton(Yii::t('app', 'Send Link'), ['name' => 'action', 'value' => 'link', 'class' => 'btn btn-primary']); ?>
                        </div>
                        <div class="tab-pane fade <?php echo $tabs[3]; ?> p-3" id="nav-otherlogin" role="tabpanel" aria-labelledby="nav-otherlogin-tab">
                            <h4 class="p-3 text-center mb-3"><?= Yii::t('app', 'Other Login Options') ?></h4>
                            <div class="text-center">
                                <p><?= Yii::t('app', 'Coming soon...') ?></p>
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


<script>
document.addEventListener("DOMContentLoaded", function() {
    var countdownElement = document.getElementById('countdown');
    var timeLeft = 120;

    var countdownTimer = setInterval(function() {
        if (timeLeft <= 0) {
            clearInterval(countdownTimer);
            countdownElement.innerHTML = "<?= Yii::t('app', 'SMS Expired') ?>";
        } else {
            countdownElement.innerHTML = timeLeft + ' ' + '<?= Yii::t('app', 'seconds remaining') ?>';
        }
        timeLeft -= 1;
    }, 1000);
});
</script>