<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
$this->title = '添加商品基本信息';
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
        <li class="active">添加标准库商品</li>
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
<?= $form->field($model, 'name')->label('商品名称')->input('text',['style'=>'width:400px']) ; ?>
<?= $form->field($model, 'title')->label('副标题/简介')->input('text',['style'=>'width:400px']) ; ?>
<?= $form->field($model, 'keywords')->label('关键词')->input('text',['style'=>'width:400px']) ; ?>
<input type="hidden" id="act" value="add" name="add">
<?= $form->field($model, 'cate_first_id')->dropDownList(ArrayHelper::map($cate_list,'id','name'),array('id'=>'cate_id'))->label('分类'); ?>
<?= $form->field($model, 'brand_id')->dropDownList(ArrayHelper::map($brand_list,'id','name'),array('id'=>'brand_id'))->label('品牌'); ?>
<div class="form-group field-product-name required">
    <label class="control-label col-sm-3" for="attributevalue-attr_value">属性<br>
        <?= Html::button('添加', ['class' => 'btn', 'id'=>'add_attr_value']) ?></label>
    <table style="max-width: 80%">
        <thead>
        <tr style="width: 80%">
            <th>属性值(空格分隔)</th>
            <th>建议售价</th>
            <th>进货价</th>
            <th>铺货价</th>
            <th>库存</th>
            <th>条形码</th>
            <th>主图</th>
        </tr>
        </thead>
        <tbody id="attr_value">
        <tr style="width: 80%; margin-top: 5px">
            <td><input type="text" style="width:100px;" name="Products[attr_value][]"></td>
            <td><input type="text" style="width:80px;" value="0.00"  name="Products[origin_price][]"></td>
            <td><input type="text" style="width:80px;" value="0.00" name="Products[sale_price][]"></td>
            <td><input type="text" style="width:80px;" value="0.00" name="Products[shop_price][]"></td>
            <td><input type="text" style="width:80px;" value="10" name="Products[total][]"></td>
            <td><input type="text" style="width:100px;"  name="Products[bar_code][]"></td>
            <td><input id="product-image" type="file" name="image[]"></td>
        </tr>
        </tbody>
    </table>
    <div class="help-block help-block-error " style="color: #a94442"> <?= \Yii::$app->getSession()->getFlash('attr_value'); ?></div>
    <div><span style="color: red">提示:进货价<=铺货价<=建议售价</span></div>
</div>
<div class="form-group field-product-brand_id required">
    <label class="control-label col-sm-3" for="product-description">详情</label>
    <div class="col-sm-6" style="width: 85%">
        <script id="editor" name="Product[description]" type="text/plain" style="width:100%;height:300px;">
            <?= !empty($model->description) ? $model->description : '' ?>
        </script>
        <input type="hidden" id="base_url" value="<?php echo Yii::$app->params['baseUrl']; ?>">
        <input type="hidden" id="act" value="add">
        <div class="help-block help-block-error " style="color: #a94442"> <?= \Yii::$app->getSession()->getFlash('error'); ?></div>
    </div>
</div>
<?php if ($bc_id==$branch_id) :?>
    <?= $form->field($model, 'bc_id')->dropDownList(ArrayHelper::map($city_list,'id','name'))->label('限定区域'); ?>
<?php endif; ?>
<?= $form->field($model, 'is_hot')->radioList(['1'=>'是','0'=>'否'])->label('是否推荐'); ?>
<?= $form->field($model, 'is_self')->radioList(['1'=>'是','2'=>'否'])->label('是否可以自营'); ?>
<?= $form->field($model, 'fixed_price')->radioList(['1'=>'是','0'=>'否'])->label('是否固定价'); ?>
<div class="form-actions">
    <?= Html::submitButton('添加', ['class' => 'btn btn-primary']) ?>
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
    $("#add_attr_value").click(function(){
        //gf.confirm('','gf.abc()');
        var html = $('#attr_value tr:first-child').html();
        $('#attr_value').append('<tr>'+html+'</tr>');
    })
</script>