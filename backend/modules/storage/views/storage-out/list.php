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
$this->title = '管理出库订单';

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
    <legend>管理出库订单</legend>
</legends>
<form action="/storage/storage-out/list" id="form" method="get">
    <table  class="table table-bordered table-hover">
        <tr>
            <th width="10%">出库原因</th>
            <td>
                <select id="reason" name="reason">
                    <option value="1" <?php if($reason == 1){echo "selected";}?>>销售</option>
                    <option value="2" <?php if($reason == 2){echo "selected";}?>>员工福利</option>
                    <option value="3" <?php if($reason == 3){echo "selected";}?>>清理库存</option>
                    <option value="4" <?php if($reason == 4){echo "selected";}?>>调拨出库</option>
                </select>
            </td>
            <th>出库时间</th>
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
            <th>出库单号</th>
            <th>仓库名称</th>
            <th>出库原因</th>
            <th>出库说明</th>
            <th>出库时间</th>
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
                    <td><?=$item['name'];?></td>
                    <td>
                        <?php
                        if($item['status'] == 1){
                            echo "销售";
                        }else if($item['status'] == 2){
                            echo "员工福利";
                        }else if($item['status'] == 3){
                            echo "清理库存";
                        }else if($item['status'] == 4){
                            echo "调拨出库";
                        }
                        ?>
                    </td>
                    <td><?=$item['remark']?></td>
                    <td><?=$item['create_time']?></td>
                    <td><a href="/storage/storage-out/view?id=<?=$item['id'];?>">查看</td>
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