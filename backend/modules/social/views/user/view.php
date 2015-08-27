<?php
$this->title = '用户详情';
?>
<legends  style="fond-size:12px;">
    <ul class="breadcrumb">
        <li>
            <a href="/">首页</a>
        </li>
        <li><a href="/social/user">用户管理</a></li>
        <li class="active">用户详情</li>
    </ul>
    <div class="tab-content">
        <div class="row-fluid">
        </div>
    </div>
</legends>
<table class="table table-bordered table-hover" style="width: 60%">
    <?php if (!empty($item)) {?>
        <tr>
            <td style="width:20%;">手机号：</td>
            <td><?= empty($item['mobile']) ? '--' : $item['mobile'];?></td>
        </tr>
        <tr>
            <td style="width:20%;">昵称：</td>
            <td><?= empty($item['nickname']) ? '--' : $item['nickname'];?></td>
        </tr>
        <tr>
            <td style="width:20%;">真实姓名：</td>
            <td><?= empty($item['realname']) ? '--' : $item['realname'];?></td>
        </tr>
        <tr>
            <td style="width:20%;">头像：</td>
            <td><img src="<?= empty($item['avatar']) ? '/images/05_mid.jpg' : $item['avatar'];?>" style="max-width: 300px;max-height: 300px;"></td>
        </tr>
        <tr>
            <td style="width:20%;">性别：</td>
            <td>
                <?php
                switch ($item['sex']) {
                    case 1;
                        echo "男";
                        break;
                    case 2;
                        echo "女";
                        break;
                    case 0;
                        echo "未知";
                        break;
                }
                ?>
            </td>
        </tr>
<!--        <tr>-->
<!--            <td style="width:20%;">性取向：</td>-->
<!--            <td>-->
<!--                --><?php
//                switch ($item['sex_orientation']) {
//                    case 1;
//                        echo "男";
//                        break;
//                    case 2;
//                        echo "女";
//                        break;
//                    case 0;
//                        echo "未知";
//                        break;
//                }
//                ?>
<!--            </td>-->
<!--        </tr>-->
        <tr>
            <td style="width:20%;">手机号：</td>
            <td><?= empty($item['mobile']) ? '--' : $item['mobile'];?></td>
        </tr>
        <tr>
            <td style="width:20%;">城市：</td>
            <td><?= empty($item['province_name']) ? '--' : $item['province_name'];?>
                <?= empty($item['city_name']) ? '--' : $item['city_name'];?>
                <?= empty($item['district_name']) ? '--' : $item['district_name'];?></td>
        </tr>
<!--        <tr>-->
<!--            <td style="width:20%;">感情状况：</td>-->
<!--            <td>--><?//= empty($item['relationship_status']) ? '--' : $item['relationship_status'];?><!--</td>-->
<!--        </tr>-->
        <tr>
            <td style="width:20%;">生日：</td>
            <td><?= empty($item['birthday']) ? '--' : $item['birthday'];?></td>
        </tr>
<!--        <tr>-->
<!--            <td style="width:20%;">血型：</td>-->
<!--            <td>--><?//= empty($item['blood_type']) ? '--' : $item['blood_type'];?><!--</td>-->
<!--        </tr>-->
<!--        <tr>-->
<!--            <td style="width:20%;">星座：</td>-->
<!--            <td>--><?//= empty($item['constellation']) ? '--' : $item['constellation'];?><!--</td>-->
<!--        </tr>-->
        <tr>
            <td style="width:20%;">个人签名：</td>
            <td><?= empty($item['personal_sign']) ? '--' : $item['personal_sign'];?></td>
        </tr>
<!--        <tr>-->
<!--            <td style="width:20%;">博客地址：</td>-->
<!--            <td>--><?//= empty($item['blog_address']) ? '--' : $item['blog_address'];?><!--</td>-->
<!--        </tr>-->
<!--        <tr>-->
<!--            <td style="width:20%;">QQ号：</td>-->
<!--            <td>--><?//= empty($item['qq_number']) ? '--' : $item['qq_number'];?><!--</td>-->
<!--        </tr>-->
<!--        <tr>-->
<!--            <td style="width:20%;">微信号：</td>-->
<!--            <td>--><?//= empty($item['weixin_number']) ? '--' : $item['mobile'];?><!--</td>-->
<!--        </tr>-->
        <tr>
            <td style="width:20%;">创建时间：</td>
            <td><?= empty($item['create_time']) ? '--' : $item['create_time'];?></td>
        </tr>
        <tr>
            <td style="width:20%;">更新时间：</td>
            <td><?= empty($item['update_time']) ? '--' : $item['update_time'];?></td>
        </tr>

    <?php }else{ ?>
        <tr>
            <tr><td colspan="2" style="text-align:center;">暂无记录</td></tr>
        </tr>
    <?php }?>
    <tr>
        <td colspan="2" style="text-align:center;"><a class="btn cancelBtn" href="/social/user">返回</a></td>
    </tr>
</table>