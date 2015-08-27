<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
$this->title = '添加商品图片信息';
?>
    <legends  style="fond-size:12px;">
        <ul class="breadcrumb">
            <li>
                <a href="/">首页</a>
            </li>
            <li><a href="/goods/product">标准库管理</a></li>
            <li class="active">添加标准库商品</li>
        </ul>
        <div class="tab-content">
            <div class="row-fluid" style="height: 30px;line-height: 30px;padding: 8px 15px;margin-bottom: 20px;text-align: center;font-size: 16px;color: #337ab7">
                基本信息  >  属性信息  > <span style="color:red;">图片信息</span>
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
    <input type="hidden" id="act" value="add" name="add">
    <div class="form-group field-product-description">
        <label class="control-label col-sm-3" for="product-description">图集</label>
        <div class="col-sm-6" style="width: 85%">
            <input type="file" name="img1">
            <input type="file" name="img2">
            <input type="file" name="img3">
            <input type="file" name="img4">
            <input type="file" name="img5">
        </div>
    </div>
    <div class="form-actions">
        <?= Html::submitButton('完成', ['class' => 'btn btn-primary','name'=>'submit']) ?>
    </div>
<?php ActiveForm::end(); ?>