<?php

/** @var yii\web\View $this */
/* @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\helpers\Url;
use app\components\MyUrl;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);

$this->registerCss("
    .langflags {
        height: 20px;
        vertical-align: middle;
    }
");

?>
<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="<?php echo Yii::$app->language; ?>" class="h-100">
<head>
    <title><?php echo Html::encode($this->title); ?></title>
    <?php $this->head(); ?>
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody(); ?>

<header id="header">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => ['class' => 'navbar-expand-md navbar-dark bg-dark fixed-top'],
    ]);

    // build language selector dropdown
    $pathInfo = Yii::$app->request->getPathInfo();
    $segments = explode('/', $pathInfo);
    if (in_array($segments[0], array_keys(Yii::$app->params['supportedLanguages']))) unset($segments[0]);
    $languageItems = [];
    foreach (Yii::$app->params['supportedLanguages'] as $lang=>$alt) {
        $languageItems[$lang] = [
            'label' => Html::img(Url::to("@web/images/flags/{$lang}.png"), [
                'class' => 'langflags',
                'alt' => $alt,
            ]),
            'url' => ["/{$lang}/".implode('/', $segments)],
            'encode' => false,
        ];
    }

    $lognav = Yii::$app->user->isGuest ? ['label' => Yii::t('app', 'Login'), 'url' => ['/'.Yii::$app->language.'/site/login']] :
        [
            'label' => '<i class="lni lni-user"></i> '.Yii::$app->user->identity->username,
            'items' => [
                [
                    'label' => '<i class="lni lni-pencil"></i> '.Yii::t('app', 'User Information'),
                    'url' => ['/'.Yii::$app->language.'/user/update'],
                ],
                [
                    'label' => '',
                ],
                Html::beginForm(['/'.Yii::$app->language.'/site/logout'], 'post').
                Html::submitButton(
                    ' <i class="lni lni-exit"></i> '.Yii::t('app', 'Logout'),
                    ['class' => 'btn ']
                ).Html::endForm(),
            ]
        ];

    echo "<div class='ms-auto'>";
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav'],
        'items' => [
            [
                'options' => ['class' => 'lang-nav-bar'],
                'label' => $languageItems[Yii::$app->language]['label'],
                'encode' => false,
                'items' => $languageItems,
            ],
            $lognav,
        ],
        'encodeLabels' => false, // Ensure HTML content in labels is rendered
    ]);
    echo "</div>";

    NavBar::end();
    ?>
</header>

<main id="main" class="flex-shrink-0" role="main">
    <div class="container">
        <?php if (!empty($this->params['breadcrumbs'])) { ?>
            <?php echo Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]); ?>
        <?php } ?>
        <?php echo Alert::widget(); ?>
        <?php echo $content; ?>
    </div>
</main>

<footer id="footer" class="mt-auto py-1 bg-light">
    <div class="container">
        <div class="row text-muted">
            <div class="col-md-6 text-center text-md-start">&copy; Appointment SAAS</div>
            <div class="col-md-6 text-center text-md-end"><?php echo date('Y'); ?></div>
        </div>
    </div>
</footer>

<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage(); ?>
