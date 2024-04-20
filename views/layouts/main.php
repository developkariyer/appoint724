<?php

/** @var yii\web\View $this */
/* @var string $content */

use app\assets\AppAsset;
use app\components\MyUrl;
use app\widgets\Alert;
use app\widgets\Card;
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
            'items' => MyMenu::getNavItems(), // left aligned nav menu items, currently empty
            'encodeLabels' => false,
        ]);
    } catch (Throwable $e) {
    }

    echo "<div class='ms-auto'>"; // right aligned nav menu
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

        <?php if (!Yii::$app->user->isGuest) : ?>
            <div class="leftMenu h-100 bg-secondary bg-gradient p-0">
                <div class="list-group h-100 p-2">
                <?php try {
                    echo Collapse::widget([
                        'items' => MyMenu::getLeftMenuItems(),
                    ]);
                } catch (Throwable $e) {
                } ?>
                <br />
                <?= Yii::$app->user->identity->user->superadmin || Yii::$app->user->identity->user->remainingBusinessCount ? Html::a(Yii::t('app', 'Create Business'), MyUrl::to(['business/create']), ['class' => 'btn btn-primary btn-outline-light']) : '' ?>
                <?= !Yii::$app->user->identity->user->superadmin && !Yii::$app->user->identity->user->remainingBusinessCount ? Html::a(Yii::t('app', 'Buy Business Slot'), MyUrl::to(['business/slot']), ['class' => 'btn btn-primary btn-outline-light']) : '' ?>
                </div>
            </div>
        <?php endif; ?>

            <div class="col p-5 rightContent h-100" id="main-content">
                <?php try {
                    echo Alert::widget();
                } catch (Throwable $e) {
                } ?>
                <?= $content ?>
                <br />
                <?php /* echo
                    Card::widget([
                        'title' => Yii::t('app', 'Debugging'),
                        'content' => '<pre>'.print_r(Yii::$app->user->identity->user->getBusinesses()->active()->orderBy('name')->all(), true).'</pre>',
                    ]);*/
                ?>
            </div>

        </div>
    </div>
</main>

<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage(); ?>
