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
        <th colspan="2">充值余额</th>
        <th colspan="2">订单收益</th>
        <th colspan="2">红包</th>
        <th colspan="2">积分</th>
    </tr>
    <?php if(empty($list)) {
        echo '<tr><td colspan="24" style="text-align:center;">暂无记录</td></tr>';
    }else{
    foreach($list as $item){
    ?>
    <tr>
        <td colspan="2"><?= $item['id'];?></td>
        <td colspan="2"><?= $item['uid'];?></td>
        <td colspan="2"><?= $item['mobile'];?></td>
        <td colspan="2"><?= $item['money'];?></td>
        <td colspan="2"><?= $item['money_earnings'];?></td>
        <td colspan="2"><?= $item['money_giving'];?></td>
        <td colspan="2"><?= $item['integral'];?></td>
        <td colspan="2"><?= $item['create_time'];?></td>

    </tr>
    <? } ?>
    <tr>
        <td colspan="10">
            <a class="btn btn-primary" href="javascript:history.go(-1);">返回</a>
            <button id="tongguo" class="btn btn-primary">审核</button>
            <button id="del" class="btn btn-primary">删除</button>
        </td>
    </tr>

</table>

