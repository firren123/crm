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
$this->title = '管理采购入库订单明细';

?>
<legends style="fond-size:12px;">
    <legend>管理采购订单明细</legend>
</legends>
    <table class="table table-bordered table-hover">
        <tr>
            <th colspan="9">商品明细</th>
        </tr>
        <tr>
            <th width="10%">商品ID</th>
            <th>物品名称</th>
            <th>规格类型</th>
            <th>入库数量</th>
            <th>备注</th>
            <!--<th>生成条码</th>-->
        </tr>
        <?php if(empty($data)) {
            echo '<tr><td colspan="7" style="text-align:center;">暂无记录</td></tr>';
        }else{
            foreach ($data as $item):
                ?>
                <tr class="order_list">

                    <td width="10%"><?= $item['good_id'];?></td>
                    <td class=""><?= $item['good_name'];?></td>
                    <td><?= $item['attr_value'];?></td>
                    <td><?= $item['num'];?></td>
                    <td><?=$item['remark']?></td>
                    <!--<td><a href="javascript:;">生成</td>-->
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