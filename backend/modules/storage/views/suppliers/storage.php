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
$this->title = '管理采购库存';

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
    <legend>管理采购库存</legend>
</legends>
<form action="/storage/suppliers/supplier-list" id="form" method="get">
    <table  class="table table-bordered table-hover">
        <tr>
            <th width="10%">选择仓库</th>
            <td>
                <select id="depots" name="depots">
                    <option value="">全部库存</option>
                    <?php if(!empty($ware)){?>
                        <?php foreach($ware as $item):?>
                            <option value="<?=$item['sn']?>"><?=$item['name']?></option>
                        <?php endforeach;?>
                    <?php }?>
                </select>
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
            <th>商品ID</th>
            <th>商品名称</th>
            <th>商品型号</th>
            <th>库存数量</th>
            <th>条形码</th>
        </tr>
        <?php if(empty($data)) {
            echo '<tr><td colspan="7" style="text-align:center;">暂无记录</td></tr>';
        }else{
            foreach ($data as $item):
                ?>
                <tr class="order_list">

                    <td width="10%"><?= $item['id'];?></td>
                    <td><?= $item['good_id'];?></td>
                    <td><?= $item['good_name'];?></td>
                    <td><?= $item['attr_value'];?></td>
                    <td><?=empty($item['allnum']) ? $item['total'] : $item['allnum'];?></td>
                    <td><?=$item['bar_code']?></td>
                    <!--<td><a href="javascript:;">查看</td>-->
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

        var depots = $.trim($("#depots option:selected").val());

        if(depots != '')
        {
            location.href="/storage/suppliers/storage?sn="+depots;
        }else{
            location.href="/storage/suppliers/storage";
        }

    }
</script>