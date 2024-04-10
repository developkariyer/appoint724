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

    echo \app\widgets\Navigation::widget([
        'supportedLanguages' => Yii::$app->params['supportedLanguages'],
        'currentPath' => Yii::$app->request->getPathInfo(),
    ]);

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
