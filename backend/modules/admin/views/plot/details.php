<?php
$this->title = '小区详情';
?>
<legends  style="fond-size:12px;">
    <ul class="breadcrumb">
        <li>
            <a href="/">首页</a>
        </li>
        <li><a href="/admin/plot/index">小区管理</a></li>
        <li class="active">小区详情</li>
    </ul>
    <div class="tab-content">
        <div class="row-fluid">
        </div>
    </div>
</legends>
<table class="table table-bordered table-hover">
    <?php if (!empty($list)) {?>
        <tr>
            <td style="width:20%;">小区名称：</td>
            <td><?= empty($list['name']) ? '--' : $list['name'];?></td>
        </tr>
        <tr>
            <td style="width:20%;">区域：</td>
            <td><?= empty($list['area']) ? '--' : $list['area'];?></td>
        </tr>
        <tr>
            <td style="width:20%;">地址：</td>
            <td><?= empty($list['address']) ? '--' : $list['address'];?></td>
        </tr>
        <tr>
            <td style="width:20%;">经度：</td>
            <td><?= empty($list['lng']) ? '--' : $list['lng'];?></td>
        </tr>
        <tr>
            <td style="width:20%;">纬度：</td>
            <td><?= empty($list['lat']) ? '--' : $list['lat'];?></td>
        </tr>
        <tr>
            <td style="width:20%;">均价：</td>
            <td><?= empty($list['average']) ? '--' : $list['average'];?></td>
        </tr>
        <tr>
            <td style="width:20%;">总面积：</td>
            <td><?= empty($list['total_area']) ? '--' : $list['total_area'];?></td>
        </tr> <tr>
            <td style="width:20%;">总户数：</td>
            <td><?= empty($list['total_number']) ? '--' : $list['total_number'];?></td>
        </tr>
        <tr>
            <td style="width:20%;">开发商：</td>
            <td><?= empty($list['developer']) ? '--' : $list['developer'];?></td>
        <tr>
            <td style="width:20%;">建筑年代：</td>
            <td><?= empty($list['buildings']) ? '--' : $list['buildings'];?></td>
        </tr>
        <tr>
            <td style="width:20%;">容积率：</td>
            <td><?= empty($list['volume_rate']) ? '--' : $list['volume_rate'];?></td>
        </tr>
        <tr>
            <td style="width:20%;">物业公司：</td>
            <td><?= empty($list['property']) ? '--' : $list['property'];?></td>
        </tr>
        <tr>
            <td style="width:20%;">出租率：</td>
            <td><?= empty($list['letting_rate']) ? '--' : $list['letting_rate'];?></td>
        </tr>
        <tr>
            <td style="width:20%;">物业类型：</td>
            <td><?= empty($list['property_type']) ? '--' : $list['property_type'];?></td>
        </tr>
        <tr>
            <td style="width:20%;">停车位：</td>
            <td><?= empty($list['parking']) ? '--' : $list['parking'];?></td>
        </tr>
        <tr>
            <td style="width:20%;">物业费用：</td>
            <td><?= empty($list['property_fee']) ? '--' : $list['property_fee'];?></td>
        </tr>
        <tr>
            <td style="width:20%;">绿化率：</td>
            <td><?= empty($list['greening_rate']) ? '--' : $list['greening_rate'];?></td>
        </tr>
        <tr>
            <td style="width:20%;">创建时间：</td>
            <td><?= empty($list['create_time']) ? '--' : $list['create_time'];?></td>
        </tr>
        <tr>
            <td style="width:20%;">状态：</td>
            <td><?= $list['status']==1 ? '启用' : '禁用';?></td>
        </tr>
    <?php }else{ ?>
        <tr>
        <tr><td colspan="2" style="text-align:center;">暂无记录</td></tr>
        </tr>
    <?php }?>
    <tr>
        <td colspan="2" style="text-align:center;"><a class="btn cancelBtn" href="<?= '/admin/plot/index?city_name='.$city_id;?>">返回</a></td>
    </tr>
</table>