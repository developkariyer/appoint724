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
        'options' => ['class' => 'navbar-expand-md navbar-dark bg-dark fixed-top'],
    ]);
/*
    $languageItems = [
        ['label' => 'English', 'url' => ['/site/language', 'lang' => 'en-US']],
        ['label' => 'Deutsch', 'url' => ['/site/language', 'lang' => 'de']],
        ['label' => 'Türkçe', 'url' => ['/site/language', 'lang' => 'tr']],
    ];
*/

    $languageItems = [
        'en-US'=>[
            'label' => Html::img(Url::to('@web/images/flags/en-US.png'), [
                'style' => 'height: 20px; vertical-align: middle;', // Adjust dimensions as needed
                'alt' => 'English',
            ]),
            'url' => ['/site/language', 'lang' => 'en-US'],
            'encode' => false,
        ],
        'de'=>[
            'label' => Html::img(Url::to('@web/images/flags/de.png'), [
                'style' => 'height: 20px; vertical-align: middle;',
                'alt' => 'Deutsch',
            ]),
            'url' => ['/site/language', 'lang' => 'de'],
            'encode' => false,
        ],
        'tr'=>[
            'label' => Html::img(Url::to('@web/images/flags/tr.png'), [
                'style' => 'height: 20px; vertical-align: middle;',
                'alt' => 'Türkçe',
            ]),
            'url' => ['/site/language', 'lang' => 'tr'],
            'encode' => false,
        ],
    ];
/*
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav'],
        'items' => [
            ['label' => Yii::t('app', 'Home'), 'url' => ['/site/index'], 'encode'=>false],
            ['label' => Yii::t('app', 'About'), 'url' => ['/site/about']],
            ['label' => Yii::t('app', 'Contact'), 'url' => ['/site/contact']],
        ],
    ]);
*/
    echo "<div class='ms-auto'>";
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav lang-nav-bar'],
        'items' => [
            [
                'options' => ['class' => 'lang-nav-bar'],
                'label' => $languageItems[Yii::$app->language]['label'],
                'encode' => false,
                'items' => $languageItems,
            ],
            Yii::$app->user->isGuest
                ? ['label' => Yii::t('app', 'Login'), 'url' => ['/site/login']]
                : '<li class="nav-item">'
                    .Html::beginForm(['/site/logout'])
                    .Html::submitButton(
                        '<i class="lni lni-exit"></i> '.Yii::$app->user->identity->username,
                        ['class' => 'nav-link btn btn-link logout']
                    )
                    .Html::endForm()
                    .'</li>',
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

<footer id="footer" class="mt-auto py-3 bg-light">
    <div class="container">
        <div class="row text-muted">
            <div class="col-md-6 text-center text-md-start">&copy; Appointment SAAS <?php echo date('Y'); ?></div>
            <div class="col-md-6 text-center text-md-end">iDeaMetric</div>
        </div>
    </div>
</footer>

<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage(); ?>
