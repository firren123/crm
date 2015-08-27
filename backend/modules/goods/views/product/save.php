<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = '添加标准库商品图片';
?>
<legends  style="fond-size:12px;">
    <ul class="breadcrumb">
        <li>
            <a href="/">首页</a>
        </li>
        <li><a href="/goods/product">标准库管理</a></li>
        <li><a href="/goods/product/list?id=<?= $_GET['id']?>">标准库图片管理</a></li>
        <li class="active">添加标准库商品图片</li>
    </ul>
    <div class="tab-content">
        <div class="row-fluid" style="height: 30px;line-height: 30px;padding: 8px 15px;margin-bottom: 20px;text-align: center;font-size: 16px;color: #337ab7">
            <a href="/goods/product/edit?id=<?= $_GET['id'];?>">编辑基本信息</a>  >   <a href="/goods/product/product-attribute?id=<?= $_GET['id'];?>">属性信息</a>  > <span style="color:red;">编辑图片</span>
        </div>
    </div>
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
        <a class="btn cancelBtn" href="/goods/product/list?id=<?= $_GET['id']?>">返回</a>
    </div>
<?php ActiveForm::end(); ?>