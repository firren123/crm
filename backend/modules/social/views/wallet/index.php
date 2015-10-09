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
$this->title = "钱包列表";

?>
<script type="text/javascript" src="/js/My97DatePicker/WdatePicker.js"></script>


<div class="wide form">
    <form id="search-form" class="well form-inline" action="/social/wallet/index" method="get">
        <label for="mobile">手机号：</label>
        <input id="mobile" type="text" name="mobile" value="<?= $mobile;?>" class="form-control">

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
                    <th colspan="2">ID</th>
                    <th colspan="2">用户ID</th>
                    <th colspan="2">手机号</th>
                    <th colspan="2">充值余额</th>
                    <th colspan="2">订单收益</th>
                    <th colspan="2">红包</th>
                    <th colspan="2">积分</th>
                    <th colspan="4">操作</th>
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
                            <td colspan="4">
                                <a href="<?= '/social/wallet/detail?id='.$item['id'];?>">详情</a>
                            </td>
                        </tr>
                    <?php } }?>

                </tbody>
            </table>
            <?= LinkPager::widget(['pagination' => $pages]); ?>
        </div>
    </div>

</legends>
