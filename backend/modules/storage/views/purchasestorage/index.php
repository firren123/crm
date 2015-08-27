<?php
/**
 * 列表-view
 *
 * PHP Version 5
 *
 * @category  ADMIN
 * @package   CONTROLLER
 * @author    zhengyu <zhengyu@iyangpin.com>
 * @time      15/5/29 14:59
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      zhengyu@iyangpin.com
 */



use yii\widgets\LinkPager;
$this->title = "采购入库单列表";

//echo 'index-view';exit;

?>

<link type="text/css" rel="stylesheet" href="/css/zcommon.css?<?php echo \Yii::$app->params['cssVersion']; ?>" />

<script src="/js/My97DatePicker/WdatePicker.js"></script>
<script src="/js/zcommon.js"></script>

<style type="text/css">

.zcss_td_op a{padding:0 20px 0 0;}
.zcss_td_op input{}

</style>



<div>
    <ul class="breadcrumb">
        <li><a href="/">首页</a></li>
        <li class="active"><a href="/storage/purchasestorage" style="color:#286090;">采购入库单管理</a></li>
        <li class="active">列表</li>
    </ul>
</div>


<div class="zcss_query_aera">

    <span style="padding:0 0 0 20px;">供应商名称:</span>
    <input type="text" class="zjs_query_spkw" style="width:100px;" value="<?php if(isset($arr_select_param['sp_kw'])){echo $arr_select_param['sp_kw'];} ?>" />

    <span style="padding:0 0 0 20px;">入库时间:</span>
    <input type="text" class="zjs_start_time" onFocus="WdatePicker({isShowClear:true,readOnly:false,dateFmt:'yyyy-MM-dd',minDate:'2015-01-01',maxDate:'2115-12-31'})" value="<?php echo $arr_select_param['create_time_start']; ?>" />
    <span>到</span>
    <input type="text" class="zjs_end_time" onFocus="WdatePicker({isShowClear:true,readOnly:false,dateFmt:'yyyy-MM-dd',minDate:'2015-01-01',maxDate:'2115-12-31'})" value="<?php echo $arr_select_param['create_time_end']; ?>" />

    <input type="button" value="查询" class="zjs_btn_query" style="width:80px;margin:0 0 0 30px;" />
</div>

<div class="zcss_w100">
    <table class="zcss_table_list"><tbody>
        <tr>
            <th>行号</th>
            <th>入库单号</th>
            <th>商品摘要</th>
            <th>供应商</th>
            <th>入库日期</th>
            <th>入库说明</th>
            <th>操作</th>
        </tr>
        <?php foreach($arr_ruku_list as $index=>$a_row){ ?>
            <tr>
                <td><?php echo $index+1; ?></td>
                <td><?php if(isset($a_row['id'])){echo $a_row['id'];} ?></td>
                <td><?php if(isset($a_row['id'])){echo $a_row['sp_name'];} ?></td>
                <td><?php if(isset($a_row['sp_name'])){echo $a_row['sp_name'];} ?></td>
                <td><?php if(isset($a_row['create_time'])){echo $a_row['create_time'];} ?></td>
                <td><?php if(isset($a_row['remark'])){echo $a_row['remark'];} ?></td>
                <td class="zcss_tac">
                    <a href="#detail" class="zjs_view_detail">查看</a>
                    <a href="/storage/purchasestorage/create-code?ps_id=<?php if(isset($a_row['id'])){echo $a_row['id'];} ?>">生成</a>
                    <span class="zjs_ruku_id" style="display:none;"><?php if(isset($a_row['id'])){echo $a_row['id'];} ?></span>
                </td>
            </tr>
        <?php } ?>
        <?php if($record_count==0){ ?>
            <tr>
                <td colspan="8" class="zcss_tac">无数据</td>
            </tr>
        <?php } ?>
    </tbody></table>

    <?php echo LinkPager::widget(['pagination' => $pages]); ?>

</div>


<div class="zcss_w100">
    <?php foreach($arr_ruku_list as $a_ruku){ ?>
        <table class="zcss_table_list zjs_detail_ zjs_detail_<?php if(isset($a_ruku['id'])){echo $a_ruku['id'];} ?>" style="display:none;"><tbody>
            <tr>
                <td colspan="7" class="zcss_tac">商品明细</td>
            </tr>
            <tr>
                <th>行号</th>
                <th>商品名</th>
                <th>商品规格</th>
                <th>入库数</th>
                <th>已入库</th>
                <th>差额</th>
                <th>备注</th>
            </tr>
            <?php if(!empty($a_ruku['detail'])){ ?>
                <?php foreach($a_ruku['detail'] as $index=>$a_goods){ ?>
                    <tr>
                        <td><?php echo $index+1; ?></td>
                        <td><?php if(isset($a_goods['goods_name'])){echo $a_goods['goods_name'];} ?></td>
                        <td><?php if(isset($a_goods['attr_value'])){echo $a_goods['attr_value'];} ?></td>
                        <td><?php if(isset($a_goods['num'])){echo $a_goods['num'];} ?></td>
                        <td>
                            <?php
                            $num_yiruku=0;
                            if(isset($a_goods['good_number'])){$num_yiruku+=intval($a_goods['good_number']);}
                            if(isset($a_goods['defective_number'])){$num_yiruku+=intval($a_goods['defective_number']);}
                            echo $num_yiruku;
                            ?>
                        </td>
                        <td>
                            <?php
                            if(isset($a_goods['num'])){echo intval($a_goods['num'])-$num_yiruku;}
                            ?>
                        </td>
                        <td><?php if(isset($a_goods['remark'])){echo $a_goods['remark'];} ?></td>
                    </tr>
                <?php } ?>
            <?php }else{ ?>
                <tr>
                    <td colspan="7" class="zcss_tac">无数据</td>
                </tr>
            <?php } ?>
        </tbody></table>
    <?php } ?>
</div>







<span class="zjs_csrf" style="display:none;"><?php echo \Yii::$app->getRequest()->getCsrfToken(); ?></span>


<script type="text/javascript">
$(function()
{
    $(".zjs_btn_query").click(function()
    {
        get_list();
    });

    $(document).on("click",".zjs_view_detail",function()
    {
        if (event.preventDefault) {
            event.preventDefault();
        } else {
            event.returnValue = false;
        }

        var cur_ruku_id=$(this).closest("td").find(".zjs_ruku_id").html();
        $(".zjs_detail_").hide();
        $(".zjs_detail_"+cur_ruku_id).show();
    });


});

function get_list()
{
    var sp_kw=$(".zjs_query_spkw").val();
    var create_time_start=$(".zjs_start_time").val();
    var create_time_end=$(".zjs_end_time").val();

    var url="/storage/purchasestorage/index?sp_kw="+encodeURIComponent(sp_kw)+"&create_time_start="+create_time_start+"&create_time_end="+create_time_end;
    //console.log(url);
    window.location.href=url;
}

</script>
