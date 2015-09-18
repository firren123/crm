<?php
/**
 * 详情-view
 *
 * PHP Version 5
 *
 * @category  ADMIN
 * @package   CONTROLLER
 * @author    zhengyu <zhengyu@iyangpin.com>
 * @time      2015-09-15
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      zhengyu@iyangpin.com
 */



$map_field_name = array(
    'id' => 'id',
    'bar_code' => '条形码',
    'title' => '商品名称',
    'sub_title' => '副标题',
    'sn' => '商品编码',
    'supplier_id' => '供应商',
    'category_id' => '分类',
    'brand_name' => '品牌名称',
    'image' => '图片',
    'description' => '商品介绍',

    'unit' => '单位',
    'net_weight' => '净重',
    'gross_weight' => '毛重',
    'size' => '产品尺寸',
    'cover_size' => '包装尺寸',
    'min_buy_num' => '最小购买量',
    'max_buy_num' => '最大购买量',
    'logistic' => '物流单价',
    'attr_value' => '规格',
    'province_limit' => '销售地区',

    'supply_price' => '供货价',
    'selling_price' => '建议售价',
);


$this->title = "详情-供应商商品审核";

?>

<link type="text/css" rel="stylesheet" href="/css/zcommon.css?<?php echo \Yii::$app->params['cssVersion']; ?>" />

<script src="/js/jquery-1.10.2.min.js"></script>
<script src="/js/zcommon.js"></script>

