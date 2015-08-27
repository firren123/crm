<?php
$this->title = "商家订单流水";
use yii\widgets\LinkPager;
?>
<legends  style="fond-size:12px;">
    <legend>商家订单每日流水</legend>
</legends>
<!--<div class="wide form">
    <form id="search-form" class="well form-inline" action="/admin/businessinfo/shop-orderflow" method="get">
        <label for="name">商家ID：</label>
        <input id="shop_id" type="text" name="shop_id" value="<?/*=$shop_id*/?>" class="form-control">
        <button id="yw3" class="btn btn-primary" name="yt0" type="submit">搜索</button>
    </form>
</div>-->
<div class="tab-content">
    <div class="row-fluid">
        <table class="table table-bordered table-hover">
            <tbody>
            <tr>
                <th>ID</th>
                <th>商家ID</th>
                <th>订单数</th>
                <th>订单总额</th>
                <th>自营总额</th>
                <th>500m特供总额</th>
                <th>时间</th>
            </tr>
            <?php if(empty($data)) {
                echo '<tr><td colspan="11" style="text-align:center;">暂无记录</td></tr>';
            }else{
                foreach ($data as $item):
                    ?>
                    <tr>
                        <td class="ywy_id look"><?= $item['id'];?></td>
                        <td class="shop_id"><?= $item['business_id'];?></td>
                        <td><?=$item['order_total']?></td>
                        <td><?= $item['money_total'];?></td>
                        <td><?= $item['zy_money_total'];?></td>
                        <td><?= $item['tg_money_total'];?></td>
                        <td><?= $item['data_time'];?></td>
                    </tr>
                <?php endforeach;
            }
            ?>
            </tbody>
        </table>
        <div class="pagination pull-right">
            <?= LinkPager::widget(['pagination' => $pages]); ?>
        </div>
    </div>
</div>