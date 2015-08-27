<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
$this->title = '编辑商品详细信息';
?>
<?= $this->registerJsFile("@web/js/jquery-1.10.2.min.js");?>
<?= $this->registerJsFile("@web/js/news.js");?>
<?= $this->registerJsFile("@web/plug/ueditor/ueditor.config.js");?>
<?= $this->registerJsFile("@web/plug/ueditor/ueditor.all.min.js");?>
<legends  style="fond-size:12px;">
    <ul class="breadcrumb">
        <li>
            <a href="/">首页</a>
        </li>
        <li><a href="/goods/product">标准库管理</a></li>
        <li class="active">编辑商品详细信息</li>
    </ul>
    <div class="tab-content">
        <div class="row-fluid" style="height: 30px;line-height: 30px;padding: 8px 15px;margin-bottom: 20px;text-align: center;font-size: 16px;color: #337ab7">
            <a href="/goods/product/edit?id=<?= $_GET['id'];?>">编辑基本信息</a>  >  <span style="color:red;">编辑详细信息</span>  > <a href="/goods/product/list?id=<?= $_GET['id'];?>">编辑图片</a> >  属性信息
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
<div class="form-group field-product-description">
    <label class="control-label col-sm-3" for="product-description">详情</label>
    <div class="col-sm-6" style="width: 85%">
        <script id="editor" name="Product[description]" type="text/plain" style="width:100%;height:300px;">
            <?= !empty($item['description']) ? $item['description'] : '' ?>
        </script>
        <input type="hidden" id="base_url" value="<?php echo Yii::$app->params['baseUrl']; ?>">
        <input type="hidden" id="act" value="add">
        <div class="help-block help-block-error " style="color: #a94442"> <?= \Yii::$app->getSession()->getFlash('description'); ?></div>
    </div>
</div>
<div class="form-actions">
    <?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>
    <a class="btn cancelBtn" href="/goods/product">返回</a>
</div>
<?php ActiveForm::end(); ?>
