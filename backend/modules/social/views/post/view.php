<?php
/**
 * 简介1
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Admin
 * @filename  view.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/8/13 下午3:51
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>

<script type="text/javascript" src="/js/webuploader//webuploader.js"></script>
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
</style>
<link rel="stylesheet" type="text/css" href="/js/webuploader/webuploader.css">
<legends  style="fond-size:12px;">
    <ul class="breadcrumb">
        <li>
            <a href="/">首页</a>
        </li>
        <li><a href="/social/post/forum-list">板块管理</a></li>
        <li class="active">查看 板块</li>
    </ul>
    <div class="tab-content">
        <div class="row-fluid">
        </div>
    </div>
</legends>

<table class="table table-bordered table-hover">
    <tr>
        <th colspan="6" style="text-align: left;">板块信息</th>
    </tr>
    <tr>
        <th width="20%">用户</th>
        <td colspan="5" width="80%">
            <?= $item['mobile'];?>
        </td>
    </tr>
    <tr>
        <th width="20%">版块名称</th>
        <td colspan="5" width="80%">
            <?= $forum_list[$item['forum_id']];?>

        </td>
    </tr>
    <tr>
        <th>标题</th>
        <td colspan="5" width="80%">
            <?= $item['title'];?>
        </td>
    </tr>
    <tr>
        <th>版块图片</th>
        <td colspan="5" width="80%">
            <?php foreach ($item['post_img'] as $k => $v) {
                if ($v != '') { ?>
                    <img width="90px" height="90px" src="<?= \Yii::$app->params['imgHost'] . $v; ?>" alt=""/>

                <?php }
            } ?>

        </td>
    </tr>
    <tr>
        <th>点击数</th>
        <td colspan="5" width="80%">
            <?= $item['thumbs'];?>
        </td>
    </tr>
    <tr>
        <th>浏览数</th>
        <td colspan="5" width="80%">
            <?= $item['views'];?>
        </td>
    </tr>

    <tr>
        <th>是否置顶</th>
        <td colspan="5" width="80%">
            <?php if($item['top']==1){ echo '是';}else{ echo "否";}?>
        </td>
    </tr>
    <tr>
        <th>是否禁用</th>
        <td colspan="5" width="80%">
            <?php if($item['status']==1){ echo '禁用';}else{ echo "可用";}?>

        </td>
    </tr>
    <tr>
        <th>内容</th>
        <td colspan="5" width="80%">
            <?= $item['content'];?>
        </td>
    </tr>

    <tr>
        <th>创建时间</th>
        <td colspan="5" width="80%">
            <?= $item['create_time'];?>
        </td>
    </tr>
    <tr>
        <th>是否删除</th>
        <td colspan="5" width="80%">
            <?php if($item['status']==1){ echo '已删除';}else{ echo "未删除";}?>

        </td>
    </tr>
</table>

<div class="form-actions">
    <a class="btn cancelBtn" href="javascript:history.go(-1);">取消</a>
</div>
