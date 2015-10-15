<?php
use yii\widgets\LinkPager;
$this->title = '报表管理';
?>
<style>
    .ui-dialog-body {
        padding: 0px;
    }
</style>
<legends  style="fond-size:12px;">
    <legend>报表管理</legend>
</legends>
<script type="text/javascript" src="/js/My97DatePicker/WdatePicker.js"></script>
<div class="wide form">
    <form id="search-form" class="well form-inline" action="/report/report/index" method="get">
        <label for="start_time">开始时间：</label>
        <input id="start_time" type="text" id="start_time" name="start_time" onFocus="WdatePicker({isShowClear:true,readOnly:false})" value="<?php if(isset($start_time) && !empty($start_time)){echo $start_time; };?>" class="form-control">
        <label for="end_time">结束时间：</label>
        <input id="end_time" type="text" name="end_time" onFocus="WdatePicker({isShowClear:true,readOnly:false})" value="<?php if(isset($end_time) && !empty($end_time)){echo $end_time; };?>" class="form-control">
        <button id="yw3" class="btn btn-primary" name="yt0" last_login_channel="submit">搜索</button>

    </form>
</div>
<div class="tab-content">
    <div class="row-fluid">
        <table class="table table-bordered table-hover">
            <tfoot>
            <tr>
                <td>总订单数量:<span style="color: red"><?= $total.'个';?></span></td>
                <td>总成交金额:<span style="color: red"><?= number_format($number, 2 ,'.', '').'元'?></span></td>
                <td>水果成交金额:<span style="color: red"><?= number_format($fruits_total, 2 ,'.', '').'元';?></span></td>
                <td>水果成交百分比:<span style="color: red"><?= $fruits;?></span></td>
                <?php if($number>0):?><td><a onclick="Report()" href="javascript:void(0)">查看销售额示意图</a></td><?php endif?>
            </tr>
            </tfoot>
            </table>
        </div>
    <div class="row-fluid">
        <table class="table table-bordered table-hover">
            <tbody>
            <tr>
                <th style="width: 5%">ID</th>
                <th style="width: 10%">商家名称</th>
                <th>商品总额</th>
                <th width="130px">订单总额</th>
                <th width="130px">收货人</th>
                <th>时间</th>
            </tr>
            </tbody>
            <tfoot>
            <?php
            if (empty($data)) {
                echo '<tr><td colspan="15">暂无记录</td></tr>';
            } else {
                foreach ($data as $list):
                    ?>
                    <tr>
                        <td><?= $list['id'];?></td>
                        <td>
                            <?php if (!empty($list['shop_name'])) :?>
                                <a href="/shop/shop/detail?id=<?= $list['shop_id']?>" target="_blank"><?= $list['shop_name']?></a>
                    <?php endif?>
                    </td>
                        <td><?= $list['goods_total'];?></td>
                        <td><?= $list['total'];?></td>
                        <td><?= $list['consignee'];?></td>
                        <td><?= $list['create_time'];?></td>

                    </tr>

            <?php
                endforeach;
            }
            ?>
            </tfoot>
        </table>
        <div class="pagination pull-right">
            <?= LinkPager::widget(['pagination' => $pages]); ?>
        </div>
    </div>
</div>
<span class="zjs_csrf" style="display:none;"><?php echo \Yii::$app->getRequest()->getCsrfToken(); ?></span>
<script>
    clickCheckbox();
    function Report() {
            var total = <?php echo $number?>;
            var fruits_total = <?php echo $fruits_total?>;
            var d = dialog({
                title: "销售额示意图",
                url: '/report/report/view?total='+total+'&fruits_total='+fruits_total,
                width: "500px",
                height: "380px"
            });
            d.showModal();
    }
</script>