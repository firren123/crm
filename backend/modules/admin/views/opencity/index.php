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
<legends  style="fond-size:12px;">
    <legend>已开通城市管理</legend>
    <a id="yw0" class="btn btn-primary" href="/admin/opencity/add" style="margin-bottom:20px;">添加开通城市</a>
    <form action="/admin/opencity/index" method="get">
        城市名称：<input type="text" name="city">
        <input type="submit" class="btn btn-primary" value="搜索">
        <input type="hidden"  name="_csrf" value="<?= \Yii::$app->getRequest()->getCsrfToken(); ?>" />
    </form>
    <div class="tab-content">
        <div class="row-fluid">
            <table class="table table-bordered table-hover">
                <tbody>
                <tr>
                    <th colspan="2">ID</th>
                    <th colspan="2">城市名称</th>
                    <th colspan="2">状态</th>
                    <th colspan="2">开通时间</th>
                    <th colspan="2">城市拼音</th>
                    <th colspan="2">操作</th>
                </tr>
                <?php if(empty($list)) {
                    echo '<tr><td colspan="12" style="text-align:center;">暂无记录</td></tr>';
                }else{
                    foreach($list as $item){
                        ?>
                        <tr>
                            <td colspan="2"><?= $item['id'];?></td>
                            <td colspan="2"><?= $item['city_name'];?></td>
                            <td colspan="2"><?= ($item['status']=='1') ? '启用' : '禁用' ;?></td>
                            <td colspan="2"><?= $item['create_time'];?></td>
                            <td colspan="2"><?= $item['pinyin'];?></td>
                            <td colspan="4">
                                <a  href="javascript:updateStatus(<?= $item['id'];?>);">修改状态</a> | <a  href="/admin/opencity/edit?id=<?php echo $item['id'];?>">编辑</a>
                            </td>
                        </tr>
                    <?php } }?>
                </tbody>
            </table>
            <?= LinkPager::widget(['pagination' => $pages]); ?>
        </div>
    </div>
</legends>
<?= $this->registerJsFile("@web/js/jquery-1.10.2.min.js");?>
<?= $this->registerJsFile("@web/js/opencity.js");?>
