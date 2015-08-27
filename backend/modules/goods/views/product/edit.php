<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
$this->title = '编辑商品基本信息';
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
            <li class="active">编辑商品基本信息</li>
        </ul>
        <div class="tab-content">
            <div class="row-fluid" style="height: 30px;line-height: 30px;padding: 8px 15px;margin-bottom: 20px;text-align: center;font-size: 16px;color: #337ab7">
                <span style="color:red;">编辑基本信息</span>  >   <a href="/goods/product/product-attribute?id=<?= $_GET['id'];?>">属性信息</a>  > <a href="/goods/product/list?id=<?= $_GET['id'];?>">编辑图片</a>
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
    <?= $form->field($model, 'name')->label('商品名称')->input('text',['style'=>'width:400px']) ; ?>
    <?= $form->field($model, 'title')->label('副标题/简介')->input('text',['style'=>'width:400px']) ; ?>
    <?= $form->field($model, 'keywords')->label('关键词')->input('text',['style'=>'width:400px']) ; ?>
<?= $form->field($model, 'cate_first_id')->dropDownList(ArrayHelper::map($cate_list,'id','name'),array('id'=>'cate_id'))->label('分类'); ?>
<?= $form->field($model, 'brand_id')->dropDownList(ArrayHelper::map($brand_list,'id','name'),array('id'=>'brand_id'),array('style'=>'width:250px'))->label('品牌'); ?>
<div class="form-group field-product-brand_id required">
    <label class="control-label col-sm-3" for="product-description">详情</label>
    <div class="col-sm-6" style="width: 85%">
        <script id="editor" name="Product[description]" type="text/plain" style="width:100%;height:300px;">
            <?= !empty($model->description) ? $model->description : '' ?>
        </script>
        <input type="hidden" id="base_url" value="<?php echo Yii::$app->params['baseUrl']; ?>">
        <input type="hidden" id="act" value="add">
        <div class="help-block help-block-error " style="color: #a94442"> <?= \Yii::$app->getSession()->getFlash('description'); ?></div>
    </div>
</div>
<?php if ($bc_id==$branch_id) :?>
<?= $form->field($model, 'bc_id')->dropDownList(ArrayHelper::map($city_list,'id','name'))->label('限定区域'); ?>
<?php endif; ?>
    <?= $form->field($model, 'is_hot')->radioList(['1'=>'是','0'=>'否'])->label('是否推荐'); ?>
    <?= $form->field($model, 'is_self')->radioList(['1'=>'是','2'=>'否'])->label('是否可以自营'); ?>
    <?= $form->field($model, 'fixed_price')->radioList(['1'=>'是','0'=>'否'])->label('是否固定价'); ?>
    <div class="form-actions">
        <?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>
        <a class="btn cancelBtn" href="/goods/product">返回</a>
    </div>
<?php ActiveForm::end(); ?>
<script>
    $(document).ready(function(){
        $("#cate_id").change(function()
        {
            var cate_id=$(this).val();
            $.get
            (
                "/goods/brand/list?cate_id="+cate_id,
                function(str_json)
                {
                    obj=JSON.parse(str_json);

                    var html_option="<option value=''>选择品牌</option>";
                    var len=obj.length;
                    for(var i=0;i<len;i++)
                    {
                        html_option+='<option value="'+obj[i]['id']+'">'+obj[i]['name']+'</option>';
                    }
                    $("#brand_id").html('全部品牌');
                    $("#brand_id").append(html_option);
                }
            );
        });
    });
</script>