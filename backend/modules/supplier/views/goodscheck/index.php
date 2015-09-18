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
$this->title = "列表-供应商商品审核";

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

.zcss_table_list td{padding:3px 10px;}
</style>



<div>
    <ul class="breadcrumb">
        <li><a href="/">首页</a></li>
        <li class="active"><a href="/supplier/supplier" style="color:#286090;">供应商管理</a></li>
        <li class="active">待审核商品-列表</li>
    </ul>
</div>


<div class="zcss_query_aera">

    <select class="zjs_select_cate">
        <option value="0">全部分类</option>
        <?php foreach($arr_all_cate as $a_cate){ ?>
            <option value="<?php echo $a_cate['id']; ?>"<?php if($arr_select_param['category_id']==$a_cate['id']){echo " selected=\"selected\" ";} ?>><?php echo $a_cate['name']; ?></option>
        <?php } ?>
    </select>


    <span class="zcss_span1">商品名称:</span>
    <input type="text" class="zjs_query_goods_name" style="width:150px;" value="<?php if($arr_select_param['like_goods_name']!=''){echo $arr_select_param['like_goods_name'];} ?>" />

    <span class="zcss_span1">条形码:</span>
    <input type="text" class="zjs_query_bar_code" style="width:150px;" value="<?php if($arr_select_param['bar_code']!=''){echo $arr_select_param['bar_code'];} ?>" />


    <input type="button" value="查询" class="zjs_btn_query" style="width:80px;margin:0 0 0 30px;" />
</div>

<div style="width:100%;overflow-x:auto;">
    <table class="zcss_table_list"><tbody>
        <tr>
            <th style="">商品名称</th>
            <th style="width:60px;">分类</th>
            <th style="width:140px;">条形码</th>
            <th style="width:80px;">供货价</th>
            <th style="width:80px;">建议售价</th>
            <th style="width:150px;">供应商名称</th>
            <th style="width:80px;">操作</th>
        </tr>
        <?php foreach($arr_goods_list as $a_row){ ?>
            <tr>
                <td class=""><?php if(isset($a_row['title'])){echo $a_row['title'];} ?></td>
                <td class="zcss_tac">
                    <?php if(isset($a_row['category_id']) && isset($map_cate_list[$a_row['category_id']])){ ?>
                        <?php if($map_cate_list[$a_row['category_id']]['status']==2){ ?>
                            <?php echo $map_cate_list[$a_row['category_id']]['name']; ?>
                        <?php }else{ ?>
                            <span class="zcss_fc_red" title="此分类不可用"><?php echo $map_cate_list[$a_row['category_id']]['name']; ?></span>
                        <?php } ?>
                    <?php }else{ ?>
                        <span class="zcss_fc_red">无分类</span>
                    <?php } ?>
                </td>
                <td class="zcss_tac"><?php if(isset($a_row['bar_code'])){echo $a_row['bar_code'];} ?></td>
                <td class="zcss_tar"><?php if(isset($a_row['supply_price'])){echo $a_row['supply_price'];} ?></td>
                <td class="zcss_tar"><?php if(isset($a_row['selling_price'])){echo $a_row['selling_price'];} ?></td>
                <td class="">
                    <?php if(isset($a_row['supplier_id']) && isset($map_sp_list[$a_row['supplier_id']])){ ?>
                        <?php if($map_sp_list[$a_row['supplier_id']]['status']==1){ ?>
                            <?php echo $map_sp_list[$a_row['supplier_id']]['company_name']; ?>
                        <?php }else{ ?>
                            <span class="zcss_fc_red" title="此供应商不可用"><?php echo $map_sp_list[$a_row['supplier_id']]['company_name']; ?></span>
                        <?php } ?>
                    <?php }else{ ?>
                        <span class="zcss_fc_red">无供应商</span>
                    <?php } ?>
                </td>

                <td class="zcss_td_op zcss_tac">
                    <a href="/supplier/goodscheck/detail?id=<?php if(isset($a_row['id'])){echo $a_row['id'];} ?>" target="_blank">详情</a>
                </td>
            </tr>
        <?php } ?>
        <?php if($record_count==0){ ?>
            <tr>
                <td colspan="7" class="zcss_tac">无数据</td>
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

});

function get_list()
{
    var cate_id=$(".zjs_select_cate").val();
    var goods_name=$(".zjs_query_goods_name").val();
    var bar_code=$(".zjs_query_bar_code").val();

    var url="/supplier/goodscheck/index?cate_id="+cate_id+"&bar_code="+encodeURIComponent(bar_code)+"&like_goods_name="+encodeURIComponent(goods_name);
    window.location.href=url;
}

</script>
