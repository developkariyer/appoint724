<?php

use app\components\MyUrl;
use app\widgets\Card;
use app\models\form\LoginForm;

/* @var yii\web\View $this */
/* @var yii\bootstrap5\ActiveForm $form */
/* @var app\models\form\LoginForm $model */

$this->title = $model->scenariodesc;
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? $this->title]);

$this->registerCss("
    .nav-tabs .nav-link {
        padding: 3px 8px;
        font-size: 1rem;
    }
");

?>

<div class="site-login">
    <div class="row justify-content-md-center mt-5">
        <div class="col-md-8 col-lg-6">
            <nav>
                <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                    <a href="<?php echo MyUrl::to(['site/login/'.LoginForm::SCENARIO_PASSWORD]); ?>" class="nav-link <?= ($model->scenario === LoginForm::SCENARIO_PASSWORD) ? 'active':'' ?>" role="tab"><?= Yii::t('app', 'Password') ?></a>
                    <a href="<?php echo MyUrl::to(['site/login/'.LoginForm::SCENARIO_SMS_REQUEST]); ?>" class="nav-link <?= ($model->scenario === LoginForm::SCENARIO_SMS_REQUEST || $model->scenario === LoginForm::SCENARIO_SMS_VALIDATE) ? 'active':'' ?>" role="tab"><?= Yii::t('app', 'SMS') ?></a>
                    <a href="<?php echo MyUrl::to(['site/login/'.LoginForm::SCENARIO_LINK]); ?>" class="nav-link <?= ($model->scenario === LoginForm::SCENARIO_LINK) ? 'active':'' ?>" role="tab"><?= Yii::t('app', 'Link') ?></a>
                    <a href="<?php echo MyUrl::to(['site/login/'.LoginForm::SCENARIO_OTHER]); ?>" class="nav-link <?= ($model->scenario === LoginForm::SCENARIO_OTHER) ? 'active':'' ?>" role="tab"><?= Yii::t('app', 'Other') ?></a>
                    <a href="<?php echo MyUrl::to(['user/register/']); ?>" class="nav-link link-danger" role="tab"><?= Yii::t('app', 'New User') ?></a>
                </div>
            </nav>
            <?php switch ($model->scenario) {
                case LoginForm::SCENARIO_PASSWORD:
                default:
                    $title = Yii::t('app', 'Login with Password');
                    $content = $this->render('_password', ['model' => $model]);
                    break;
                case LoginForm::SCENARIO_SMS_REQUEST:
                    $title = Yii::t('app', 'Login with SMS');
                    $content = $this->render('_sms_request', ['model' => $model]);
                    break;
                case LoginForm::SCENARIO_SMS_VALIDATE:
                    $title = Yii::t('app', 'Login with SMS');
                    $content = $this->render('_sms_validate', ['model' => $model]);
                    break;
                case LoginForm::SCENARIO_LINK:
                    $title = Yii::t('app', 'Login with Link');
                    $content = $this->render('_link', ['model' => $model]);
                    break;
                case LoginForm::SCENARIO_OTHER:
                    $title = Yii::t('app', 'Other');
                    $content = $this->render('_other', ['model' => $model]);
                    break;                        
            } 
            try {
                echo Card::widget([
                    'title' => $title,
                    'content' => $content,
                ]);
            } catch (Throwable $e) {
            } ?>
            <div class="row">
                <div class="col-6">
                    <p><h3><?= Yii::t('text', 'I forgot my password.') ?></h3></p>
                    <p><?= Yii::t('text', 'You do not need a password to login. You can use SMS, Link or Other methods. If you want, you can easily set a password after logging in.') ?></p>
                </div>
                <div class="col-6">
                    <p><h3><?= Yii::t('text', 'My GSM not accepted.') ?></h3></p>
                    <p><?= Yii::t('text', 'For the moment, only GSM numbers from Turkey are accepted. You have to enter your GSM number in this format: (555) 123 45 67') ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

