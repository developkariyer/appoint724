<?php

/** @var yii\web\View $this */
/* @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Html;
use yii\bootstrap5\NavBar;
use yii\bootstrap5\Nav;
use app\components\MyMenu;
use app\widgets\Collapse;


AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);

$this->registerCss("
    .langFlags {
        height: 20px;
        vertical-align: middle;
    }
    .leftMenu {
        width: 200px;
        overflow-y: auto; 
        overflow-x: hidden;
    }
    .rightContent {
        overflow-y: auto; 
        overflow-x: hidden; 
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
    }
    .accordion-header:hover, .list-group-item:hover  {
        background-color: #dad7f8;
        color: #241c72;
        cursor: pointer;
    }
    .submenu-item {
        padding-left: 2rem;
    }
    .accordion-collapse {
        transition: height 0.5s ease;
    }   
    
    /*
    .main-item {
        font-weight: bold;
    }*/
");

?>
<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="<?php echo Yii::$app->language; ?>" class="h-100">
<head>
    <title><?php echo Html::encode($this->title); ?></title>
    <?php $this->head(); ?>
    <link rel="icon" type="image/png" sizes="32x32" href="/web/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/web/favicon-16x16.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/web/apple-touch-icon.png">
    <link rel="manifest" href="/web/site.webmanifest">
    <link rel="mask-icon" href="/web/safari-pinned-tab.svg" color="#5bbad5">
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
        'options' => ['class' => 'navbar-expand-md navbar-dark bg-primary fixed-top p-0'],
        'innerContainerOptions' => ['class' => ''],
        'renderInnerContainer' => false,
    ]);

    try {
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav'],
            'items' => MyMenu::getNavItems(), // menu items
            'encodeLabels' => false,
        ]);
    } catch (Throwable $e) {
    }

    echo "<div class='ms-auto'>";
    try {
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav'],
            'items' => [
                MyMenu::getLangNavItems(), // language specific menu items
                MyMenu::getLogNavItems(), // login/logout specific menu items
            ],
            'encodeLabels' => false,
        ]);
    } catch (Throwable $e) {
    }
    echo "</div>";

    NavBar::end();

?>
</header>

<main id="main" class="flex-shrink-0 h-100" role="main">
    <div class="container-fluid h-100">
        <div class="row h-100">
            <div class="leftMenu h-100 bg-secondary bg-gradient p-0">
                <div class="list-group h-100 p-2">
                <?php try {
                    echo Collapse::widget([
                        'items' => MyMenu::getLeftMenuItems(),
                    ]);
                } catch (Throwable $e) {
                } ?>
                </div>
            </div>
            <div class="col p-5 rightContent h-100" id="main-content">
                <?php try {
                    echo Alert::widget();
                } catch (Throwable $e) {
                } ?>
                <?= $content ?>
            </div>

        </div>
    </div>
</main>

<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage(); ?>
