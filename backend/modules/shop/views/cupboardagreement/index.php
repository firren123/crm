<?php
/**
 * 简介1
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   I500
 * @filename  index.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/3/22 上午11:32
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */
use yii\helpers\Html;
use yii\widgets\LinkPager;
$this->title = "商家协议列表";
?>
<?= $this->registerJsFile("@web/js/jquery-1.10.2.min.js"); ?>
<?= $this->registerJsFile("@web/js/shoporder.js"); ?>
<script src="/js/My97DatePicker/WdatePicker.js"></script>
<link rel="stylesheet" href="/css/crmBase.css">
<legends  style="fond-size:12px;">
    <legend>商家展位协议列表</legend>
</legends>
<div class="wide form">
    <form id="search-form" class="well form-inline" action="/shop/cupboardagreement/index" method="get">
        <label for="shop_name">商家名称：</label>
        <input id="shop_name" type="text"  name="shop_name" value="<?= $shop_name;?>" class="form-control"/>
        <label for="sn">协议编号：</label>
        <input id="sn" type="text" size="31" name="sn" value="<?= $sn;?>" class="form-control"/>
        <label for="status">协议确认状态：</label>
        <select id="status" name="status" class="form-control">
            <?php foreach($status_data as $k => $v){ ?>
                <option <?php if($k == $status){echo " selected ";}?> value="<?= $k; ?>"><?= $v; ?></option>
            <?php } ?>

        </select>

        <button id="yw3" class="btn btn-primary" name="yt0" type="submit">搜索</button>
    </form>
</div>

<a id="yw0" class="btn btn-primary" href="/shop/cupboardagreement/add" style="margin-bottom:20px;">添加展位协议</a>

<div class="tab-content">
    <div class="row-fluid">
        <table class="table table-bordered table-hover">

            <tr>
<!--                <th><input type="checkbox" id="all"/>全选</th>-->
                <th>协议编号</th>
                 <th >商家名称</th>
                <th>展位名称</th>
                <th>展位收费类型</th>
                <th>展位租用金额</th>
                <th>可使用展位时常</th>
                <th>协议状态</th>
                <th>创建时间</th>
                <th>操作</th>

            </tr>
            <?php if (!empty($list) || $list != null) { ?>
<!--                <form action="/shop/Cupboardagreement/alledit" method="post">-->
                    <?php foreach ($list as $k => $v) { ?>
                        <tr <?php if(in_array($v['id'],$result_arr)):?> bgcolor="#aaaaaa"<?php endif;?> >
<!--                            <td><input type="checkbox" name="order_sn[]" id="o_id"-->
<!--                                       value="--><?//= html::encode($v['order_sn']) ?><!--"/>-->
                            <td><a href="/shop/cupboardagreement/detail?id=<?= html::encode($v['id']); ?>"><?= html::encode($v['sn']); ?></a></td>
                            <td><?= Html::encode($v['shop_name']);?></td>
                            <td><?= Html::encode($v['title']);?></td>
                            <td><?= html::encode($jiesuan_data[$v['type']]); ?></td>
                            <td><?= html::encode($v['cupboard_amount']); ?></td>
                            <td><?= html::encode($v['cupboard_period']); ?></td>
                            <td><?= html::encode($status_data[$v['status']]); ?></td>
                            <td><?= html::encode($v['create_time']); ?></td>
                            <td>
                                <a
                                   href="/shop/cupboardagreement/detail?id=<?= html::encode($v['id']); ?>">查看详情</a>
                            </td>
                        </tr>
                    <?php } ?>
<!--                    <tr>-->
<!--                        <td colspan="2">-->
<!--                            <input type="radio" value="1" name="type"/>确认-->
<!--                            <input type="radio" value="2" name="type"/>发货-->
<!--                            <input type="radio" value="3" name="type"/>收款-->
<!--                        </td>-->
<!--                        <td colspan="7">-->
<!---->
<!--                            <input type="submit" value="提交"/>-->
<!--                        </td>-->
<!--                    </tr>-->
<!--                </form>-->
            <?php } ?>


        </table>
    </div>
    <div class="pages">
        <?= LinkPager::widget(['pagination' => $pages]) ?>
    </div>
</div>