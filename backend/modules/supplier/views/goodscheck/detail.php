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
    'title' => '商品名称',
    'sub_title' => '副标题',
    'sn' => '商品编码',
    'supplier_id' => '供应商',
    'category_id' => '分类',
    'brand_id' => '品牌名称',
    'image' => '图片',
    'description' => '商品介绍',
    'supply_price' => '供货价',
    'selling_price' => '建议售价',
    'unit' => '单位',
    'bar_code' => '条形码',
    'net_weight' => '净重',
    'gross_weight' => '毛重',
    'size' => '产品尺寸',
    'cover_size' => '包装尺寸',
    'min_buy_num' => '最小购买量',
    'max_buy_num' => '最大购买量',
    'logistic' => '物流单价',
    'attr_value' => '规格',
    'province_limit' => '销售地区',
);


$this->title = "供应商商品详情";

?>

<link type="text/css" rel="stylesheet" href="/css/zcommon.css?<?php echo \Yii::$app->params['cssVersion']; ?>" />

<script src="/js/jquery-1.10.2.min.js"></script>

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
            <td colspan="2" class="zcss_tac">
                <input type="radio" name="zname_radio_1" id="zid_radio_1" class="zjs_radio" value="1" />
                <label for="zid_radio_1">通过</label>

                <input type="radio" name="zname_radio_1" id="zid_radio_2" class="zjs_radio" value="2" style="margin:0 0 0 100px;" />
                <label for="zid_radio_2">驳回</label>
            </td>
        </tr>
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
            //nothing
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
    if(status=='1'){
        act='pass';
    } else if(status=='2'){
        act='reject';
        reason=$(".zjs_text_reason").val();
        if(reason.length>50){alert("驳回原因过长，限50字以内");return;}
    } else {
        return;
    }

    $.post
    (
        "/supplier/goodscheck/ajax?type="+act,
        {
            "goods_id":goods_id,
            "reason":reason
        },
        function(str)
        {
            console.log(str);return;
            if(str=="1"){
                alert("操作成功");
                //console.log("操作成功");
                window.location.href="/supplier/goodscheck/index";
            } else {
                alert("操作失败");
                //console.log("操作失败");
            }
        }
    );
}

</script>
