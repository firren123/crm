<?php
/**
 * 列表-view
 *
 * PHP Version 5
 *
 * @category  ADMIN
 * @package   CONTROLLER
 * @author    zhengyu <zhengyu@iyangpin.com>
 * @time      15/4/21 09:56
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      zhengyu@iyangpin.com
 */


$map_field_name = array(
    'id' => 'id',
    'shop_name' => '店铺名称',
    'province' => '省',
    'city' => '市',
    'district' => '区/县',
    'status' => '状态',
);


use yii\widgets\LinkPager;
$this->title = "商家列表";

//echo 'index-view';exit;

?>

<link type="text/css" rel="stylesheet" href="/css/zcommon.css?<?php echo \Yii::$app->params['cssVersion']; ?>" />

<script src="/js/jquery-1.10.2.min.js"></script>
<script src="/js/json2.js"></script>
<script src="/js/layer/layer.js"></script>
<script src="/js/zcommon.js?<?php echo \Yii::$app->params['jsVersion']; ?>"></script>

<style type="text/css">
.zcss_td_op a{padding:0 10px;}

.zcss_span1{padding:0 0 0 10px;}
</style>



<div>
    <ul class="breadcrumb">
        <li><a href="/">首页</a></li>
        <li class="active"><a href="/shop/shop" style="color:#286090;">商家管理</a></li>
        <li class="active">列表</li>
    </ul>
</div>


<div class="zcss_query_aera">

    <select class="zjs_select_province_open">
        <option value="0">全部省</option>
        <?php foreach($arr_select_province as $a_provice){ ?>
            <option value="<?php echo $a_provice['id']; ?>"<?php if($arr_select_param['province']==$a_provice['id']){echo " selected=\"selected\" ";} ?>><?php echo $a_provice['name']; ?></option>
        <?php } ?>
    </select>

    <select class="zjs_select_city">
        <option value="0" class="zjs_default_v">全部市</option>
        <?php foreach($arr_cur_select_city_list as $a_city){ ?>
            <option value="<?php echo $a_city['id']; ?>"<?php if($arr_select_param['city']==$a_city['id']){echo " selected=\"selected\" ";} ?>><?php echo $a_city['name']; ?></option>
        <?php } ?>
    </select>

    <select class="zjs_select_district">
        <option value="0" class="zjs_default_v">全部区/县</option>
        <?php foreach($arr_cur_select_district_list as $a_district){ ?>
            <option value="<?php echo $a_district['id']; ?>"<?php if($arr_select_param['district']==$a_district['id']){echo " selected=\"selected\" ";} ?>><?php echo $a_district['name']; ?></option>
        <?php } ?>
    </select>

    <span class="zcss_span1">id:</span>
    <input type="text" class="zjs_query_id" style="width:50px;" value="<?php if($arr_select_param['id']!=''){echo $arr_select_param['id'];} ?>" />

    <span class="zcss_span1">店铺名称:</span>
    <input type="text" class="zjs_query_shop_name" style="width:80px;" value="<?php if($arr_select_param['like_shop_name']!=''){echo $arr_select_param['like_shop_name'];} ?>" />

    <span class="zcss_span1">联系人:</span>
    <input type="text" class="zjs_query_contact_name" style="width:80px;" value="<?php if($arr_select_param['like_contact_name']!=''){echo $arr_select_param['like_contact_name'];} ?>" />

    <span class="zcss_span1">业务员:</span>
    <input type="text" class="zjs_query_business_name" style="width:80px;" value="<?php if($arr_select_param['business_name']!=''){echo $arr_select_param['business_name'];} ?>" />

    <input type="button" value="查询" class="zjs_btn_query" style="width:80px;margin:0 0 0 30px;" />
</div>

<div style="width:100%;overflow-x:auto;">
    <table class="zcss_table_list"><tbody>
        <tr>
            <th style="width:70px;"><?php echo $map_field_name['id']; ?></th>
            <th><?php echo $map_field_name['shop_name']; ?></th>
            <th style="width:90px;"><?php echo $map_field_name['province']; ?></th>
            <th style="width:90px;"><?php echo $map_field_name['city']; ?></th>
            <th style="width:90px;"><?php echo $map_field_name['district']; ?></th>
            <th style="width:50px;"><?php echo $map_field_name['status']; ?></th>
            <th style="width:70px;">联系人</th>
            <th style="width:70px;">业务员</th>
            <th style="width:210px;">操作</th>
        </tr>
        <?php foreach($arr_shop_list as $a_row){ ?>
            <tr>
                <td class="zjs_id zcss_tac"><?php if(isset($a_row['id'])){echo $a_row['id'];} ?></td>
                <td><?php if(isset($a_row['shop_name'])){echo $a_row['shop_name'];} ?></td>
                <td class="zcss_tac"><?php if(isset($map_related_province[$a_row['province']])){echo $map_related_province[$a_row['province']];} ?></td>
                <td class="zcss_tac"><?php if(isset($map_related_city[$a_row['city']])){echo $map_related_city[$a_row['city']];} ?></td>
                <td class="zcss_tac"><?php if(isset($map_related_district[$a_row['district']])){echo $map_related_district[$a_row['district']];} ?></td>
                <td class="zcss_tac"><?php if(isset($map_status[$a_row['status']])){echo $map_status[$a_row['status']];} ?></td>

                <td class="zcss_tac"><?php echo $a_row['contact_name']; ?></td>
                <td class="zcss_tac"><?php if(isset($a_row['business_id']) && isset($map_related_business[$a_row['business_id']])){echo $map_related_business[$a_row['business_id']];} ?></td>

                <td class="zcss_td_op zcss_tac">
                    <a href="/shop/shop/detail?id=<?php if(isset($a_row['id'])){echo $a_row['id'];} ?>" target="_blank">详情</a>
                    <a href="/shop/shop/edit?id=<?php if(isset($a_row['id'])){echo $a_row['id'];} ?>" target="_blank">修改</a>
                    <a href="/shop/shopcommunity/relation?id=<?php if(isset($a_row['id'])){echo $a_row['id'];} ?>" target="_blank">服务小区</a>
                </td>
            </tr>
        <?php } ?>
        <?php if($record_count==0){ ?>
            <tr>
                <td colspan="9" class="zcss_tac">无数据</td>
            </tr>
        <?php } ?>
        </tbody></table>

    <?php echo LinkPager::widget(['pagination' => $pages]); ?>

