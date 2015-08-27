<?php
$this->title = '商品详情';
?>
<legends  style="fond-size:12px;">
    <ul class="breadcrumb">
        <li>
            <a href="/">首页</a>
        </li>
        <li><a href="/shop/cash">商家提现申请</a></li>
        <li class="active">商家提现申请详情</li>
    </ul>
    <div class="tab-content">
        <div class="row-fluid">
        </div>
    </div>
</legends>
<table class="table table-bordered table-hover" style="width: 60%">
    <?php if (!empty($item)) {?>
        <tr>
            <td style="width:30%;">商家名称：</td>
            <td><?= empty($item['shop_name']) ? '--' : $item['shop_name'];?></td>
        </tr>
        <tr>
            <td style="width:30%;">账期：</td>
            <td><?= empty($item['account_period']) ? '--' : $item['account_period'];?></td>
        </tr>
        <tr>
            <td style="width:30%;">账户名：</td>
            <td><?= empty($item['account_name']) ? '--' : $item['account_name'];?></td>
        </tr>
        <tr>
            <td style="width:30%;">卡号：</td>
            <td><?= empty($item['account_card']) ? '--' : $item['account_card'];?></td>
        </tr>
        <tr>
            <td style="width:30%;">账号类型：</td>
            <td><?= empty($item['money']) ? '--' : $item['money'];?></td>
        </tr>
        <tr>
            <td style="width:30%;">申请提现时间：</td>
            <td><?= empty($item['apply_time']) ? '--' : $item['apply_time'];?></td>
        </tr>
        <tr>
            <td style="width:30%;">处理时间：</td>
            <td><?= empty($item['handle_time']) ? '--' : $item['handle_time'];?></td>
        </tr> <tr>
            <td style="width:30%;">审核状态：</td>
            <td><?php
                if ($item['status']==0) {
                    echo "审核中";
                } elseif($item['status']==1) {
                    echo "审核通过";
                } elseif($item['status']==2) {
                    echo "审核驳回";
                } else {
                    echo "--";
                }
                ?></td>
        </tr>

    <?php }else{ ?>
        <tr>
            <tr><td colspan="2" style="text-align:center;">暂无记录</td></tr>
        </tr>
    <?php }?>

</table>
<div class="row-fluid">
    <table class="table table-bordered table-hover" style="width:60%;">
        <tr>
            <th style="width: 5%">ID</th>
            <th style="width: 20%">账期</th>
            <th style="width: 20%">账单金额</th>
            <th>结算状态</th>
            <th>审核状态</th>
        </tr>
        <tfoot>
        <?php if(empty($data)) {
            echo '<tr><td colspan="14">暂无申请</td></tr>';
        }else {
            foreach ($data as $item):
                ?>
                <tr>
                    <td><?= $item['id']?></td>
                    <td><?= date('Y-m-d', strtotime($item['start_time'])).'-'.date('Y-m-d', strtotime($item['end_time']))?></td>
                    <td><?= $item['money']?></td>
                    <td><?php switch($item['status']){
                            case 0;
                                echo "未结算";
                                break;
                            case 1;
                                echo "已结算";
                                break;
                            case 2;
                                echo "冻结";
                                break;
                        }?></td>
                    <td><?php switch($item['is_apply']){
                            case 0;
                                echo "未审核";
                                break;
                            case 1;
                                echo "审核中";
                                break;
                            case 2;
                                echo "审核通过";
                                break;
                        }?></td>
                </tr>
            <?php
            endforeach;
        }
        ?>
        </tfoot>
    </table>
</div>
<div class="form-actions">
    <a class="btn cancelBtn" href="/shop/cash">返回</a>
</div>