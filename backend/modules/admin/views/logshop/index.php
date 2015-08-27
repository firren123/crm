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
<?php $this->title='商家日志';?>
<legends  style="fond-size:12px;">
    <legend>商家日志</legend>
    <form action="/admin/logshop/index" method="get">
        类型:<select name="log_type">
            <option value="" style="width: 150px;">请选择</option>
            <option value="1" style="width: 150px;" <?php if($log_type == 1){echo 'selected';}?>>商品</option>
            <option value="2" style="width: 150px;" <?php if($log_type == 2){echo 'selected';}?>>资金</option>
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
                    <th colspan="2">商家ID</th>
                    <th colspan="2">操作时间</th>
                    <th colspan="4">操作日志</th>
                    <th colspan="2">ip地址</th>
                    <th colspan="2">浏览器</th>
                    <th colspan="2">操作系统</th>
                    <th colspan="2">类型</th>
                </tr>
                <?php if(empty($shop_info)) {
                    echo '<tr><td colspan="18" style="text-align:center;">暂无记录</td></tr>';
                }else{
                    foreach($shop_info as $item){
                        ?>
                        <tr>
                            <td colspan="2"><?= $item['id'];?></td>
                            <td colspan="2"><?= $item['shop_id'];?></td>
                            <td colspan="2"><?= $item['log_time'];?></td>
                            <td colspan="4"><?= $item['log_info'];?></td>
                            <td colspan="2"><?= $item['ip_address'];?></td>
                            <td colspan="2"><?= $item['browser'];?></td>
                            <td colspan="2"><?= $item['os'];?></td>
                            <td colspan="2"><?= ($item['log_type']==1)?'商品':'资金';?></td>
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

