<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\components\MyUrl;
use app\widgets\Alert;
use yii\bootstrap5\Html;
use yii\bootstrap5\NavBar;
use yii\bootstrap5\Nav;
use app\components\MyMenu;
use app\widgets\Collapse;
use yii\web\View;


AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);

?>
<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="<?php echo Yii::$app->language; ?>" class="h-100">
<head>
    <title><?php echo Html::encode($this->title); ?></title>
    <?php $this->head(); ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody(); ?>

<header id="header">
<?php

    $menuItems = new MyMenu();

    NavBar::begin([
        'brandLabel' => '&nbsp;'.Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => ['class' => 'navbar-expand-md navbar-dark bg-primary fixed-top p-0'],
        'innerContainerOptions' => ['class' => ''],
        'renderInnerContainer' => false,
    ]);

    echo Nav::widget([ // left aligned nav menu items
        'options' => ['class' => 'navbar-nav'],
        'items' => [
            ...$menuItems->getAuthorizedBusinessMenu(),
            ...$menuItems->getNavItems(),
        ]
    ]);

    echo "<div class='ms-auto'>"; // right aligned nav menu
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav'],
        'encodeLabels' => false,
        'items' => [
            $menuItems->getLangNavItems(), // language specific menu items
            $menuItems->getLogNavItems(), // login/logout specific menu items
        ],
    ]);
    echo "</div>";

    NavBar::end();

?>
</header>

<main id="main" class="flex-shrink-0 h-100" role="main">
    <div class="container-fluid h-100">
        <div class="row h-100">

            <div class="col<?= isset($this->params['noPadding']) ? ' p-0':' p-5' ?> rightContent h-100" id="main-content">
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
