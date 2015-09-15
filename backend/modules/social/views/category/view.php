<?php
/**
 * 简介1
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Admin
 * @filename  forum_view.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/8/14 上午11:17
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = '查看服务类型详情';
?>
<style>
    .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td{
        text-align: left;
    }
    .compileForm .imgListForm{padding-top:10px; height:117px;}
    ul li{
        float: left;

    }
    ul{list-style: none;}
    .imgListForm li{width:92px;min-height:117px; border:none; background:#fff;}
    .imgListForm a,.imgListForm span{display:block;}
    .imgListForm a{width:90px; height:90px; text-align:center; border:1px solid #dfdfdf; background:#f5f5f5;}
    .imgListForm span{color:#666; line-height:25px; cursor:pointer;text-align: center;}
    .breadcrumb{height: 35px}
</style>
<link rel="stylesheet" type="text/css" href="/js/webuploader/webuploader.css">
<legends  style="fond-size:12px;">
    <ul class="breadcrumb">
        <li>
            <a href="/">首页</a>
        </li>
        <li><a href="/social/category">服务类型管理</a></li>
        <li class="active">查看 服务类型</li>
    </ul>
    <div class="tab-content">
        <div class="row-fluid">
        </div>
    </div>
</legends>

<table class="table table-bordered table-hover">
    <tr>
        <th colspan="6" style="text-align: left;">服务类型信息</th>
    </tr>
    <tr>
        <th width="20%">服务类型标题</th>
        <td colspan="5" width="80%">
            <?= empty($item['name']) ? '--' : $item['name'];?>
        </td>
    </tr>
    <tr>
        <th width="20%">服务类型描述</th>
        <td colspan="5" width="80%">
            <?= empty($item['description']) ? '--' : $item['description'];?>
        </td>
    </tr>
    <tr>
        <th>父级</th>
        <td colspan="5" width="80%">
            <?php
            if ($item['pid']==0) {
                echo "顶级分类";
            } else {
            ?>
            <a href="/social/category/view?id=<?= empty($item['pid']) ? 0 : $item['pid'];?>" ><?= empty($item['pid_name']) ? '顶级分类' : $item['pid_name'];?></a>
            <?php }?>
        </td>
    </tr>
    <tr>
        <th>服务类型图片</th>
        <td colspan="5" width="80%">
            <img src="<?= empty($item['image']) ? '' : \Yii::$app->params['imgHost'].$item['image'];?>" alt="<?= empty($item['name']) ? '--' : $item['name'];?>"/>
        </td>
    </tr>
    <tr>
        <th>排序</th>
        <td colspan="5" width="80%">
            <?= empty($item['sort']) ? '--' : $item['sort'];?>
        </td>
    </tr>
    <tr>
        <th>是否可用</th>
        <td colspan="5" width="80%">
            <?php if($item['status']==1){ echo '可用';}else{ echo "禁用";}?>

        </td>
    </tr>
    <tr>
        <th>是否显示</th>
        <td colspan="5" width="80%">
            <?php if($item['is_deleted']==1){ echo '不显示';}else{ echo "显示";}?>
        </td>
    </tr>
    <tr>
        <th>创建时间</th>
        <td colspan="5" width="80%">
            <?= empty($item['create_time']) ? '--' : $item['create_time'];?>
        </td>
    </tr>
    </tr>
    <tr>
        <th>更新时间</th>
        <td colspan="5" width="80%">
            <?= empty($item['update_time']) ? '--' : $item['update_time'];?>
        </td>
    </tr>
    </tr>
    </table>

<div class="form-actions">
    <a class="btn cancelBtn" href="javascript:history.go(-1);">返回</a>
</div>
