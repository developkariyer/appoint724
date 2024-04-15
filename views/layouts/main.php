<?php

/** @var yii\web\View $this */
/* @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Html;
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
    .leftmenu {
        width: 200px;
        overflow-y: auto; 
        overflow-x: hidden;
    }
    .rightcontent {
        overflow-y: auto; 
        overflow-x: hidden; 
        /*border-top-left-radius: 0.25rem;*/
        background-color: #ffffff;
    }
    .navbar-brand {
        width: 200px;  
        white-space: nowrap;  
        overflow: hidden;  
        text-overflow: ellipsis;  
    }
    body {
        padding-top: 40px;
        background-color: #dc3545;
    }
    .accordion-header:hover, .list-group-item:hover  {
        background-color: #f8d7da;
        color: #721c24;
        cursor: pointer;
    }
    .submenu-item {
        padding-left: 2rem;
    }
    .main-item {
        font-weight: bold;
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

<main id="main" class="flex-shrink-0 h-100" role="main">
    <div class="container-fluid h-100">
        <div class="row h-100">
            <?= $this->render('menu') ?>
            <div class="col p-5 rightcontent h-100" id="main-content">
                <?php echo Alert::widget(); ?>
                <?php echo $content; ?>
            </div>

        </div>
    </div>
</main>

<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage(); ?>
