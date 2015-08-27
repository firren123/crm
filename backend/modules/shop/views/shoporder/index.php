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

$this->title = "商家订单列表";

?>
<?= $this->registerJsFile("@web/js/jquery-1.10.2.min.js"); ?>
<?= $this->registerJsFile("@web/js/shoporder.js"); ?>
<script src="/js/My97DatePicker/WdatePicker.js"></script>
<link rel="stylesheet" href="/css/crmBase.css">



<div class="wide form">
    <form id="search-form" class="well form-inline" action="/shop/shoporder/index" method="get">

        <label for="ship_status">订单物流状态：</label>
        <select id="ship_status" name="ship_status" class="form-control">
            <?php foreach($ship_status_data as $k => $v){ ?>
                <option <?php if($k == $ship_status){echo " selected ";}?> value="<?= $k; ?>"><?= $v; ?></option>
            <?php } ?>

        </select>
        <label for="status">订单确认状态：</label>
        <select id="status" name="status" class="form-control">
            <?php foreach($status_data as $k => $v){ ?>
                <option <?php if($k == $status){echo " selected ";}?> value="<?= $k; ?>"><?= $v; ?></option>
            <?php } ?>

        </select>
        <label for="pay_status">订单支付状态：</label>
        <select id="pay_status" name="pay_status" class="form-control">
            <?php foreach($pay_status_data as $k => $v){ ?>
                <option <?php if($k == $pay_status){echo " selected ";}?> value="<?= $k; ?>"><?= $v; ?></option>
            <?php } ?>

        </select>
        <br /><br />
        <label for="shop_name">商家名称：</label>
        <input id="shop_name" type="text"  name="shop_name" value="<?= $shop_name;?>" class="form-control"/>

        <label for="order_sn">订单号：</label>
        <input id="order_sn" type="text" size="31" name="order_sn" value="<?= $order_sn;?>" class="form-control"/>

        <br /><br />
        <label for="start_time">开始时间：</label>
        <input id="start_time" type="text" id="start_time" name="start_time" onFocus="WdatePicker({isShowClear:true,readOnly:false})" value="<?php if(isset($start_time)){echo $start_time; };?>" class="form-control">
        <label for="end_time">结束时间：</label>
        <input id="end_time" type="text" name="end_time" onFocus="WdatePicker({isShowClear:true,readOnly:false})" value="<?php if(isset($end_time)){echo $end_time; };?>" class="form-control">
        <button id="yw3" class="btn btn-primary" name="yt0" type="submit">搜索</button>
    </form>
</div>
<div class="tab-content">

    <div class="row-fluid">
        <table class="table table-bordered table-hover">

            <tr>
<!--                <th><input type="checkbox" id="all"/>全选</th>-->
                <th>订单号</th>
                 <th >商家名称</th>
                <th>总金额</th>
                <th>订单确认</th>
                <th>支付状态</th>
                <th>物流状态</th>
                <th>下单时间</th>
                <th>操作</th>

            </tr>
            <?php if (!empty($list) || $list != null) { ?>
<!--                <form action="/shop/shoporder/alledit" method="post">-->
                    <?php foreach ($list as $k => $v) { ?>
                        <tr <?php if(in_array($v['id'],$result_arr)):?> bgcolor="#aaaaaa"<?php endif;?> >
<!--                            <td><input type="checkbox" name="order_sn[]" id="o_id"-->
<!--                                       value="--><?//= html::encode($v['order_sn']) ?><!--"/>-->
                            <td><a href="/shop/shoporder/detail?order_sn=<?= html::encode($v['order_sn']); ?>" target="_blank"><?= html::encode($v['order_sn']); ?></a></td>
                                        <td><a href="/shop/shop/detail?id=<?= $v['shop_id']?>" target="_blank"><?= Html::encode($v['shop_name']);?></a></td>
                            <td><?= html::encode($v['total']); ?></td>
                            <td>
                                <?php if ($v['status'] == 0) { ?>
                                   未确认
                                <?php } else {
                                    echo $status_data[$v['status']];
                                } ?>
                            </td>
                            <td>
                                <?php
                                echo $v['pay_status']==0?'未支付':'已支付';
//                                echo $pay_status_data[$v['pay_status']];
                                ?>
                            </td>
                            <td>
                                <?php if ($v['ship_status'] == 1) { ?>
                                    已发货
                                <?php } else {
                                    echo $ship_status_data[$v['ship_status']];
                                } ?>
                            </td>
                            <td>
                                <?= html::encode($v['create_time']); ?>
                            </td>
                            <td>
                                <a
                                   href="/shop/shoporder/detail?order_sn=<?= html::encode($v['order_sn']); ?>">查看详情</a>
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