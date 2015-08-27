<?php
use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\bootstrap\Dropdown;
use yii\bootstrap\Collapse;
use yii\bootstrap\Tabs;
use yii\widgets\Menu;
use yii\widgets\Breadcrumbs;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <script src="/js/jquery-1.10.2.min.js"></script>
    <script src="/js/common.js"></script>

</head>
<body>

<?php

$this->beginBody() ?>
            <?= $content ?>



<?php $this->endBody() ?>
<link rel="stylesheet" href="/js/artdialog/css/ui-dialog.css">
<script src="/js/artdialog/dist/dialog-min.js"></script>
<script src="/js/artdialog/dist/dialog-plus.js"></script>
<input type="hidden" id="base_url" value="<?php echo Yii::$app->params['baseUrl']; ?>" />
</body>
</html>
<?php $this->endPage() ?>
