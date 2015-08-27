<?php
/**
 * 简介
 * @category  admin
 * @package   资金流水
 * @author     <youyong@iyangpin.com>
 * @time      2015/4/1 10:22
 * @copyright 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com
 * @link      youyong@iyangpin.com
 */
use yii\helpers\Html;
use yii\widgets\LinkPager;
$this->title = "资金流水";
?>
<?= $this->registerJsFile("@web/js/jquery-1.10.2.min.js");?>
<script src="/js/My97DatePicker/WdatePicker.js"></script>
<div class="tab-content">
    <div class="row-fluid">
        <table table align='center' class="table table-bordered table-hover">
            <tr>
                <td colspan="7">
                    <form action="" method="get">
                        <p class="findformbox">
                            <input type="text" id="username" name="username" placeholder="商家名" class="findformtxt" style="float:left;margin-right:10px" />
                            <input type="text" id="start_time" name="start_time" placeholder="开始时间" class="zjs_start_time" onFocus="WdatePicker({isShowClear:true,readOnly:false})" style="float:left;margin-right:10px" />
                            <input type="text"id="end_time" name="end_time" placeholder="结束时间" class="zjs_end_time" onFocus="WdatePicker({isShowClear:true,readOnly:false})" style="float:left;margin-right:10px" />
                            <input type="submit" id="search" value="搜索" class="findformbtn" />
                        </p>
                    </form></td>
            </tr>
            <tr>
                <th>订单号</th><th>订单总额</th><th>用户ID</th><th>所属商家</th><th>状态</th>
            </tr>
                    <?php
                    if(empty($list)){
                        echo '<tr><td colspan="9" style="text-align:center;">暂无记录</td></tr>';
                    }else{
                        foreach($list as $k => $v){?>
            <tr>
            <td><?= html::encode($v['order_sn'])?></td>
                    <td><?= html::encode($v['total']); ?></td>
                    <td>
                        <?= html::encode($v['user_id']);?>
                    </td>

                    <td>
                        <?= $username;?>
                    </td>
                    <td>
                        <?= html::encode($v['ship_status'])=='5'?'订单完成':'';?>
                    </td>
            </tr>
                <?php }?>
            <?php }?>

            <?= LinkPager::widget(['pagination' => $pages]) ?>

        </table>
        <p style="float:right;">共计&nbsp;<span style="color: red;"><?php echo $count;?></span>&nbsp;条记录</p>
    </div>
</div>