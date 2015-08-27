<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
$this->title = '添加商品属性信息';
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
            <li class="active">添加标准库商品</li>
        </ul>
        <div class="tab-content">
            <div class="row-fluid" style="height: 30px;line-height: 30px;padding: 8px 15px;margin-bottom: 20px;text-align: center;font-size: 16px;color: #337ab7">
                基本信息  >  <span style="color:red;">属性信息</span>  > 图片信息
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
    <input type="hidden" id="act" value="add" name="add">
    <div class="form-group field-product-name required">
        <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" id="pro_id"/>
        <label class="control-label col-sm-3" for="product-description">商品属性</label>
        <div class="col-sm-6" style="width: 85%">
            <?php if(empty($item)){ ?>
                <input type="checkbox" value="0" checked name="attr_name_id[]" id="attribute">无
            <?php }else{ ?>
                <div style="height: 30px;line-height: 30px;">

            <div id="attr_name_id" style="float: left;">
                <?php foreach($item as $list):?>
                    <input type="checkbox"  name="attr_name_id[]" onclick="return getAttrValue();"  value="<?= $list['id']?>"> <?= $list['admin_name']?>
                <?php endforeach;?>
                </div>
                    </div>
            <?php }?>
            <div class="sku_attr_value" id="sku_attr_value">
            </div>
            <!--价格列表Start-->
            <div class="sku_map" id="sku_map">
            </div>
            <!--价格列表end-->
        </div>
    </div>
    <div class="form-actions">
        <input type="button" value="下一步" onclick="toVaild()" class = 'btn btn-primary'>
    </div>
<?php ActiveForm::end(); ?>
<script>
    function getAttrValue(){
        var pid=$("#pro_id").val();
        var attr_name_ids='';
        $("input[name='attr_name_id[]']:checked").each(function(){
            attr_name_ids += $(this).val()+",";
        });
        $('#sku_attr_value').empty();
        if (attr_name_ids=='0,') {
            $("#attr_name_id").hide();
            return;
        }
        if (attr_name_ids=='') {
            $("#attr_name_id").show();
            return;
        }
        $.ajax({
            url:"/goods/product/attr-values/",
            type:"GET",
            data:"attr_name_ids="+attr_name_ids+"&pid="+pid,
            dataType:"text",
            success:function(data){
                $('#sku_attr_value').append(data);
                if(pid > 0) {
                    //获取价格
                    addPriceList();
                }
            }
        });
        return true;
    }
    //组合价格列表 model_id,name_id,id
    function addPriceList(){
        var pid=$("#pro_id").val();
        //判断是否都选中
        var attr_name_count=$("input[name='attr_name_id[]']:checked").length;
        var selected_count=0;
        var attr_name_ids='';
        $("input[name='attr_name_id[]']:checked").each(function(){
            attr_name_ids += $(this).val()+",";
            var idcount = $("#sku_group_"+$(this).val()+" input[name='attr_value_id[]']:checked").length;
            if(idcount>0) {
                selected_count+=1;
            }
        });

        if(selected_count == attr_name_count) {
            var attr_value_ids='';
            $("input[name='attr_value_id[]']:checked").each(function(){
                attr_value_ids += $(this).val()+",";
            });
            var current_price=$('#current_price').val();
            $.getJSON("/goods/product/price-table?attr_value_ids="+attr_value_ids+"&attr_name_ids="+attr_name_ids+"&current_price="+current_price+"&pid="+pid, function(data) {
                $('#sku_map').empty(); //清空
                $("#sku_map").append(data.result);
                $('#total_num').attr("disabled","disabled"); //禁止库存框
                //$('#total_num').val('');
            });
        }
        else {
            $('#sku_map').empty(); //清空
            $('#total_num').removeAttr("disabled"); //取消禁止库存框
            //$('#total_num').val('');
        }
        return true;
    }

    function toVaild(){
        var number = 0;
        $("input[name='attr_name_id[]']").each(function(){
            var value = $(this).val();
            if (value==0) {
                $('#form').submit();
            }
        });
        var falg = 0;
        $("input[name='attr_name_id[]']:checkbox").each(function () {
            if ($(this).prop("checked")==true) {
                falg += 1;
            }
        })
        if (falg==0) {
            alert('请选择属性!');
        } else {
            var origin_price=$("input[name='origin_price[]']").map(function(){return this.value}).get().join();
            var sale_price=$("input[name='sale_price[]']").map(function(){return this.value}).get().join();
            var shop_price=$("input[name='shop_price[]']").map(function(){return this.value}).get().join();
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
                        $("input[name='bar_code[]']").each(function () {
                            var value = $(this).val();
                            var length = value.length;
                            if (value == '') {
                                number = 2;
                            } else {
                                if (isNaN(value)) {
                                    number = 3;
                                } else {
                                    if (length != 13) {
                                        number = 4;
                                    } else {
                                        number = 1;

                                    }
                                }
                            }
                        });
                        if (number == 1) {
                            var ids = $("input[name='bar_code[]']").valueOf();
                            var ids = $('input[id="bar_code"]').map(function () {
                                return this.value
                            }).get().join();
                            $.get
                            (
                                "/goods/product/bar-code?code=" + ids,
                                function (str_json) {
                                    var result = JSON.parse(str_json);
                                    if (result == 0) {
                                        alert('条形码 已经存在!');
                                    }
                                    if (result == 2) {
                                        alert('条形码 不能重复!');
                                    }
                                    if (result == 1) {
                                        $('#form').submit();
                                    }
                                }
                            );
                        }
                        if (number == 2) {
                            alert("条形码 不能为空");
                        }
                        if (number == 3) {
                            alert("条形码 必须是数字");
                        }
                        if (number == 4) {
                            alert("条形码 必须是13位");
                        }
                        if (number == 5) {
                            alert('条形码 已经存在!');
                        }
                    }
                }
            );

        }

    }
</script>