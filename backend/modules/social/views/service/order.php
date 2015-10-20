<?php
/**
 * 简介1
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Crm
 * @filename  setting.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/9/23 上午10:28
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */

use yii\helpers\Html;
use yii\widgets\LinkPager;
$this->title = "服务订单列表";

?>
<script type="text/javascript" src="/js/My97DatePicker/WdatePicker.js"></script>

<legends  style="fond-size:12px;">
    <legend>服务订单列表</legend>
</legends>
<div class="wide form">
    <form id="search-form" class="well form-inline" action="/social/service/order" method="get">
        <dl>
            <dd>
                <label for="mobile">用户手机号：</label>
                <input id="mobile" type="text" name="mobile" value="<?= $mobile;?>" class="form-control">
                <label for="service_mobile">服务方手机号：</label>
                <input id="service_mobile" type="text" name="service_mobile" value="<?= $service_mobile;?>" class="form-control">
            </dd>
        </dl>
        <dl>
            <dd>
                <label for="status">订 单 状 态：</label>
                <select id="status" name="status" class="form-control">
                    <option  value="999">全部</option>
                    <?php foreach($order_status_data as $k => $v){ ?>
                        <option <?php if($k == $status){echo " selected ";}?> value="<?= $k; ?>"><?= $v; ?></option>
                    <?php } ?>

                </select>
                <label for="mobile">支付状态：</label>
                <select id="status" name="pay_status" class="form-control">
                    <option  value="999">全部</option>
                    <?php foreach($order_pay_status_data as $k => $v){ ?>
                        <option <?php if($k == $pay_status){echo " selected ";}?> value="<?= $k; ?>"><?= $v; ?></option>
                    <?php } ?>

                </select>
                <button id="sub" class="btn btn-primary" name="yt0" type="submit">搜索</button>
            </dd>
        </dl>

    </form>
</div>

<legends  style="fond-size:12px;">
    <div class="tab-content">
        <div class="row-fluid">
            <table  class="table table-bordered table-hover">
                <tbody>
                <tr>

                    <th colspan="2">ID</th>
                    <th colspan="2">时间</th>
                    <th colspan="2">用户手机号</th>
                    <th colspan="2">服务方手机号</th>
                    <th colspan="2">订单状态</th>
                    <th colspan="2">支付状态</th>
                    <th colspan="4">操作</th>
                </tr>
                <?php if(empty($list)) {
                    echo '<tr><td colspan="24" style="text-align:center;">暂无记录</td></tr>';
                }else{
                    foreach($list as $item){
                        ?>
                        <tr>
                            <td colspan="2"><?= $item['id'];?></td>
                            <td colspan="2"><?= $item['create_time'];?></td>
                            <td colspan="2"><?= $item['mobile'];?></td>
                            <td colspan="2"><?= $item['service_mobile'];?></td>
                            <td colspan="2"><?= $order_status_data[$item['status']];?></td>
                            <td colspan="2"><?= $order_pay_status_data[$item['pay_status']];?></td>
                            <td colspan="4">
                                <a href="<?= '/social/service/order-detail?order_sn='.$item['order_sn'];?>">详情</a>
                            </td>
                        </tr>
                    <?php } }?>

                </tbody>
            </table>
            <?= LinkPager::widget(['pagination' => $pages]); ?>
        </div>
    </div>

</legends>
<script>
    $(function(){
        $("#sub").click(function(){
            var sn = $("#order_sn").val();
//            console.log(sn);
//            console.log(sn.length);
            if(sn.length > 0 && sn.length < 6 ){
                alert('订单号搜索不得少于6位末尾订单号');
                return false;
            }
        })

    })
</script>