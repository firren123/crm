<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/5/18
 * Time: 9:41
 */
use yii\widgets\LinkPager;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = '所有用户基本信息';
date_default_timezone_set("PRC");
?>
<style>
    ul { }
    li{ float: left; list-style-type: none;}
    td{border: 1px solid #DDD;}
    th{border: 1px solid #DDD; text-align: center;}
</style>
<script language="JavaScript">
    function allChecked(field){
        var checkboxes = document.getElementsByClassName("checkbox");
            for(var i=0;i<checkboxes.length;i++) {
                checkboxes[i].checked = field.checked;
            }
    }
    function arrid(x){
        var checkboxes = document.getElementsByClassName("checkbox");
        var arr1=new Array();
        for(var i=0;i<checkboxes.length;i++){
            if(checkboxes[i].checked){
                arr1[i]=checkboxes[i].value;
            }
        }
        if(arr1.length > 0){
            x==1?window.location.href="alllock?arrid="+arr1:window.location.href="allunlock?arrid="+arr1;
            }else{
                alert("请选择要锁定的用户！");
        }
    }
</script>
<div class="tab-content">
    <ul>
        <li><a href="../..">首页</a>&nbsp;&nbsp;/&nbsp;&nbsp;</li>
        <li>用户基本信息</li>
    </ul>
    <div style="clear: both;"></div>
    <div style="margin-top: 20px; margin-bottom: 20px;">
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
            <div style="float: left;">
                用户名：<input type="text" name="username" value="<?= @$_GET['username'] ?>"/>
                <br/>
            </div>
            <div style="float: left; margin-left: 20px;">
                <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
    <table style="width: 100%; height: auto; margin-top: 60px; border: 1px solid #DDD; text-align: center;">
        <tr style='border: 1px solid powderblue; height: 30px; color:#3b97d7;'>
            <th style="width:10px;">
                <input type="checkbox" onclick="allChecked(this)" />全选
            </th>
            <th style="width: 100px;">用户名</th>
            <th style="width: 70px">姓名</th>
            <th style="width: 110px">最后登录IP</th>
            <th style="width: 70px">状态</th>
            <th style="width: 35px">操作</th>
        </tr>
        <?php if(!empty($list)){ ?>
            <?php foreach($list as $k=>$v){ ?>
            <tr>
                <td style="padding-left: 20px;">
                    <input type="checkbox" class="checkbox" value="<?= $v['id'] ?>">
                </td>
                <td><?= $v['username'] ?></td>
                <td><?= $v['name'] ?></td>
                <td><?= $v['login_ip'] ?></td>
                <td>
                    <?php if($v['status']==2){ ?>
                        <label style="color: #3b97d7;">已激活</label>
                    <?php }?>
                    <?php if($v['status']==1){ ?>
                        <label style="color: red; ">已锁定</label>
                    <?php }?>
                </td>
                <td>
                    <a href="showoneuser?id=<?= $v['id'] ?>">详情</a>
                    <?php if($v['status']==2){ ?>
                        <a href="lockoneuser?id=<?= $v['id'] ?>" onClick="if(confirm('确定要锁定？'))return true;return false;" >锁定</a><br/>
                    <?php }?>
                    <?php if($v['status']==1){ ?>
                        <a href="unlockoneuser?id=<?= $v['id'] ?>" onClick="if(confirm('确定要激活？'))return true;return false;" >激活</a><br/>
                    <?php }?>
                    <a href="editoneuser?id=<?= $v['id'] ?>&psw=true">密码重置</a>
                </td>
            </tr>
            <?php } ?>
        <?php }else{ ?>
            <tr><td colspan="14">暂无记录！</td></tr>
        <?php }?>
    </table>
    <div style="float: right;">
        <?= LinkPager::widget(['pagination' => $pages]); ?>
        <div style="float: right; margin-top: 20px; margin-right: 120px;">
            <a class="bulk-actions-btn btn btn-danger btn-small active" href="index" onclick="if(confirm('确定要全部锁定？')) arrid(1) ;return false;">使选中全部锁定</a>
            <a class="bulk-actions-btn btn btn-danger btn-small active" href="index" onclick="if(confirm('确定要全部激活？')) arrid(2) ;return false;">使选中全部激活</a>
        </div>
    </div>
</div>