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
    <legend>分公司管理</legend>
    <a id="yw0" class="btn btn-primary" href="/admin/branch/add" style="margin-bottom:20px;">添加分公司</a>
    <div class="tab-content">
        <div class="row-fluid">
            <table class="table table-bordered table-hover">
                <tbody>
                <tr>
                    <th colspan="2">ID</th>
                    <th colspan="2">分公司名称</th>
                    <th colspan="2">省名</th>
                    <th colspan="2">状态</th>
                    <th colspan="2">排序</th>
                    <th colspan="2">操作</th>
                </tr>
                <?php if(empty($list)) {
                    echo '<tr><td colspan="16" style="text-align:center;">暂无记录</td></tr>';
                }else{
                    foreach($list as $item){
                        ?>
                        <tr>
                            <td colspan="2"><?= $item['id'];?></td>
                            <td colspan="2"><?= $item['name'];?></td>
                            <td colspan="2"><?= $item['province_name'];?></td>
                            <td colspan="2"><?= ($item['status']=='1') ? '启用' : '禁用' ;?></td>
                            <td colspan="2"><?= $item['sort'];?></td>
                            <td colspan="4"><a  href="/admin/branch/del?id=<?php echo $item['id']?>">删除</a> | <a  href="/admin/branch/up?id=<?php echo $item['id']?>">修改</a>
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
