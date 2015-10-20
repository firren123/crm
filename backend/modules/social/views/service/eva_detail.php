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

<div class="breadcrumbs">您现在的位置：<a href="/">首页</a><span>&gt;</span><a
        href="/social/service/eva-list">店铺评论</a><span>&gt;</span><span>店铺评论详情</span>
</div>

<table class="table table-bordered table-hover">
    <tr>
        <th colspan="5">订单评论
        </th>
    </tr>
    <tr>
        <th>订单号</th>
        <th>用户手机</th>
        <th>星级</th>
        <th>类型</th>
        <th>时间</th>
    </tr>
        <tr>
            <td><?= $list['order_sn']; ?></td>
            <td><?= $list['mobile']; ?></td>
            <td><?= $list['star']; ?></td>
            <td><?= $list['type']==1?'体验方':'服务方'; ?></td>
            <td><?= $list['create_time']; ?></td>
        </tr>
    <tr>
        <th colspan="5">评论内容
        </th>
    </tr>
    <tr>
        <td colspan="5"><?= $list['content']; ?></td>
    </tr>

    <tr>
        <td colspan="5">
            <a class="btn btn-primary" href="javascript:history.go(-1);">返回</a>
        </td>
    </tr>

</table>


