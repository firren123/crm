<?php
/**
 * 简介
 * @category  admin
 * @package   意见反馈列表
 * @author     <youyong@iyangpin.com>
 * @time      2015/4/1 10:22
 * @copyright 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com
 * @link      youyong@iyangpin.com
 */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
$this->title = '意见反馈列表';
?>
    <a id="yw0" class="btn btn-primary" style="margin-bottom:20px;">用户意见反馈列表</a>
        <div class="tab-content">
            <div class="fl">
                <p style="margin-left: 10px;">共计&nbsp;<span style="color: red;"><?php echo $number;?></span>&nbsp;条记录</p>
            </div>
            <div class="row-fluid">
                <table class="table table-bordered table-hover">
                    <tbody>
                <tr>
                    <th>ID</th>
                    <th>用户id</th>
                    <th>用户名</th>
                    <th>反馈内容</th>
                    <th>反馈时间</th>
<!--                    <th>审核状态</th>-->
                </tr>
                <?php
                if(empty($item)){

                    echo '<tr><td colspan="4" style="text-align:center;">暂无反馈记录</td></tr>';

                }else{
                    foreach ($item as $list) {
                        ?>
                        <tr>
                            <td><?= $list['id'];?></td>
                            <td><?= $list['user_id'];?></td>
                            <td><?= $list['username'];?></td>
                            <td style="text-align:left;"><?= $list['content'];?></td>
                            <td><?= $list['create_time'];?></td>
<!--                            <td>--><?//= $list['status']==0 ? '待审核' : '通过';?><!--</td>-->
                        </tr>
                    <?php } }?>
                    </tbody>
                </table>
                <?= LinkPager::widget(['pagination' => $pages]); ?>
            </div>
        </div>

