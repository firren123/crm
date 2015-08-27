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
    <legend>敏感词管理</legend>
    <a id="yw0" class="btn btn-primary" href="/admin/sensitivekeywords/add" style="margin-bottom:20px;">添加敏感词</a>
    <div class="tab-content">
        <div class="row-fluid">
            <table class="table table-bordered table-hover">
                <tbody>
                <tr>
                    <th colspan="2">ID</th>
                    <th colspan="2">敏感词</th>
                    <th colspan="2">状态</th>
                    <th colspan="3">操作</th>
                </tr>
                <?php if(empty($info)) {
                    echo '<tr><td colspan="24" style="text-align:center;">暂无记录</td></tr>';
                }else{
                    foreach($info as $item){
                        ?>
                        <tr>
                            <td colspan="2"><?= $item['id'];?></td>
                            <td colspan="2"><?= $item['keyword'];?></td>
                            <td colspan="2"><?= ($item['status']=='0') ? '启用' : '禁用' ;?></td>
                            <td colspan="3"><a onclick="return confirm('确认删除吗？');" href="/admin/sensitivekeywords/del?id=<?php echo $item['id'];?>">删除敏感词</a>
                                | <a  href="javascript:upStatus(<?= $item['id'];?>);">修改状态</a></td>
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
<!--<input id="token" type="hidden" name="YII_CSRF_TOKEN" value="--><?php //echo Yii::app()->request->csrfToken; ?><!--" />-->
<input type="hidden"  id="token" name="_csrf" value="<?= \Yii::$app->getRequest()->getCsrfToken(); ?>" />
