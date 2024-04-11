<?php

namespace app\controllers;

use app\models\ContactForm;
use app\models\LoginForm;
use Yii;
use yii\bootstrap5\ActiveForm;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;
use app\components\MyUrl;


class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
            'languageBehavior' => [
                'class' => \app\components\LanguageBehavior::class,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionReroute($path)
    {
        $requestUrl = Yii::$app->request->url;
        $baseUrl = Yii::$app->request->baseUrl; // Gets the base URL of the application
    
        if (trim($requestUrl, '/') === trim($baseUrl, '/')) {
            return $this->redirect([Yii::$app->language.'/']);
        }

        echo Yii::$app->request->url;
        exit;
    }

    public function actionVerifymyemail()
    {
        if (!Yii::$app->user->isGuest) {
            $email_token = \app\models\Authidentity::generateEmailToken(Yii::$app->user->identity->user->email);
            Yii::$app->session->setFlash('info', "***********".\yii\helpers\Html::a($email_token, ['verifyemail/'.$email_token], ['class' => 'alert-link'])."************");
            if ($email_token !== false) {
                Yii::$app->session->setFlash('error', Yii::t('app','Check your e-mail for a login link.'));
            } else {
                Yii::$app->session->setFlash('error', Yii::t('app','Unable to e-mail a login link.'));
            }
            return $this->goBack();
        } else {
            return $this->redirect(['login', 's' => 8]);
        }
    }

    public function actionVerifyemail($token)
    {
        $authidentity = \app\models\Authidentity::findIdentityByEmailToken($token);
        if ($authidentity) {
            $authidentity->expires = date('Y-m-d H:i:s', strtotime('+10 seconds'));
            $authidentity->save(false);
            $user = $authidentity->user;
            $user->emailverified = true;
            $user->save(false);
            Yii::$app->user->login($authidentity, 3600 * 24 * 30);
            Yii::$app->session->setFlash('info', Yii::t('app','Login/verification successful.'));
            return $this->goHome();
        }
        Yii::$app->session->setFlash('error', Yii::t('app', 'Token invalid or expired.'));
        return $this->goHome();
    }

    public function actionVerifytcno()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goBack();
        }
        $user = Yii::$app->user->identity->user;
        if (!$user) {
            return $this->goBack();
        }
        $soapRequest = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<soap12:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap12="http://www.w3.org/2003/05/soap-envelope">
  <soap12:Body>
    <TCKimlikNoDogrula xmlns="http://tckimlik.nvi.gov.tr/WS">
      <TCKimlikNo>{$user->tcno}</TCKimlikNo>
      <Ad>{$user->first_name}</Ad>
      <Soyad>{$user->last_name}</Soyad>
      <DogumYili>{$user->dogum_yili}</DogumYili>
    </TCKimlikNoDogrula>
  </soap12:Body>
