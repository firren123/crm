<?php
/**
 * 简介1
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   admin
 * @filename  order.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/5/26 下午2:40
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */
use yii\widgets\LinkPager;
use yii\helpers\Html;
$this->title = '管理采购订单';

?>
<script type="text/javascript" src="/js/My97DatePicker/WdatePicker.js"></script>
<style>
    .num{width: 60px;}
    .price{width: 50px;}
    .total{width: 90px;}
    .order_list{
        cursor: pointer;
    }

</style>
<legends style="fond-size:12px;">
    <legend>管理采购订单</legend>
</legends>
<form action="/supplier/supplierorder/order-list" id="form" method="get">
    <table  class="table table-bordered table-hover">
        <!--<tr>
            <td colspan="3"><a href="" id="selectedCom" class="btn btn-primary">选择供应商</a></td>
        </tr>-->
        <tr>
            <th width="20%">供应商</th>
            <td colspan="2"><input type="text" id="supplier_name" name="supplier_name" value="<?=$supplier_name?>"/></td>
            <th width="20%">订单编号</th>
            <td colspan="2"><input id="order_sn" name="order_sn" type="text" value="<?=$order_sn?>"/></td>
            <th width="20%">付款状态</th>
            <td colspan="2" id="pay_status">
                <select name="pay_status" id="pay_status">
                    <option value="999">全部</option>
                    <?php foreach($pay_status_data as $k=>$v){ ?>
                        <option value="<?= $k; ?>" <?php if($pay_status ==$k){echo " selected ";}?>><?= $v; ?></option>
                     <?php } ?>

                </select>
            </td>
        </tr>
        <tr>
            <th>订购日期</th>
            <td colspan="2">
                <input readonly id="pay_one" name="pay_time_one" value="<?=$pay_time_one?>" onFocus="WdatePicker({isShowClear:true,readOnly:false})"/>
            </td>
            <th>至</th>
            <td colspan="2">
                <input readonly id="pay_two" name="pay_time_two" value="<?=$pay_time_two?>" onFocus="WdatePicker({isShowClear:true,readOnly:false})"/>
            </td>
            <th></th>
            <td colspan="2"></td>
        </tr>
        <tr>
            <th>交付日期</th>
            <td colspan="2">
                <input readonly id="receive_one" name="arrange_receive_time_one" value="<?=$receive_time_one?>" onFocus="WdatePicker({isShowClear:true,readOnly:false})"/>
            </td>
            <th>至</th>
            <td colspan="2">
                <input readonly id="receive_two" name="arrange_receive_time_two" value="<?=$receive_time_two?>" onFocus="WdatePicker({isShowClear:true,readOnly:false})"/>
            </td>
            <td colspan="3">
                <a href="javascript:search()" id="search" class="btn btn-primary">搜索</a>
                &nbsp;共&nbsp;<span style="font-size:18px;color:#FF0000;"><?=$count?></span>&nbsp;条搜索结果
            </td>
        </tr>

    </table>
</form>
    <table class="table table-bordered table-hover">
        <tr>
            <th colspan="9">采购订单列表</th>
        </tr>
        <tr>
            <th width="10%">行号</th>
            <th>采购订单号</th>
            <th>供应商名称</th>
            <th>采购日期</th>
            <th>交货日期</th>
            <th>总金额</th>
            <th>付款状态</th>
            <th>采购说明</th>
        </tr style="cursor:pointer">
        <?php if(empty($list)) {
            echo '<tr><td colspan="7" style="text-align:center;">暂无记录</td></tr>';
        }else{
            foreach ($list as $item):
                ?>
                <tr class="order_list">

                    <td width="10%"><?= $item['id'];?></td>
                    <td class="order_sn"><?= $item['order_sn'];?></td>
                    <td><?= $item['supplier_name'];?></td>
                    <td><?=$item['create_time']?></td>
                    <td><?= $item['arrange_receive_time'];?></td>
                    <td><?=$item['total']?></td>
                    <td><?php if(isset($pay_status_data[$item['pay_status']])){echo $pay_status_data[$item['pay_status']];}; ?></td>
                    <td><?= $item['remark'];?></td>
                </tr>
            <?php endforeach;
        }
        ?>
    </table>
    <div id="zcss_page" class="pages">
        <?= LinkPager::widget(['pagination' => $pages]); ?>
    </div>
<table class="table table-bordered table-hover">
    <tr>
        <th colspan="8">订单详情</th>
    </tr>
    <tr id="add_order_detail">
        <th width="10%">行号</th>
        <th>物品名称</th>
        <th>规格型号</th>
        <th>单位</th>
        <th>单价</th>
        <th>采购数量</th>
        <th>金额</th>
        <th>备注</th>
    </tr>
        <tr id="empty_detail"><td colspan="8" style="text-align:center;">暂无记录</td></tr>



</table>
</div>
<script type="text/javascript" src="/js/supplier/order.js"></script>
<script type="text/javascript">
    function search(){
        var pay_one     = document.getElementById('pay_one').value;
        var pay_two     = document.getElementById('pay_two').value;
        var receive_one = document.getElementById('receive_one').value;
        var receive_two = document.getElementById('receive_two').value;
        if(pay_one > pay_two)
        {
            alert("订购日期范围不正确");
            return;
        }
        if(receive_one >receive_two)
        {
            alert("交付日期范围不正确");
            return;
        }
        document.getElementById('form').submit();
    }
    $(".order_list").click(function(){
        var order_sn = $(this).find(".order_sn").html();
        $(this).parent().find("tr").attr("style","");
        $(this).attr('style','background:#337ab7');
        console.log(order_sn);
        order.getOrderDetail(order_sn);
    })
</script>
