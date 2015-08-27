<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
$this->title = '编辑商品属性信息';
?>
<style>
    #attr_name_id >input{margin-left: 10px;}
</style>
<legends  style="fond-size:12px;">
    <ul class="breadcrumb">
        <li>
            <a href="/">首页</a>
        </li>
        <li><a href="/goods/product">标准库管理</a></li>
        <li><a href="/goods/product/product-attribute?id=<?= $_GET['id'];?>">商品属性</a></li>
        <li class="active">编辑商品属性</li>
    </ul>
    <div class="tab-content">
        <div class="row-fluid" style="height: 30px;line-height: 30px;padding: 8px 15px;margin-bottom: 20px;text-align: center;font-size: 16px;color: #337ab7">
            <a href="/goods/product/edit?id=<?= $_GET['id'];?>">编辑基本信息</a>  >   <span style="color:red;">属性信息</span>  > <a href="/goods/product/list?id=<?= $_GET['id'];?>">编辑图片</a>
        </div>
    </div>
</legends>
<?php
$form = ActiveForm::begin([
    'id' => "form",
    'layout' => 'horizontal',
    'enableAjaxValidation' => false,
    'options' => ['enctype' => 'multipart/form-data'],
]);
?>
<?php if (!empty($list)) {?>

    <?php foreach ($list as $data):?>
        <div class="form-group field-product-origin_price required">
            <label class="control-label col-sm-3" for="productimage-image"><?= $data['name']?>:</label>
            <div class="col-sm-6">
                <select id="SearchForm_type" class="form-control" name="attr[]" style="width: 300px">
                    <?php foreach($data['data'] as $list):?>
                        <option value="<?= $list['id']?>" <?php if($data['attr_id']==$list['id']){ echo "selected";}?> ><?= $list['attr_value']?></option>
                    <?php endforeach;?>
                </select>
                <input type="hidden" name="attr_id[]" value="<?= empty($list['attr_id']) ? 0 : $list['attr_id'];?>">
            </div>
        </div>
    <?php endforeach;?>
    <div class="form-group field-product-origin_price required">
        <label class="control-label col-sm-3" for="productimage-image">建议售价:</label>
        <div class="col-sm-6">
            <input type="text" name="origin_price" id="origin_price" value="<?= $item['origin_price']?>">
        </div>
    </div>
    <div class="form-group field-productimage-image">
        <label class="control-label col-sm-3" for="productimage-image">进货价:</label>
        <div class="col-sm-6">
            <input type="text" name="sale_price" id="sale_price" value="<?= $item['sale_price']?>">
        </div>
    </div>
    <div class="form-group field-product-origin_price required">
        <label class="control-label col-sm-3" for="productimage-image">铺货价:</label>
        <div class="col-sm-6">
            <input type="text" name="shop_price" id="shop_price" value="<?= $item['shop_price']?>">
        </div>
    </div>
    <div class="form-group field-product-origin_price required">
        <label class="control-label col-sm-3" for="productimage-image" >库存:</label>
        <div class="col-sm-6">
            <input type="text" name="total_num"" id="total_num"" value="<?= $item['total_num']?>">
        </div>
    </div>
    <div class="form-group field-product-origin_price required">
        <label class="control-label col-sm-3" for="productimage-image">条形码:</label>
        <div class="col-sm-6">
            <input type="text" name="bar_code" id="bar_code" value="<?= $item['bar_code']?>">
        </div>
    </div>
    <div style="color: red">提示:进货价<=铺货价<=建议售价</div>
<?php }?>
<div class="form-actions">
    <input type="button" value="提交" onclick="toVaild()" class = 'btn btn-primary'>
    <a class="btn cancelBtn" href="javascript:history.go(-1)">返回</a>
</div>
<?php ActiveForm::end(); ?>
<script>
    function toVaild(){
        var type = $("#SearchForm_type").val();
        var origin_price = $("#origin_price").val();
        var sale_price = $("#sale_price").val();
        var shop_price = $("#shop_price").val();
        var total_num = $("#total_num").val();
        var bar_code = $("#bar_code").val();
        if (type==null) {
            alert('属性 不能为空');
            return false;
        }
        if (origin_price=='') {
            alert('建议售价 不能为空');
            return false;
        }
        if (shop_price=='') {
            alert('铺货价 不能为空');
            return false;
        }
        $.get
        (
            "/goods/product/ajax-price?origin_price=" + origin_price + "&sale_price="+sale_price+ "&shop_price="+shop_price,
            function (str_json) {
                var result = JSON.parse(str_json);
                if (result == 0) {
                    alert('建议售价,进货价,铺货价 不符合要求!');
                    return;
                } else if(result == 2) {
                    alert('建议售价或进货价或铺货价 不能为空!');
                    return;
                } else if(result == 3) {
                    alert('建议售价或进货价或铺货价 必须是数字!');
                    return;
                } else {
                    if (total_num=='') {
                        alert('库存 不能为空');
                        return false;
                    }
                    if (bar_code=='') {
                        alert('条形码 不能为空');
                        return false;
                    }
                    $('#form').submit();

                }
            });

    }
</script>