</soap12:Envelope>
XML;

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://tckimlik.nvi.gov.tr/Service/KPSPublic.asmx",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $soapRequest,
            CURLOPT_HTTPHEADER => [
                "content-type: application/soap+xml; charset=utf-8",
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if (!$err) {
            if (trim(strip_tags($response)) === 'true') {
                $user->tcnoverified = true;
                $user->save(false);
                Yii::$app->session->setFlash('info', Yii::t('app', 'T.C. Identity Number verified.'));
                return $this->goBack();
            }
        }
        Yii::$app->session->setFlash('error', Yii::t('app', 'Unknown error:').$err);
        return $this->goBack();
    }

    public function actionVerifygsm()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goBack();
        }

        $model = new LoginForm();
        $model->scenario = LoginForm::SCENARIO_SMS_VALIDATE;
        $model->gsm = Yii::$app->user->identity->user->gsm;

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $retval = ActiveForm::validate($model);
            return $retval;
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate() && $model->login()) {
                // no need to do anything, login automatically verifies GSM if successful
                Yii::$app->session->setFlash('info', Yii::t('app','Login/verification successful.'));
                return $this->goHome();
            }
        } else {
            $sms_otp = \app\models\Authidentity::generateSmsPin($model->gsm);
            // send sms_otp via SMS service API call
            Yii::$app->session->setFlash('info', "*********** $sms_otp ************");
        }

        return $this->render('@app/views/authidentity/sms_validate', ['model' => $model]);
    }

    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest) {
            $authidentity = Yii::$app->user->identity;
    
            $messages = [];
            if (!$authidentity->user->tcnoverified) {   
                $url = \yii\helpers\Html::a(Yii::t('app', 'Please update your profile.'), MyUrl::to(['user/update']), ['class' => 'alert-link']);
                $url2 =  \yii\helpers\Html::a(Yii::t('app', 'Click here to verify.'), MyUrl::to(['site/verifytcno']), ['class' => 'alert-link']);
                $messages[] = Yii::t('app', 'Your T.C. No is not verified.')." {$url} - {$url2}";
            }
            if (!$authidentity->user->gsmverified) {
                $url = \yii\helpers\Html::a(Yii::t('app', 'Please verify your GSM number.'), MyUrl::to(['site/verifygsm']), ['class' => 'alert-link']);
                $messages[] = Yii::t('app', "Your GSM number is not verified.")." {$url}";
            }
            if (!$authidentity->user->emailverified) {
                $url = \yii\helpers\Html::a(Yii::t('app', 'Please verify your e-mail.'), MyUrl::to(['site/verifymyemail']), ['class' => 'alert-link']);
                $messages[] = Yii::t('app', "Your e-mail address is not verified.")." {$url}";
            }
            if (count($messages)) Yii::$app->session->setFlash('warning', $messages);
        }
            
        if (Yii::$app->user->isGuest) {
            return $this->redirect(MyUrl::to(['site/login']));
        } else {
            if (Yii::$app->user->identity->user->superadmin) {
                return $this->redirect(MyUrl::to(['site/superadmin']));
            } else {
                // later, business logic will be implemented here *****
                //return $this->redirect(MyUrl::to(['site/index']));
                return $this->render('index');
            }
        }
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if (Yii::$app->request->isPost) {
            $model->scenario = Yii::$app->request->post('action');
        } else {
            $model->scenario = Yii::$app->request->get('s', LoginForm::SCENARIO_PASSWORD);
        }

        $allowed_scenarios = array_keys($model->scenarios());
        if (!in_array($model->scenario, $allowed_scenarios)) {
            $model->scenario = LoginForm::SCENARIO_PASSWORD;
        }

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $retval = ActiveForm::validate($model);
            return $retval;
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                switch ($model->scenario) {
                    case LoginForm::SCENARIO_PASSWORD:
                    case LoginForm::SCENARIO_SMS_VALIDATE:
                        if ($model->login()) {
                            $this->setHomePage();
                            Yii::$app->session->setFlash('info', Yii::t('app','Login successful.'));
                            return $this->goHome();
                        } else {
                            if ($model->scenario === LoginForm::SCENARIO_SMS_VALIDATE) {
                                $model->addError('smsotp', Yii::t('app', 'Unable to authenticate'));
                            } else {
                                $model->addError('password', Yii::t('app', 'Unable to authenticate'));
                            }
                        }
                        break;
                    case LoginForm::SCENARIO_SMS_REQUEST:
                        $sms_otp = \app\models\Authidentity::generateSmsPin($model->gsm);
                        Yii::$app->session->setFlash('info', "*********** $sms_otp ************");
                        if ($sms_otp !== false) {
                            if ($sms_otp !==true ) { /* send sms via external api call, to be implemented */ }
                            $model->scenario=LoginForm::SCENARIO_SMS_VALIDATE;
                        } else {
                            $model->addError('gsm', Yii::t('app', 'Unable to send an SMS'));
                        }
                        break;
                    case LoginForm::SCENARIO_EMAIL_LINK:
                        $email_token = \app\models\Authidentity::generateEmailToken($model->emaillink);
                        Yii::$app->session->setFlash('info', "***********".\yii\helpers\Html::a($email_token, ['verifyemail/'.$email_token], ['class' => 'alert-link'])."************");
                        if ($email_token !== false) {
                            Yii::$app->session->setFlash('warning', Yii::t('app','Check your e-mail for a login link.'));
                            return $this->goHome();
                        } else {
                            $model->addError('emaillink', Yii::t('app', 'Unable to e-mail a login link.'));
                        }
                        break;
                    case LoginForm::SCENARIO_OTHER:
                        break;
                }
            }
        } else {
            if ($model->scenario === LoginForm::SCENARIO_SMS_VALIDATE) {
                $model->scenario = LoginForm::SCENARIO_SMS_REQUEST;
            }
        }
        return $this->render('@app/views/authidentity/login', ['model' => $model]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        Yii::$app->session->setFlash('warning', Yii::t('app','Log out successful. See you soon.'));
        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }

        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    private function setHomePage() 
    {
    }

    public function actionSuperadmin()
    {
        if (Yii::$app->user->isGuest || !Yii::$app->user->identity->user->superadmin) {
            $this->setHomePage();
            return $this->goHome();
        }

        return $this->render('superadmin');
    }

}
