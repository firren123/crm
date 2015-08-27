<?php
$this->title = "优惠券列表";
use yii\widgets\LinkPager;
?>
<legends  style="fond-size:12px;">
    <legend>优惠券管理</legend>
</legends>
<?php
echo $this->render('_search', ['type_list'=>$type_list,'type_id'=>$type_id,'name'=>$name,'use_system'=>$use_system]);
?>
<div class="tab-content">
    <div class="row-fluid">
        <table class="table table-bordered table-hover">
            <tbody>
            <tr>

                <th style="width: 20%">优惠券名称</th>
                <th>限定区域</th>
                <th>用户名称</th>
                <th>最小订单金额</th>
                <th>现金券面值</th>
                <th>消费积分</th>
                <th style="width: 10%">过期时间</th>
                <th>现金券状态</th>
                <th>操作</th>
            </tr>
            </tbody>
            <tfoot>
            <?php if(empty($data)){
                echo '<tr><td colspan="10" style="text-align:center;">暂无记录</td></tr>';
            }else{
                foreach ($data as $item):
                    ?>
                    <tr>
                        <td><?= $item['type_name'];?></td>
                        <td><?= empty($item['area_name']) ? '--' :$item['area_name'];?></td>
                        <td><?= $item['mobile'];?></td>
                        <td><?= $item['min_amount'];?></td>
                        <td><?= $item['par_value'];?></td>
                        <td><?= $item['consumer_points'];?></td>
                        <td><?= $item['expired_time'];?></td>
                        <td><?= empty($item['status_name']) ? '--' :$item['status_name'];?></td>
                        <td style="width: 7%"><a href="/admin/coupons/view?id=<?= $item['id'];?>&type=<?= $use_system?>">详情</a></td>
                    </tr>
                <?php endforeach;
            }
            ?>
            </tfoot>
            </table>
        <div class="pagination pull-right">
            <?= LinkPager::widget(['pagination' => $pages]); ?>
        </div>
        </div>
    </div>