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
$this->title = '商家参与活动列表';

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
    <legend>商家参与活动列表</legend>
        <ul class="breadcrumb">
            <li>
                <a href="/">首页</a>
            </li>
            <li class="">活动列表</li>
        </ul>
</legends>
    <table class="table table-bordered table-hover">
        <tr>
            <th colspan="9">商家列表</th>
        </tr>
        <tr>
            <th width="10%">行号</th>
            <th>商家名称</th>
            <th>经营种类</th>
            <th>商家地址</th>
        </tr>
        <?php if(empty($data)) {
            echo '<tr><td colspan="7" style="text-align:center;">暂无记录</td></tr>';
        }else{
            foreach ($data as $item):
                ?>
                <tr class="order_list">

                    <td width="10%"><?= $item['id'];?></td>
                    <td><?= $item['shop_name'];?></td>
                    <td><?=empty($item['m_name'])?'':$item['m_name']?></td>
                    <td>
                        <?=empty($item['p_name'])?'':$item['p_name']?>&nbsp;&nbsp;
                        <?=empty($item['c_name'])?'':$item['c_name']?>&nbsp;&nbsp;
                        <?=empty($item['d_name'])?'':$item['d_name']?>
                    </td>
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

        var reason = $("#reason option:selected").val();
        var time_one = $('#time_one').val();
        var time_two =  $('#time_two').val();

        if(reason != '')
        {
            location.href="/storage/storage-out/list?reason="+reason;
        }
        if(time_one != '')
        {
            location.href="/storage/storage-out/list?time_one="+time_one;
        }
        if(time_two != '')
        {
            location.href="/storage/storage-out/list?time_two="+time_two;
        }
        if(reason != '' && time_one != '')
        {
            location.href="/storage/storage-out/list?reason="+reason+"&time_one="+time_one;
        }
        if(reason != '' && time_two != '')
        {
            location.href="/storage/storage-out/list?reason="+reason+"&time_two="+time_two;
        }
        if(time_one != '' && time_two != '')
        {
            location.href="/storage/storage-out/list?time_one="+time_one+"&time_two="+time_two;
        }
        if(time_one != '' && time_two != '' && supplier_name != '')
        {
            location.href="/storage/storage-out/list?time_one="+time_one+"&time_two="+time_two+"&reason="+reason;
        }

    }
</script>