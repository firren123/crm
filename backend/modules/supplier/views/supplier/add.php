<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/5/13
 * Time: 16:41
 */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = '供应商信息添加';
?>
<script language="JavaScript">
    var email=true;
    var mobile=true;
    var phone=true;
    var QQ=true;
    var account=true;
    //var balancereg = new RegExp("^-?\d+\.?\d{0,2}$");
    //var mobilereg = new RegExp("^15[8,9]\d{8}$");
    var emailreg = new RegExp("^([0-9a-zA-Z]([-.\w]*[0-9a-zA-Z])*@(([0-9a-zA-Z])+([-\w]*[0-9a-zA-Z])*\.)+[a-zA-Z]{2,9})$");
    //var mobilereg= new RegExp("/^1\d{10}$");
    var mobilereg = /^1\d{10}$/;
    //var phonereg = /^(0[0-9]{2,3}\-)?([2-9][0-9]{6,7})$/;
    var phonereg = /^\d{3}-\d{8}|\d{4}-\d{7}$/;
    var QQreg = /^[1-9][0-9]{4,10}$/;
    //window.onload事件
    function func(){
        //清除账号 密码信息
        document.getElementById('account').value='';
        document.getElementById('password').value='';
        //验证邮箱 手机号 电话 qq 的格式
        var e=document.getElementById('email');
        regemail(e);
        var m=document.getElementById('mobile');
        regmobile(m);
        var p=document.getElementById('phone');
        regphone(p);
        var q=document.getElementById('qq');
        regqq(q);
    }
    //验证email格式
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
    //验证手机号格式
//    function regmobile(file){
//        //alert(emailreg.test(file.value));
//        if(!mobilereg.test(file.value)){
//            document.getElementById("mobilelabel").style.display="inline";
//            mobile=false;
//        }else{
//            document.getElementById("mobilelabel").style.display="none";
//            mobile=true;
//        }
//    }
    //验证固定电话格式
    function regphone(file){
        if(file.value.length==12) {
            if (!phonereg.test(file.value)) {
                document.getElementById("phonelabels").style.display = "inline";
                phone = false;
            } else {
                document.getElementById("phonelabels").style.display = "none";
                phone = true;
            }
        }else{
            document.getElementById("phonelabels").style.display = "inline";
            phone = false;
        }
        if(file.value==''){document.getElementById("phonelabels").style.display="none";}
    }
    //验证QQ格式
    function regqq(file){
        //alert(emailreg.test(file.value));
        if(!QQreg.test(file.value)){
            document.getElementById("qqlabel").style.display="inline";
            QQ=false;
        }else{
            document.getElementById("qqlabel").style.display="none";
            QQ=true;
        }
        if(file.value==''){document.getElementById("qqlabel").style.display="none";}
    }
    //验证账号
    function regaccount(file){
        var accountmsg=file.value;
            $.ajax(
                {
                    type: "GET",
                    url: '/supplier/supplier/getajax',
                    data: {'account':accountmsg},
                    asynic: false,
                    dataType: "json",
                    beforeSend: function () {
                    },
                    success: function (result) {
                        if(result==1){
                            document.getElementById("accountlabel").style.display="none";
                            account=true;
                        }else{
                            document.getElementById("accountlabel").style.display="inline";
                            account=false;
                        }
                    }
                }
            );
        if(file.value==''){document.getElementById("accountlabel").style.display="none";}
    }

    //提交动作
    function sub(){
        if(!account) {
            alert("账号不可用，无法提交！");
        }else{
            if(!email) {
                alert("邮箱格式有误，无法提交！");
            }else{
                if(!phone){
                    alert("固定电话格式有误，无法提交！");
                }else{
                    if(!QQ){
                        alert("QQ格式有误，无法提交！");
                    }else{
                        document.getElementById("login-form").submit().click;
                    }
                }
            }
        }
    }

</script>
<body onload="func()">
<h3><b>供应商信息添加</b></h3>
<?php
$form = ActiveForm::begin([
    'id' => "login-form",
    'action' => "addgoods",
    'method'=>'post',
    'layout' => 'horizontal',
    'enableAjaxValidation' => false,
    'options' => ['enctype' => 'multipart/form-data'],
]);
?>
<?php

?>
<?= $form->field($model, 'company_name')->label('公司名称：')->input('text',['style'=>'width:200px','name'=>'company_name','maxlength'=>'50']) ; ?>
<?= $form->field($model, 'account')->label('账号：')->input('text',['style'=>'width:200px','name'=>'account','value' =>'','onblur'=> 'regaccount(this)']) ; ?>
    <label id="accountlabel" style="color: red; display: none;  margin-left: 450px; margin-top: -50px; float: left;">用户名已存在！</label>
<?= $form->field($model, 'password')->label('密码：')->passwordInput(['style'=>'width:200px','name'=>'password','value' =>'']) ; ?>
<?= $form->field($model, 'contact')->label('联系人：')->input('text',['style'=>'width:200px','name'=>'contact']) ; ?>
    <label style="margin-left: 60px; margin-right: 30px; margin-bottom: 20px;"><label style="color:red;">*&nbsp;</label>性别：</label>
    <input type="radio" name="sex" checked="checked" value="1"/>男&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="radio" name="sex" value="2"/>女
<?= $form->field($model, 'email')->label('电子邮件：')->input('text',['style'=>'width:200px','name'=>'email','onblur'=>'regemail(this)']) ; ?>
    <label id="emaillabel" style="color: red; display: none;  margin-left: 450px; margin-top: -50px; float: left;">电子邮箱格式不正确！</label>
<?= $form->field($model, 'mobile')->label('手机号：')->input('text',['style'=>'width:200px','name'=>'mobile','onblur'=>'regmobile(this)']) ; ?>
    <label id="mobilelabel" style="color: red; display: none;  margin-left: 450px; margin-top: -50px; float: left;">手机号格式不正确！</label>
<?= $form->field($model, 'phone')->label('电话：')->input('text',['style'=>'width:200px','name'=>'phone','placeholder'=>"区号请用“-”分开",'onblur'=>'regphone(this)']) ; ?>
<label id="phonelabels" style="color: red; display: none;  margin-left: 450px; margin-top: -50px; float: left;">固定电话格式不正确！</label>
<?= $form->field($model, 'qq')->label('QQ：')->input('text',['style'=>'width:200px','name'=>'qq','onblur'=>'regqq(this)']) ; ?>
    <label id="qqlabel" style="color: red; display: none;  margin-left: 450px; margin-top: -50px; float: left;">QQ格式不正确！</label>
<div class="form-actions">
    <a class="btn btn-primary" onclick="sub()">提交</a>
    <a class="btn cancelBtn" href="javascript:history.go(-1);">取消</a>
</div>
<?php ActiveForm::end(); ?>
</body>
