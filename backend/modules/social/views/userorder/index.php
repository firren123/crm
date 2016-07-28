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
 * @author    zhaochengqiang <zhaochengqiang@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/3/22 下午19:32
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */
use yii\helpers\Html;
use yii\widgets\LinkPager;
$this->title = "用户订单列表";

?>
<legends  style="fond-size:12px;">
    <legend>用户订单列表</legend>
</legends>
<script type="text/javascript" src="/js/My97DatePicker/WdatePicker.js"></script>
<?= $this->registerJsFile("@web/js/shoporder.js");?>


<div class="wide form">
    <form id="search-form" class="well form-inline" action="/social/userorder/index" method="get">


        <label for="order_sn">订单号：</label>
        <input id="order_sn" type="text" size="31" name="order_sn" value="<?= $order_sn;?>" class="form-control"/>
        <label for="name">用户名：</label>
        <input id="name" type="text" name="mobile" value="<?= $mobile;?>" class="form-control">
        <label for="name">商家名：</label>
        <input id="name" type="text" name="shop_name" value="<?= $shop_name;?>" class="form-control">
        <br /><br />
        <label for="status">订单状态：</label>
        <select id="status" name="status" class="form-control">
            <option  value="999">全部</option>
            <?php foreach($status_data as $k => $v){ ?>
                <option <?php if($k == $status){echo " selected ";}?> value="<?= $k; ?>"><?= $v; ?></option>
            <?php } ?>

        </select>
        <label for="status">支付状态：</label>
        <select id="pay_status" name="pay_status" class="form-control">
            <option  value="999">全部</option>
            <?php foreach($pay_status_data as $k => $v){ ?>
                <option <?php if($k == $pay_status){echo " selected ";}?> value="<?= $k; ?>"><?= $v; ?></option>
            <?php } ?>

        </select>
        <label for="status">支付方式：</label>
        <select id="pay_site_id" name="pay_site_id" class="form-control">
            <option  value="0">全部</option>
            <?php foreach($pay_site_list as $k => $v){ ?>
                <option <?php if($k == $pay_site_id){echo " selected ";}?> value="<?= $k; ?>"><?= $v; ?></option>
            <?php } ?>

        </select>
        <label for="status">发货状态：</label>
        <select id="ship_status" name="ship_status" class="form-control">
            <?php foreach($ship_status_data as $k => $v){ ?>
                <option <?php if($k == $ship_status){echo " selected ";}?> value="<?= $k; ?>"><?= $v; ?></option>
            <?php } ?>

        </select>
        <br /><br />
        <!--        <label for="shop_name">商家名称：</label>-->
        <!--        <input id="shop_name" type="text"  name="shop_name" value="--><?php //if(isset($shop_name)){echo $shop_name; };?><!--" class="form-control">-->
        <label for="start_time">开始时间：</label>
        <input id="start_time" type="text" id="start_time" name="start_time" onFocus="WdatePicker({isShowClear:true,readOnly:false})" value="<?php if(isset($start_time)){echo $start_time; };?>" class="form-control">
        <label for="end_time">结束时间：</label>
        <input id="end_time" type="text" name="end_time" onFocus="WdatePicker({isShowClear:true,readOnly:false})" value="<?php if(isset($end_time)){echo $end_time; };?>" class="form-control">
        <button id="sub" class="btn btn-primary" name="yt0" type="submit">搜索</button>
    </form>
</div>

<legends  style="fond-size:12px;">
    <div class="tab-content">
        <div class="row-fluid">
            <table  class="table table-bordered table-hover">
                <tbody>
                <tr>
                    <th colspan="2">订单ID</th>
                    <th colspan="2">用户名</th>
                    <th colspan="2">商家名称</th>
                    <th colspan="2">订单总额</th>
                    <th colspan="2">优惠金额</th>
                    <th colspan="2">订单状态</th>
                    <th colspan="2">支付状态</th>
                    <th colspan="2">支付方式</th>
                    <th colspan="2">发货状态</th>
                    <th colspan="2">下单时间</th>
                    <th colspan="2">收货人</th>

                    <th colspan="4">操作</th>
                </tr>
                <?php if(empty($data)) {
                    echo '<tr><td colspan="24" style="text-align:center;">暂无记录</td></tr>';
                }else{
                    foreach($data as $item){
                        ?>
                        <tr <?php if(in_array($item['id'],$result_arr)):?>bgcolor="#aaaaaa" <?php endif;?>>
                            <td colspan="2"><a href="<?= '/social/userorder/detail?order_sn='.$item['order_sn'];?>"><?= $item['order_sn'];?></a></td>
                            <td colspan="2"><a href="/social/user/showoneuser?id=<?= $item['mobile']?>" target="_blank"><?= $item['mobile'];?></a></td>
                            <td colspan="2"><?= $item['shop_name'];?></td>
                            <td colspan="2"><?= $item['total'];?></td>
                            <td colspan="2"><?= $item['dis_amount'];?></td>
                            <td colspan="2"><?php echo isset($status_data[$item['status']]) ? $status_data[$item['status']] : ''; ?></td>
                            <td colspan="2"><?php if(isset($pay_status_data[$item['pay_status']])){echo $pay_status_data[$item['pay_status']];}else{echo '未知';};?></td>
                            <td colspan="2">
                                <?php
                                if(isset($pay_site_list[$item['pay_method_id']])){
                                    echo $pay_site_list[$item['pay_method_id']];
                                }else{
                                    echo '未知';};
                                ?></td>
                            <td colspan="2"><?php if(isset($ship_status_data[$item['ship_status']])){ echo $ship_status_data[$item['ship_status']];}else{ echo "未知";};?></td>
                            <td colspan="2"><?= $item['create_time'];?></td>
                            <td colspan="2"><?= $item['consignee'];?></td>

                            <td colspan="4">
                                <a href="<?= '/social/userorder/detail?order_sn='.$item['order_sn'];?>">详情</a>
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