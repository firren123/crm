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
$this->title = '修改用户信息';
?>
<script language="JavaScript">

    //window.onload=func();
    var email=true;
    var mobile=true;
    var balance=true;
    //var balancereg = new RegExp("^-?\d+\.?\d{0,2}$");
    var balancereg = /^\d+(\.\d+)?$/;
    //var mobilereg = new RegExp("^15[8,9]\d{8}$");
    var emailreg = new RegExp("^([0-9a-zA-Z]([-.\w]*[0-9a-zA-Z])*@(([0-9a-zA-Z])+([-\w]*[0-9a-zA-Z])*\.)+[a-zA-Z]{2,9})$");
    //var mobilereg= new RegExp("/^1\d{10}$");
    var mobilereg = /^1\d{10}$/;
    function func(){
        var e=document.getElementById('email');
        regemail(e);
        var m=document.getElementById('mobile');
        regmobile(m);
        var b=document.getElementById('balance');
        regbalance(b);
    }
    function regemail(file){
        //alert(emailreg.test(file.value));
        if(!emailreg.test(file.value)){
            document.getElementById("emaillabel").style.display="inline";
            email=false;
        }else{
            document.getElementById("emaillabel").style.display="none";
            email=true;
        }
        if(file.value==''){document.getElementById("emaillabel").style.display="none";}
    }
    function regmobile(file){
        //alert(emailreg.test(file.value));
        if(!mobilereg.test(file.value)){
            document.getElementById("mobilelabel").style.display="inline";
            mobile=false;
        }else{
            document.getElementById("mobilelabel").style.display="none";
            mobile=true;
        }
        if(file.value==''){document.getElementById("mobilelabel").style.display="none";}
    }
    function regbalance(file){
        //alert(emailreg.test(file.value));
        if(!balancereg.test(file.value)){
            document.getElementById("balancelabel").style.display="inline";
            balance=false;
        }else{
            document.getElementById("balancelabel").style.display="none";
            balance=true;
        }
        if(file.value==''){document.getElementById("balancelabel").style.display="none";}
    }
    function sub(){
        if(!email) {
            alert("邮箱格式有误，无法提交！");
        }else{
            if (!mobile) {
                alert("手机号格式有误，无法提交！");
            }else{
                if(!balance){
                    alert("余额格式有误，无法提交！");
                }else{
                    document.getElementById("login-form").submit().click;
                    }
                }
            }
    }
</script>
<style>
    ul{ }
    li{ float: left; list-style-type: none;}
    td{border: 1px solid #c0c0c0;}
    tr{}
</style>
<body onload="func()">
<div class="tab-content" >
    <ul style="margin-top: 10px; width: 1000px; height: 30px; ">
        <li><a href="../..">首页</a>&nbsp;&nbsp;/&nbsp;&nbsp;</li>
        <li><a href="index">所有用户基本信息</a>&nbsp;&nbsp;/&nbsp;&nbsp;</li>
        <li><a href="showoneuser?id=<?= $list['id']?>">用户个人详细信息</a>&nbsp;&nbsp;/&nbsp;&nbsp;</li>
        <li>修改用户个人详细信息</li>
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
        <?= $form->field($model, 'email')->label('电子邮箱：',['style'=>'width:150px'])->input('text',['style'=>'width:200px;','name'=>'email','value' => $list['email'],'onblur'=>'regemail(this)']) ; ?>
        <label id="emaillabel" style="color: red; display: none;  margin-left: 450px; margin-top: -50px; float: left;">电子邮箱格式不正确！</label>
        <?= $form->field($model, 'mobile')->label('手机号：',['style'=>'width:150px'])->input('text',['style'=>'width:200px;','name'=>'mobile','value' => $list['mobile'],'onblur'=>'regmobile(this)']) ; ?>
        <label id="mobilelabel" style="color: red; display: none; margin-left: 450px; margin-top: -50px; float: left;">手机号格式不正确！</label>
        <label style="margin-left: 72px; margin-right: 30px; margin-bottom: 20px;"><label style="color: red;">*</label>状态：</label>
    <?php if( $list['status']==1) {?>
        <input type="radio" name="status" checked="checked" value="1"/>冻结&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="radio" name="status" value="2"/>激活
    <?php }if( $list['status']==2) { ?>
        <input type="radio" name="status" value="1"/>冻结&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="radio" name="status" checked="checked" value="2"/>激活
    <?php } ?>
    <?= $form->field($model, 'balance')->label('账户余额：',['style'=>'width:150px'])->input('text',['style'=>'width:200px;','name'=>'balance','value' => $list['balance'],'onblur'=>'regbalance(this)']) ; ?>
    <label id="balancelabel" style="color: red; display: none; margin-left: 450px; margin-top: -50px; float: left;">账户余额不正确！</label>
    <div class="form-actions">
        <a class="btn btn-primary" onclick="sub()">确定修改</a>
        <a class="btn cancelBtn" href="javascript:history.go(-1);">返回</a>
    </div>
    <?php ActiveForm::end(); ?>
</div>
</body>