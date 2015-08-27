<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/5/18
 * Time: 11:02
 */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\ActiveField;
$this->title = '修改用户密码';
?>
<script language="JavaScript">
    var psw='';
    var regpsww=false;
    function regpsw(file){
        psw=file.value;
        if(file.value===document.getElementById("bbb").value){
            document.getElementById("passwordlabel").style.display="none";
            regpsww=true;
        }else{
            document.getElementById("passwordlabel").style.display="block";
            regpsww=false;
        }
        if(file.value==''){document.getElementById("passwordlabel").style.display="none";}
    }
    function regpsws(file){
        if(file.value===psw){
            document.getElementById("passwordlabel").style.display="none";
            regpsww=true;
        }else{
            document.getElementById("passwordlabel").style.display="block";
            regpsww=false;
        }
        if(file.value==''){document.getElementById("passwordlabel").style.display="none";}
    }
    function sub(){
        if(regpsww){
            document.getElementById("login-form").submit().click;
        }else {
            alert("密码有误，请重新填写！");
        }
    }
</script>
<style>
    ul{ }
    li{ float: left; list-style-type: none;}
    td{border: 1px solid #c0c0c0;}
    tr{}
</style>
<div class="tab-content" >
    <ul style="margin-top: 10px; width: 1000px; height: 30px; ">
        <li><a href="../..">首页</a>&nbsp;&nbsp;/&nbsp;&nbsp;</li>
        <li><a href="index">所有用户基本信息</a>&nbsp;&nbsp;/&nbsp;&nbsp;</li>
        <li><a href="showoneuser?id=<?= $list['id']?>">用户个人详细信息</a>&nbsp;&nbsp;/&nbsp;&nbsp;</li>
        <li>重置密码</li>
    </ul>
    <div style="margin-top: 20px;"></div>
    <?php
    $form = ActiveForm::begin([
        'id' => "login-form",
        'action' => 'editoneusermsg',
        'method' => 'post',
        'layout' => 'horizontal',
        'enableAjaxValidation' => false,
        'options' => ['enctype' => 'multipart/form-data'],

    ]);
    ?>
    <input type="hidden" name="editid" value="<?= $list['id'] ?>" />
    <label style="margin-left: 65px; margin-bottom: 50px;">用户名：<?= $list['username'] ?></label>
    <label style="margin-left: 65px; margin-bottom: 50px;">姓名：<?= $list['name'] ?></label>
    <?= $form->field($model, 'password')->label('密码：',['style'=>'width:150px'])->input('password',['style'=>'width:200px;','name'=>'password','onblur'=>'regpsw(this)']) ; ?>
    <label style="color:red; ">*</label><label style="margin-left: 20px;">在次输入密码：</label>
    <input type="password" id="bbb" onblur="regpsws(this)" style=" margin-left: 25px; width: 200px; padding-left: 10px; padding-top: 5px ;padding-bottom: 5px; border: 1px solid #ddd"/>
    <label id="passwordlabel" style="color: red; display: none; margin-right: 440px; margin-top: 10px; float: right;">两次密码不一致！</label>
    <div class="form-actions">
        <a class="btn btn-primary" onclick='sub()'>确定修改</a>
        <a class="btn cancelBtn" href="javascript:history.go(-1);">返回</a>
    </div>
    <?php ActiveForm::end(); ?>
</div>