<style type="text/css">
.zcss_table1 th{font-weight:bold;}
.zcss_table1 th,.zcss_table1 td{border:1px solid #000000;padding:5px;}

.zcss_td_left{width:120px;}
.zcss_td_right{width:650px;}

.zcss_class0401{margin:30px 0;}
.zcss_class0401 a{padding:0 20px;}
</style>



<div>

    <ul class="breadcrumb">
        <li><a href="/">首页</a></li>
        <li class="active"><a href="/supplier/supplier" style="color:#286090;">供应商管理</a></li>
        <li class="active">待审核商品-详情</li>
    </ul>

    <table class="zcss_table1"><tbody>

        <tr>
            <td colspan="2">
                <span class="zcss_fc_red zcss_fs_16">
                <?php if($existed_product_id==0){ ?>
                    此商品的条形码在标准库中 <b>不存在</b>
                <?php }else{ ?>
                    此商品的条形码在标准库中 <b>已存在</b>， <a href="/goods/product/details?id=<?php echo $existed_product_id; ?>" target="_blank">标准库中此条形码的商品</a>
                <?php } ?>
                </span>
            </td>
        </tr>

        <?php foreach($map_field_name as $field=>$name){ ?>
            <tr>
                <td class="zcss_td_left"><?php echo $name; ?></td>

                <td class="zcss_td_right">

                <?php if($field=='id'){ ?>
                    <?php if(isset($arr_goods_info['id'])){echo $arr_goods_info['id'];} ?>
                <?php }elseif($field=='province_limit'){ ?>
                    <?php foreach($arr_province_limit as $value){ ?>
                        <?php echo $value['name'].","; ?>
                    <?php } ?>
                <?php }elseif($field=='supplier_id'){ ?>
                    <?php if(isset($arr_sp_info['company_name'])){echo $arr_sp_info['company_name'];} ?>
                <?php }elseif($field=='category_id'){ ?>
                    <?php if(isset($arr_cate_info['name'])){echo $arr_cate_info['name'];} ?>
                <?php }elseif($field=='image'){ ?>
                    <img src="<?php echo \Yii::$app->params['imgHost'].$arr_goods_info['image']; ?>" style="max-height:100px;max-width:100px;" />
                <?php }elseif($field=='xxxx'){ ?>
                <?php }else{ ?>
                    <?php if(isset($arr_goods_info[$field])){echo $arr_goods_info[$field];} ?>
                <?php } ?>

                </td>
            </tr>
        <?php } ?>


        <tr>
            <td colspan="2">
                <span class="zcss_fc_red zcss_fs_16">
                <?php if($existed_product_id==0){ ?>
                    此商品的条形码在标准库中 <b>不存在</b>
                <?php }else{ ?>
                    此商品的条形码在标准库中 <b>已存在</b>， <a href="/goods/product/details?id=<?php echo $existed_product_id; ?>" target="_blank">标准库中此条形码的商品</a>
                <?php } ?>
                </span>
            </td>
        </tr>


        <tr>
            <td colspan="2" class="zcss_tac">
                <input type="radio" name="zname_radio_1" id="zid_radio_1" class="zjs_radio" value="1" />
                <label for="zid_radio_1">通过</label>

                <input type="radio" name="zname_radio_1" id="zid_radio_2" class="zjs_radio" value="2" style="margin:0 0 0 100px;" />
                <label for="zid_radio_2">驳回</label>
            </td>
        </tr>
        <tr class="zjs_tr_hide zjs_tr_price" style="display:none;">
            <td rowspan="2">设置价格</td>
            <td>
                <span>进货价：</span>
                <input type="text" class="zjs_text_jhj" />
            </td>
        </tr>
        <tr class="zjs_tr_hide zjs_tr_price" style="display:none;">
            <td>
                <span>铺货价：</span>
                <input type="text" class="zjs_text_phj" />
            </td>
        </tr>
        <?php if($existed_product_id>0){ ?>
        <tr class="zjs_tr_hide zjs_tr_cover" style="display:none;">
            <td>
                <span>是否覆盖：</span>
            </td>
            <td>
                <span>是否覆盖标准库中与此条形码相同的商品“进货价”、“铺货价”之外的属性：</span>
                <br />
                <input type="radio" value="1" name="zid_radio_cover" id="zid_radio_cover1" class="zjs_radio_cover" /><label for="zid_radio_cover1" style="margin:0 20px 0 0;">覆盖</label>
                <input type="radio" value="0" name="zid_radio_cover" id="zid_radio_cover2" class="zjs_radio_cover" /><label for="zid_radio_cover2">不覆盖</label>
            </td>
        </tr>
        <?php } ?>
        <tr class="zjs_tr_hide zjs_tr_reason" style="display:none;">
            <td>审核驳回原因</td>
            <td><input type="text" class="zjs_text_reason" style="width:99%;" /></td>
        </tr>
        <tr>
            <td colspan="2" class="zcss_tac">
                <input type="button" class="zjs_btn_submit" value="提交" disabled="disabled" style="width:100px;height:50px;" />
            </td>
        </tr>


    </tbody></table>
</div>

<div class="zcss_class0401">
    <a href="/supplier/goodscheck/index">返回列表</a>
</div>


<span class="zjs_id" style="display:none;"><?php echo $arr_goods_info['id']; ?></span>



<script type="text/javascript">
$(function()
{
    $(".zjs_radio").change(function()
    {
        var value=$(this).val();
        $(".zjs_tr_hide").hide();
        if (value == 1) {
            $(".zjs_tr_price").show();
            $(".zjs_tr_cover").show();
        } else if (value == 2) {
            $(".zjs_tr_reason").show();
        } else {
        }
        $(".zjs_btn_submit").removeAttr("disabled");
    });


    $(".zjs_btn_submit").click(function()
    {
        submit();
    });
});



function submit()
{
    var status=$(".zjs_radio:checked").val();
    var goods_id=$(".zjs_id").html();

    var act='';
    var reason='';
    var jhj=0;
    var phj=0;
    var is_cover=0;
    if(status=='1'){
        act='pass';
        jhj=$(".zjs_text_jhj").val();
        phj=$(".zjs_text_phj").val();
        if(z_check_money(jhj)==0){alert("进货价格式错误");return;}
        if(z_check_money(phj)==0){alert("铺货价格式错误");return;}
        var len_tr_cover=$(".zjs_tr_cover").length;
        if(len_tr_cover==0){
            //此条形码不存在
        }else{
            //此条形码存在
            var radio_cover=$(".zjs_radio_cover:checked");
            if(radio_cover.length==0){
                alert("请选择是否覆盖");return;
            }else{
                is_cover=$(radio_cover).val();
            }
        }
    } else if(status=='2'){
        act='reject';
        reason=$(".zjs_text_reason").val();
        if(reason.length==0){alert("请填写驳回原因");return;}
        if(reason.length>50){alert("驳回原因过长，限50字以内");return;}
    } else {
        return;
    }

    disable_btn();
    $.ajax({
        "type":"post",
        "url": "/supplier/goodscheck/ajax?type="+act,
        "dataType":"json",
        "data":{
            "goods_id":goods_id,
            "reason":reason,
            "is_cover":is_cover,
            "jhj":jhj,
            "phj":phj
        },
        "success":function(obj){
            z_log("ajax goodscheck success");
            if(obj.result=='1'){
                window.location.href="/supplier/goodscheck/index";
            }else{
                alert(obj.msg);
            }
        },
        "error":function(){
            z_log("ajax goodscheck error");
        },
        "complete":function(){
            z_log("ajax goodscheck complete");
            enable_btn();
        }
    });



}

</script>
