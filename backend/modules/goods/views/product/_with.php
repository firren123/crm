<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
$this->title = '添加商品详细信息';
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
                基本信息 >  <span style="color:red;">详细信息</span>   > 图片信息 >  属性信息
            </div>
        </div>
    </legends>
<?= $this->registerJsFile("@web/js/jquery-1.10.2.min.js");?>
<?= $this->registerJsFile("@web/js/news.js");?>
<?= $this->registerJsFile("@web/plug/ueditor/ueditor.config.js");?>
<?= $this->registerJsFile("@web/plug/ueditor/ueditor.all.min.js");?>
<?php
$form = ActiveForm::begin([
    'id' => "login-form",
    'layout' => 'horizontal',
    'enableAjaxValidation' => false,
    'options' => ['enctype' => 'multipart/form-data'],
]);
?>
<div class="form-group field-product-description">
    <label class="control-label col-sm-3" for="product-description">详情</label>
    <div class="col-sm-6" style="width: 85%">
        <script id="editor" name="Product[description]" type="text/plain" style="width:100%;height:300px;"></script>
        <input type="hidden" id="base_url" value="<?php echo Yii::$app->params['baseUrl']; ?>">
        <input type="hidden" id="act" value="add">
        <div class="help-block help-block-error " style="color: #a94442"> <?= \Yii::$app->getSession()->getFlash('error'); ?></div>
    </div>


</div>
    <div class="form-actions">
        <?= Html::submitButton('下一步', ['class' => 'btn btn-primary']) ?>
    </div>
<?php ActiveForm::end(); ?>