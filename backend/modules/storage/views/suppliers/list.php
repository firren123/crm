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
 * @author    sunsong <sunsongsong@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/8/24 下午2:40
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */
use yii\widgets\LinkPager;
use yii\helpers\Html;
$this->title = '管理采购入库订单';

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
<form action="/storage/suppliers/supplier-list" id="form" method="get">
    <table  class="table table-bordered table-hover">
        <tr>
            <th width="10%">供应商名称</th>
            <td><input type="text" id="supplier_name" maxlength="8" size="8" name="supplier_name" value="<?=$name?>"/></td>
            <th>入库时间</th>
            <td>
                <input readonly id="time_one" name="time_one" maxlength="20" size="20" value="<?=$time_one?>" onFocus="WdatePicker({isShowClear:true,readOnly:false})"/>
            </td>
            <th>至</th>
            <td>
                <input readonly id="time_two" name="time_two" maxlength="20" size="20" value="<?=$time_two?>" onFocus="WdatePicker({isShowClear:true,readOnly:false})"/>
            </td>
            <td>
                <!--<a href="javascript:;" class="btn btn-primary">搜索</a>-->
                <input type="button" onclick="searchlist()" value="搜索" class="btn btn-primary" />
                &nbsp;共&nbsp;<span style="font-size:18px;color:#FF0000;"><?=$count;?></span>&nbsp;条搜索结果
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
            <th>入库单号</th>
            <!--<th>商品摘要</th>-->
            <th>供应商</th>
            <th>入库时间</th>
            <th>入库说明</th>
            <th>操作</th>
        </tr>
        <?php if(empty($data)) {
            echo '<tr><td colspan="7" style="text-align:center;">暂无记录</td></tr>';
        }else{
            foreach ($data as $item):
                ?>
                <tr class="order_list">

                    <td width="10%"><?= $item['id'];?></td>
                    <td><?= $item['code'];?></td>
                    <td><?= $item['supplier_name'];?></td>
                    <td><?=$item['create_time']?></td>
                    <td><?=$item['remark']?></td>
                    <td><a href="/storage/suppliers/view?id=<?=$item['id'];?>">查看</td>
                </tr>
            <?php endforeach;
        }
        ?>
    </table>
    <div id="zcss_page" class="pages">
        <?= LinkPager::widget(['pagination' => $pages]); ?>
    </div>
</div>
<script type="text/javascript">
    function searchlist()
    {

        var supplier_name = $('#supplier_name').val();
        var time_one = $('#time_one').val();
        var time_two =  $('#time_two').val();

        if(supplier_name != '')
        {
            location.href="/storage/suppliers/supplier-list?supplier_name="+supplier_name;
        }
        if(time_one != '')
        {
            location.href="/storage/suppliers/supplier-list?time_one="+time_one;
        }
        if(time_two != '')
        {
            location.href="/storage/suppliers/supplier-list?time_two="+time_two;
        }
        if(supplier_name != '' && time_one != '')
        {
            location.href="/storage/suppliers/supplier-list?supplier_name="+supplier_name+"&time_one="+time_one;
        }
        if(supplier_name != '' && time_two != '')
        {
            location.href="/storage/suppliers/supplier-list?supplier_name="+supplier_name+"&time_two="+time_two;
        }
        if(time_one != '' && time_two != '')
        {
            location.href="/storage/suppliers/supplier-list?time_one="+time_one+"&time_two="+time_two;
        }
        if(time_one != '' && time_two != '' && supplier_name != '')
        {
            location.href="/storage/suppliers/supplier-list?time_one="+time_one+"&time_two="+time_two+"&supplier_name="+supplier_name;
        }

    }
</script>