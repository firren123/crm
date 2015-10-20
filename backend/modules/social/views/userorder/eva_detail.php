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


?>
<legends  style="fond-size:12px;">
    <legend>店铺评论</legend>
</legends>
<div class="breadcrumbs">您现在的位置：<a href="/">首页</a><span>&gt;</span><a
        href="/social/userorder/grade-list">店铺评论</a><span>&gt;</span><span>店铺评论详情</span>
</div>

<table class="table table-bordered table-hover">
    <tr>
        <th colspan="14">订单评论
        </th>
    </tr>
    <tr>
        <th colspan="2">ID</th>
        <th colspan="2">用户名</th>
        <th colspan="2">商家名称</th>
        <th colspan="2">手机号</th>
        <th colspan="2">用户评分</th>
        <th colspan="2">订单号</th>
        <th colspan="2">创建时间</th>
    </tr>
    <tr>
        <td colspan="2"><?= $list['id']; ?></td>
        <td colspan="2"><?= $list['uid']; ?></td>
        <td colspan="2"><?= $list['shop_id']; ?></td>
        <td colspan="2"><?= $list['mobile']; ?></td>
        <td colspan="2"><?= $list['grade']; ?></td>
        <td colspan="2"><?= $list['order_sn']; ?></td>
        <td colspan="2"><?= $list['create_time']; ?></td>

    </tr>

    <tr>
        <th colspan="14">评论内容
        </th>
    </tr>
    <tr>
        <td colspan="14"><?= $list['content']; ?></td>
    </tr>

    <tr>
        <td colspan="14">
            <a class="btn btn-primary" href="javascript:history.go(-1);">返回</a>
        </td>
    </tr>

</table>


