<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
$this->title = '编辑待发布商品';
?>
<style type="text/css">
    .item{
        padding:3px 5px;
        cursor:pointer;
        height: 30px;
        line-height: 30px;
    }
    .addbg{
        background:#ccc;
    }
    #append{
        border:1px solid #ccc;
        border-top:0;
        display:none;
        width: 400px;
        overflow: hidden;
        position: absolute;
        z-index: 2;
        background: #ffffff;
    }
</style>
<?= $this->registerJsFile("@web/js/jquery-1.10.2.min.js");?>
<?= $this->registerJsFile("@web/js/news.js");?>
<?= $this->registerJsFile("@web/plug/ueditor/ueditor.config.js");?>
<?= $this->registerJsFile("@web/plug/ueditor/ueditor.all.min.js");?>
<?= $this->registerJsFile("@web/js/goods/product.js");?>
<legends  style="fond-size:12px;">
    <ul class="breadcrumb">
        <li>
            <a href="/">首页</a>
        </li>
        <li><a href="/goods/productpre">待发布商品管理</a></li>
        <li class="active">编辑待发布商品</li>
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
<?= $form->field($model, 'cate_first_id')->dropDownList(ArrayHelper::map($cate_list,'id','name'),array('id'=>'cate_id'))->label('顶级分类'); ?>
<?= $form->field($model, 'cate_second_id')->dropDownList(ArrayHelper::map($cate_second_list,'id','name'),array('id'=>'cate_second_id'))->label('二级分类'); ?>

<div class="form-group field-product-brand_id required">
    <label class="control-label col-sm-3" for="product-description">品牌</label>
    <div class="col-sm-6" style="width: 85%">
        <input id="kw" onKeyup="getContent(this);" class="form-control" style="width:400px" name="Product[brand_name]" value="<?= !empty($item['brand_name']) ? $item['brand_name'] : '' ?>"/>
        <div id="append"></div>
        <div class="help-block help-block-error " style="color: #a94442"> <?= \Yii::$app->getSession()->getFlash('brand_name'); ?></div>
    </div>
</div>
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
            <td><input type="text" style="width:100px;" name="Products[attr_value][]" value="<?= !empty($item['attr_value']) ? $item['attr_value'] : '' ?>"></td>
            <td><input type="text" style="width:80px;"   name="Products[origin_price][]" value="<?= !empty($item['origin_price']) ? $item['origin_price'] : '0.00'?>"></td>
            <td><input type="text" style="width:80px;"  name="Products[sale_price][]" value="<?= !empty($item['sale_price']) ? $item['sale_price'] : '0.00' ?>"></td>
            <td><input type="text" style="width:80px;"  name="Products[shop_price][]" value="<?= !empty($item['shop_price']) ? $item['shop_price'] : '0.00' ?>"></td>
            <td><input type="text" style="width:80px;"  name="Products[total][]" value="<?= !empty($item['total']) ? $item['total'] : '0' ?>"></td>
            <td><input type="text" style="width:100px;"  name="Products[bar_code][]" value="<?= !empty($item['bar_code']) ? $item['bar_code'] : '' ?>"></td>
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
        <a class="btn cancelBtn" href="/goods/productpre">返回</a>
    </div>
<?php ActiveForm::end(); ?>
<script>
    var data = <?php echo $brand_list?>;
    $(document).ready(function(){
        $("#cate_id").change(function()
        {
            var cate_id=$(this).val();
            $.get
            (
                "/goods/brand/list?cate_id="+cate_id,
                function(str_json)
                {
                    data=JSON.parse(str_json);
                }
            );
        });
    });
    $("#add_attr_value").click(function(){
        //gf.confirm('','gf.abc()');
        var html = $('#attr_value tr:first-child').html();
        $('#attr_value').append('<tr>'+html+'</tr>');
    })
    $(document).ready(function(){
        $(document).keydown(function(e){
            e = e || window.event;
            var keycode = e.which ? e.which : e.keyCode;
            if(keycode == 38){
                if(jQuery.trim($("#append").html())==""){
                    return;
                }
                movePrev();
            }else if(keycode == 40){
                if(jQuery.trim($("#append").html())==""){
                    return;
                }
                $("#kw").blur();
                if($(".item").hasClass("addbg")){
                    moveNext();
                }else{
                    $(".item").removeClass('addbg').eq(0).addClass('addbg');
                }

            }else if(keycode == 13){
                dojob();
            }
        });

        var movePrev = function(){
            $("#kw").blur();
            var index = $(".addbg").prevAll().length;
            if(index == 0){
                $(".item").removeClass('addbg').eq($(".item").length-1).addClass('addbg');
            }else{
                $(".item").removeClass('addbg').eq(index-1).addClass('addbg');
            }
        }

        var moveNext = function(){
            var index = $(".addbg").prevAll().length;
            if(index == $(".item").length-1){
                $(".item").removeClass('addbg').eq(0).addClass('addbg');
            }else{
                $(".item").removeClass('addbg').eq(index+1).addClass('addbg');
            }

        }

        var dojob = function(){
            $("#kw").blur();
            var value = $(".addbg").text();
            $("#kw").val(value);
            $("#append").hide().html("");
        }
    });
    function getContent(obj){
        var kw = jQuery.trim($(obj).val());
        if(kw == ""){
            $("#append").hide().html("");
            return false;
        }
        var html = "";
        for (var i = 0; i < data.length; i++) {
            if (data[i].indexOf(kw) >= 0) {
                html = html + "<div class='item' onmouseenter='getFocus(this)' onClick='getCon(this);'>" + data[i] + "</div>"
            }
        }
        if(html != ""){
            $("#append").show().html(html);
        }else{
            $("#append").hide().html("");
        }
    }
    function getFocus(obj){
        $(".item").removeClass("addbg");
        $(obj).addClass("addbg");
    }
    function getCon(obj){
        var value = $(obj).text();
        $("#kw").val(value);
        $("#append").hide().html("");
    }
</script>