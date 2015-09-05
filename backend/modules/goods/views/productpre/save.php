<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = '添加待发布商品图片';
?>
<legends  style="fond-size:12px;">
    <ul class="breadcrumb">
        <li>
            <a href="/">首页</a>
        </li>
        <li><a href="/goods/productpre">待发布商品管理</a></li>
        <li><a href="/goods/productpre/list?id=<?= $_GET['id']?>">待发布图片管理</a></li>
        <li class="active">添加待发布商品图片</li>
    </ul>
</legends>
<?php
$form = ActiveForm::begin([
    'id' => "login-form",
    'layout' => 'horizontal',
    'enableAjaxValidation' => false,
    'options' => ['enctype' => 'multipart/form-data'],
]);
?>
<?= $form->field($model, 'image')->fileInput()->label('产品图片') ; ?>
    <div class="form-actions">
        <?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>
        <a class="btn cancelBtn" href="/goods/productpre/list?id=<?= $_GET['id']?>">返回</a>
    </div>
<?php ActiveForm::end(); ?>