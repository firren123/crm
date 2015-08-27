<?php
$this->title = "业务员轨迹";
use yii\widgets\LinkPager;
?>
<legends  style="fond-size:12px;">
    <legend>业务员轨迹</legend>
</legends>
<div class="tab-content">
    <div class="row-fluid">
        <table class="table table-bordered table-hover">
            <tbody>
            <tr>
                <th>ID</th>
                <th>业务员ID</th>
                <th>地址</th>
                <th>店铺位置X坐标</th>
                <th>店铺位置y坐标</th>
                <th>创建时间</th>
            </tr>
            </tbody>
            <?php if(empty($data)){
                echo "<tr><td colspan='6'><div>暂无数据！！！</div></td></tr>";
            } else {
                foreach ($data as $item):
            ?>
            <tr>
                <td><?=$item['id']?></td>
                <td><?=$item['business_id']?></td>
                <td><?=$item['address']?></td>
                <td><?=$item['position_x']?></td>
                <td><?=$item['position_y']?></td>
                <td><?=$item['create_time']?></td>
            </tr>
            <?php endforeach;}?>
        </table>
        <div class="pagination pull-right">
            <?= LinkPager::widget(['pagination' => $pages]); ?>
        </div>
    </div>
</div>
