<?php
/**
 * 简介1
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Crm
 * @filename  setting_detail.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/9/23 上午11:02
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */
?>
<?= $this->registerCssFile("@web/css/globalm.css"); ?>
<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = '钱包详情';

?>
<div class="breadcrumbs">您现在的位置：<a href="/">首页</a><span>&gt;</span><a href="/social/wallet/index">钱包</a><span>&gt;</span><span>钱包详情</span>
</div>
<table class="table table-bordered table-hover">
    <tr>
        <th colspan="2">ID</th>
        <th colspan="2">用户ID</th>
        <th colspan="2">手机号</th>
        <th colspan="4">充值余额</th>
        <th colspan="4">订单收益</th>
        <th colspan="4">红包</th>
        <th colspan="2">积分</th>
        <th colspan="2">创建时间</th>
    </tr>
    <tr>
        <td colspan="2"><?= $list['id'];?></td>
        <td colspan="2"><?= $list['uid'];?></td>
        <td colspan="2"><?= $list['mobile'];?></td>
        <td colspan="4"><?= $list['money'];?></td>
        <td colspan="4"><?= $list['money_earnings'];?></td>
        <td colspan="4"><?= $list['money_giving'];?></td>
        <td colspan="2"><?= $list['integral'];?></td>
        <td colspan="2"><?= $list['create_time'];?></td>
    </tr>
</table>
<table class="table table-bordered table-hover">
    <tr>
        <td colspan="30">提现详细</td>
    </tr>
    <tr>
        <th colspan="2">ID</th>
        <th colspan="2">用户ID</th>
        <th colspan="2">手机号</th>
        <th colspan="2">姓名</th>
        <th colspan="2">银行卡号</th>
        <th colspan="2">提现金额</th>
        <th colspan="2">状态</th>
        <th colspan="2">提现时间</th>
        <th colspan="2">预计到账时间</th>
        <th colspan="2">到账时间</th>
        <th colspan="2">操作</th>

    </tr>

    <?php if(empty($withdrawal_list)) {
        echo '<tr><td colspan="30" style="text-align:center;">暂无记录</td></tr>';
    }else{
        foreach($withdrawal_list as $item){
            ?>
            <tr>
                <td colspan="2"><?= $item['id'];?></td>
                <td colspan="2"><?= $item['uid'];?></td>
                <td colspan="2"><?= $item['mobile'];?></td>
                <td colspan="2"><?= $item['real_name'];?></td>
                <td colspan="2"><?= $item['bank_card'];?></td>
                <td colspan="2"><?= $item['money'];?></td>
                <td colspan="2"><?= $item['status'];?></td>
                <td colspan="2"><?= $item['create_time'];?></td>
                <td colspan="2"><?= $item['expect_arrival_time'];?></td>
                <td colspan="2"><?= $item['arrival_time'];?></td>
                <td colspan="4">
                    <a href="#" class="log" m="<?= $item['id'];?>">
                        <div style="height: 30px;width: 30px;background: url('/images/code_br.jpg') no-repeat -710px -599px">
                        </div>
                    </a>
                </td>
            </tr>
        <?php } }?>

    <tr>
        <td colspan="30"><?= \yii\widgets\LinkPager::widget(['pagination' => $pages]); ?></td>
    </tr>

</table>
<script>
    $(function(){
        $(".log").click(function(){
            var id = $(this).attr("m");
            var d = dialog({
                'title':'提现流程',
                okValue: '关闭',
                fixed: true,
                url: '/social/wallet/wal-log?id='+id,
                content: '提现日志',
                quickClose: true,
                ok: function () {}
            });
            d.show();
            return false;
        });
    });

</script>
