<?php
/* @var $this SiteController */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
?>
<?php
/* @var $this OrderController */
/* @var $dataProvider CActiveDataProvider */
?>
<?php $this->title='管理员日志';?>
<legends  style="fond-size:12px;">
    <legend>管理员日志</legend>
    <form action="/admin/log/index" method="get">
        日志类型:<select name="log_type">
            <option value="" style="width: 150px;">请选择</option>
            <option value="1" style="width: 150px;" <?php if($log_type == 1){echo 'selected';}?>>商品</option>
            <option value="2" style="width: 150px;" <?php if($log_type == 2){echo 'selected';}?>>商家</option>
            <option value="3" style="width: 150px;" <?php if($log_type == 3){echo 'selected';}?>>小区</option>
            <option value="4" style="width: 150px;" <?php if($log_type == 4){echo 'selected';}?>>活动</option>
            <option value="5" style="width: 150px;" <?php if($log_type == 5){echo 'selected';}?>>用户</option>
            <option value="6" style="width: 150px;" <?php if($log_type == 6){echo 'selected';}?>>优惠劵</option>
            <option value="7" style="width: 150px;" <?php if($log_type == 7){echo 'selected';}?>>资讯</option>
            <option value="8" style="width: 150px;" <?php if($log_type == 8){echo 'selected';}?>>库房</option>
            <option value="9" style="width: 150px;" <?php if($log_type == 9){echo 'selected';}?>>财务</option>
            <option value="10" style="width: 150px;" <?php if($log_type == 10){echo 'selected';}?>>权限</option>
            <option value="11" style="width: 150px;" <?php if($log_type == 11){echo 'selected';}?>>网站配置</option>
        </select>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit"  class="btn btn-primary" value="搜索">
        <input type="hidden"  name="_csrf" value="<?= \Yii::$app->getRequest()->getCsrfToken(); ?>" />
    </form>
    <div class="tab-content">
        <div class="row-fluid">
            <table class="table table-bordered table-hover">
                <tbody>
                <tr>
                    <th colspan="2">ID</th>
                    <th colspan="2">管理员ID</th>
                    <th colspan="4">日志记录</th>
                    <th colspan="2">操作时间</th>
                    <th colspan="2">ip地址</th>
                    <th colspan="2">浏览器</th>
                    <th colspan="2">操作系统</th>
                    <th colspan="2">日志类型</th>
                </tr>
                <?php if(empty($shop_info)) {
                    echo '<tr><td colspan="18" style="text-align:center;">暂无记录</td></tr>';
                }else{
                    foreach($shop_info as $item){
                        ?>
                        <tr>
                            <td colspan="2"><?= $item['id'];?></td>
                            <td colspan="2"><?= $item['admin_id'];?></td>
                            <td colspan="4" style="text-align: left;"><?= $item['log_info'];?></td>
                            <td colspan="2"><?= $item['add_time'];?></td>
                            <td colspan="2"><?= $item['ip_address'];?></td>
                            <td colspan="2"><?= $item['browser'];?></td>
                            <td colspan="2"><?= $item['os'];?></td>
                            <?php if($item['log_type']==10){?>
                                <td colspan="2">权限</td>
                            <?php }elseif($item['log_type']==9){?>
                                <td colspan="2">财务</td>
                            <?php }elseif($item['log_type']==8){?>
                                <td colspan="2">库房</td>
                            <?php }elseif($item['log_type']==7){?>
                                <td colspan="2">资讯</td>
                            <?php }elseif($item['log_type']==6){?>
                                <td colspan="2">优惠劵</td>
                            <?php }elseif($item['log_type']==5){?>
                                <td colspan="2">用户</td>
                            <?php }elseif($item['log_type']==4){?>
                                <td colspan="2">活动</td>
                            <?php }elseif($item['log_type']==3){?>
                                <td colspan="2">小区</td>
                            <?php }elseif($item['log_type']==2){?>
                                <td colspan="2">商家</td>
                            <?php }else{?>
                                <td colspan="2">商品</td>
                            <?php }?>
                        </tr>
                    <?php } }?>
                </tbody>
            </table>
            <?php echo LinkPager::widget(['pagination' => $pages]); ?>
        </div>
    </div>
</legends>
<?= $this->registerJsFile("@web/js/jquery-1.10.2.min.js");?>
<?= $this->registerJsFile("@web/js/opencity.js");?>

