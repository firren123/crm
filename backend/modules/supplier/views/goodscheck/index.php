<?php
/**
 * 列表-view
 *
 * PHP Version 5
 *
 * @category  ADMIN
 * @package   CONTROLLER
 * @author    zhengyu <zhengyu@iyangpin.com>
 * @time      15/9/15 11:10
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      zhengyu@iyangpin.com
 */


$map_field_name = array(
    'id' => 'id',
    'title' => '商品名称',
    'supplier_id' => '省',
    'category_id' => '市',
    'image' => '区/县',
    'bar_code' => '状态',
    'supply_price' => '状态',
    'selling_price' => '状态',
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
        <li class="active"><a href="/supplier/supplier" style="color:#286090;">供应商管理</a></li>
        <li class="active">待审核商品-列表</li>
    </ul>
</div>


<div class="zcss_query_aera">

    <select class="zjs_select_province_open">
        <option value="0">全部分类</option>
        <?php foreach($arr_all_cate as $a_cate){ ?>
            <option value="<?php echo $a_cate['id']; ?>"<?php if($arr_select_param['category_id']==$a_cate['id']){echo " selected=\"selected\" ";} ?>><?php echo $a_cate['name']; ?></option>
        <?php } ?>
    </select>


    <span class="zcss_span1">商品名称:</span>
    <input type="text" class="zjs_query_id" style="width:50px;" value="<?php if($arr_select_param['like_goods_name']!=''){echo $arr_select_param['like_goods_name'];} ?>" />

    <span class="zcss_span1">条形码:</span>
    <input type="text" class="zjs_query_shop_name" style="width:80px;" value="<?php if($arr_select_param['bar_code']!=''){echo $arr_select_param['bar_code'];} ?>" />


    <input type="button" value="查询" class="zjs_btn_query" style="width:80px;margin:0 0 0 30px;" />
</div>

<div style="width:100%;overflow-x:auto;">
    <table class="zcss_table_list"><tbody>
        <tr>
            <th style="width:70px;">商品名称</th>
            <th style="width:210px;">操作</th>
        </tr>
        <?php foreach($arr_goods_list as $a_row){ ?>
            <tr>
                <td class="zjs_id zcss_tac"><?php if(isset($a_row['title'])){echo $a_row['title'];} ?></td>
                <td class="zcss_td_op zcss_tac">
                    <a href="/shop/shop/detail?id=<?php if(isset($a_row['id'])){echo $a_row['id'];} ?>" target="_blank">详情</a>
                    <a href="/shop/shop/edit?id=<?php if(isset($a_row['id'])){echo $a_row['id'];} ?>" target="_blank">修改</a>
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