</div>


<a href="/shop/shop/add" target="_blank" style="font-size:20px;font-weight:bold;">添加新商家</a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;


<span class="zjs_csrf" style="display:none;"><?php echo \Yii::$app->getRequest()->getCsrfToken(); ?></span>



<select class="zjs_select_business" style="display:none;position:absolute;">
    <?php
    if(!empty($arr_select_business)){
        echo '<option value="'.$arr_select_business['id'].'">'.$arr_select_business['name'].'</option>';
    }
    ?>
</select>



<script type="text/javascript">
$(function()
{
    $(".zjs_btn_query").click(function()
    {
        get_list();
    });

    $(".zjs_query_business_name").focus(function()
    {
        layer.tips(
            "可输入姓名或id",
            ".zjs_query_business_name",
            {
                tips: [1, '#0088cc'],
                time: 5000
            }
        );
    });


    $(".zjs_query_business_name").keyup(function()
    {
        get_business();
    });

    $(document).on("change",".zjs_select_business",function()
    {
        var value=parseInt($(".zjs_select_business").val());
        if(value==0){
            $(".zjs_query_business_name").val('');
            return;
        }else{
            var str=$(".zjs_select_business option:selected").html();
            $(".zjs_query_business_name").val(str);
            $(".zjs_select_business").hide();
        }
    });


});

function get_list()
{
    //var status=$(".zjs_status").val();
    var id=$(".zjs_query_id").val();
    var province=$(".zjs_select_province_open").val();
    var city=$(".zjs_select_city").val();
    var district=$(".zjs_select_district").val();
    var shop_name=$(".zjs_query_shop_name").val();
    var contact_name=$(".zjs_query_contact_name").val();
    var business_id=$(".zjs_select_business").val();
    var business=$(".zjs_query_business_name").val();
    if(business==''){
        business_id=0;
    }
    if (business!='' && (business_id==0 || business_id=='')) {
        layer.msg('请在下拉列表中选择业务员或重新输入');
        //$(".zjs_query_business_name").val('');
        return;
    }


    var url="/shop/shop/index?id="+id+"&province="+province+"&city="+city+"&district="+district+"&like_shop_name="+encodeURIComponent(shop_name)+"&like_contact_name="+encodeURIComponent(contact_name)+"&business_id="+business_id;
    //alert(url);
    //console.log(url);
    window.location.href=url;
}

function get_business()
{
    var kw=$(".zjs_query_business_name").val();
    if(kw==''){
        return;
    }
    if(kw.length>10){
        return;
    }
    if ((/^[A-Za-z]+$/.test(kw))) {
        return;
    }

    var x=$(".zjs_query_business_name").offset().left;
    var y=$(".zjs_query_business_name").offset().top + $(".zjs_query_business_name").height()+6;
    $(".zjs_select_business").html("<option value='0'>获取中...</option>").show().offset({"top":y,"left":x});
    $.ajax({
        "type":"post",
        "url": "/shop/shop/ajax",
        "dataType":"json",
        "data":{
            "act":"getsalesman",
            "kw":kw
        },
        "success":function(obj){
            //console.log("success");
            if(obj.code=='200'){
                if(obj.data.data.length>0){
                    var len=obj.data.data.length;
                    var html='';
                    var data=obj.data.data;
                    html+='<option value="0">请选择</option>';
                    for(var i=0;i<len;i++){
                        //html+='<option value="'+data[i].id+'">'+data[i].name+'-'+data[i].id+'</option>';
                        html+='<option value="'+data[i].id+'">'+data[i].name+'</option>';
                    }
                    var x=$(".zjs_query_business_name").offset().left;
                    var y=$(".zjs_query_business_name").offset().top + $(".zjs_query_business_name").height()+6;
                    $(".zjs_select_business").html(html).show().offset({"top":y,"left":x});
                }else{
                    $(".zjs_select_business").html("<option value='0'>无数据</option>");
                }
            }else{
                $(".zjs_select_business").html("<option value='0'>查询失败</option>");
            }
        },
        "error":function(){
            //console.log("error");
            $(".zjs_select_business").html("<option value='0'>查询失败</option>");
        },
        "complete":function(){
            //console.log("complete");
        }
    });
}

</script>
