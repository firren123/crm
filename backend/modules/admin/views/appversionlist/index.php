<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/6/3
 * Time: 14:32
 */
use yii\widgets\LinkPager;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = 'app版本列表';
?>
<style>
    table{ border: 1px solid #ddd;}
    th{ text-align: center; padding: 5px 5px 5px 5px;}
    td{ border: 1px solid #ddd; text-align: center; padding: 5px 5px 5px 5px;}
</style>
<legends  style="fond-size:12px;">
    <ul class="breadcrumb">
        <li>
            <a href="/">首页</a>
        </li>
        <li>app版本列表</li>

    </ul>
    <div class="tab-content">
        <div class="row-fluid">
        </div>
    </div>
</legends>
<?php
$form = ActiveForm::begin([
    'id' => "login-form",
    'action' => 'index',
    'method' => 'get',
    'layout' => 'horizontal',
    'enableAjaxValidation' => true,
    'options' => ['enctype' => 'multipart/form-data'],
]);
?>
<div style="clear: both;"></div>
<div style=" float: left; margin-bottom: 20px;">
    <span style=" float: left; margin-top: 5px; margin-left: 10px;">操作系统</span>
    <select name="type" class="form-control" style="float: left; width: 100px; margin-left: 20px;">
        <option value="">请选择</option>
        <option value="0" <?= @$_GET['type']=='0'? 'selected' : '' ;?> >安卓</option>
        <option value="1" <?= @$_GET['type']=='1'? 'selected' : '' ;?> >IOS</option>
    </select>
    <span style=" float: left; margin-top: 5px; margin-left: 40px;">主版本</span>
    <input type="text" name="major" class="form-control" maxlength="10" style="float: left; width: 200px; margin-left: 20px; margin-right: 20px;" value="<?= @$_GET['major'] ?>" onkeyup="regmajor(this)"/>
    <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
    <a class="btn btn-primary" style="margin-left: 20px;" href="new-version">发布新版本</a>
</div>
<?php ActiveForm::end(); ?>
<table style=" width: 100%;">
    <tr>
        <th>操作系统</th>
        <th>所属项目</th>
        <th>版本名称</th>
        <th>主版本</th>
        <th>副版本</th>
        <th>下载地址</th>
        <th>有效性</th>
        <th>操作</th>
    </tr>
<?php
    foreach($result as $k => $v){
        ?>
    <tr>
        <td><?= $v['type']==0? '安卓':'';?><?= $v['type']==1? 'IOS':'';?></td>
        <td>
            <?php
            if($v['subordinate_item']=='0'){
                echo "用户APP";
            }
            if($v['subordinate_item']=='1'){
                echo "商家APP";
            }
            if($v['subordinate_item']=='2'){
                echo "供应商APP";
            }
            if($v['subordinate_item']=='3'){
                echo "店小二APP";
            }
            ?>
        </td>
        <td><?= $v['name']?></td>
        <td><?= $v['major']?></td>
        <td><?= $v['minor']?></td>
        <td><?= $v['url']?></td>
        <td>
            <?php
            if($v['status']=='0'){echo "删除";}
            if($v['status']=='1'){echo "有效";}
            if($v['status']=='2'){echo "禁用";}
            ?>
        </td>
        <td class="text-center" style="width: 100px;">
            <a href="show-one-url?id=<?php echo !empty($v['id']) ? $v['id'] : '0' ;?>" title="编辑">
                <span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>&nbsp;&nbsp; |&nbsp;&nbsp;
            <a href="del-one-url?id=<?php echo !empty($v['id']) ? $v['id'] : '0' ;?>"  title="删除" onClick="if(confirm('确定要删除？'))return true;return false;">
                <span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span></a>
        </td>
    </tr>
    <?php
}
?>
</table>
<div style="float: right;">
    <?= LinkPager::widget(['pagination' => $pages]); ?>
</div>
<script language="javascript">
    var majorreg = /^\d+(\.\d+){0,2}$/;
    function regmajor(file){
        if(!majorreg.test(file.value)){
            file.style.border='1px solid red';
        }else{
            file.style.border='1px solid #CCC';
        }
        if(file.value==''){file.style.border='1px solid #CCC';}
    }
</script>

