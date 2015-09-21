<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/5/13
 * Time: 16:41
 */
//var_dump($list);
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title ='供应商信息修改';
?>
<script language="JavaScript">
    var email=true;
    var mobile=true;
    var phone=true;
    var QQ=true;
    var account=true;
    var accounteditmsg='';
    //var balancereg = new RegExp("^-?\d+\.?\d{0,2}$");
    //var mobilereg = new RegExp("^15[8,9]\d{8}$");
    var emailreg = new RegExp("^([0-9a-zA-Z]([-.\w]*[0-9a-zA-Z])*@(([0-9a-zA-Z])+([-\w]*[0-9a-zA-Z])*\.)+[a-zA-Z]{2,9})$");
    //var mobilereg= new RegExp("/^1\d{10}$");
    var mobilereg = /^1\d{10}$/;
    //var phonereg = /^[1-9][0-9]{7}$/;
    var phonereg = /^\d{3}-\d{8}|\d{4}-\d{7}$/;
    var QQreg = /^[1-9][0-9]{4,10}$/;
    //window.onload事件
    function func(){
        accounteditmsg=document.getElementById("supplier-account").value;
        var e=document.getElementById('supplier-email');
        regemail(e);
//        var m=document.getElementById('supplier-mobile');
//        regmobile(m);
        var p=document.getElementById('supplier-phone');
        regphone(p);
        var q=document.getElementById('supplier-qq');
        regqq(q);
    }
    //验证email格式
    function regemail(file){
        //alert(emailreg.test(file.value));
        if(!emailreg.test(file.value)){
            document.getElementById("emaillabel").style.display="block";
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
        //alert(emailreg.test(file.value));
        if(!phonereg.test(file.value)){
            document.getElementById("phonelabel").style.display="block";
            phone=false;
        }else{
            document.getElementById("phonelabel").style.display="none";
            phone=true;
        }
        if(file.value==''){document.getElementById("phonelabel").style.display="none";}
    }
    //验证QQ格式
    function regqq(file){
        //alert(emailreg.test(file.value));
        if(!QQreg.test(file.value)){
            document.getElementById("qqlabel").style.display="block";
            QQ=false;
        }else{
            document.getElementById("qqlabel").style.display="none";
            QQ=true;
        }
        if(file.value==''){document.getElementById("qqlabel").style.display="none";}
    }
    //验证账号
    function regaccount(file){
        if(accounteditmsg===file.value){
            document.getElementById("accountlabel").style.display="none";
            account=true;
        }else{
            $.ajax(
                {
                    type: "GET",
                    url: '/supplier/supplier/getajax',
                    data: {'account':file.value},
                    asynic: false,
                    dataType: "json",
                    beforeSend: function () {
                    },
                    success: function (result) {
                        if(result==1){
                            document.getElementById("accountlabel").style.display="none";
                            account=true;
                        }else{
                            document.getElementById("accountlabel").style.display="block";
                            account=false;
                        }
                    }
                }
            );
        }
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
<style>
    ul{ float: left;}
    li{ float: left; list-style-type: none;}
</style>
<body onload="func()">
<span style=" float: left; width: 500px; margin-left: 30px;">
    <ul>
        <li><a href="../..">首页</a>&nbsp;&nbsp;/&nbsp;&nbsp;</li>
        <li><a href="index">所有供应商基本信息</a>&nbsp;&nbsp;/&nbsp;&nbsp;</li>
        <li><a href="javascript:history.go(-1);">供应商详细信息</a>&nbsp;&nbsp;/&nbsp;&nbsp;</li>
        <li>供应商信息修改</li>
    </ul>
<?php
//FORM 开始和结束必须在 span标签之外！！！！！！！！！
$form = ActiveForm::begin([
    'id' => "login-form",
    'action' => "editgoods",
    'method'=>'post',
    'layout' => 'horizontal',
    'enableAjaxValidation' => false,
    'options' => ['enctype' => 'multipart/form-data'],
]);
?>
<div style=" float: left; width: 900px; height: 800px; text-align: left; margin-top: 20px;">
<input type="hidden" name="editid" value="<?= $editid ?>" />
<?= $form->field($model, 'company_name')->label('公司名称：',['style'=>'width:150px'])->input('text',['style'=>'width:200px','name'=>'company_name','value' => $list[0]['company_name']]) ; ?>
<?= $form->field($model, 'account')->label('账号：',['style'=>'width:150px'])->input('text',['style'=>'width:200px','name'=>'account','value' => $list[0]['account'],'onblur'=>'regaccount(this)']) ; ?>
    <label id="accountlabel" style="color: red; display: none;  margin-left: 450px; margin-top: -50px; float: left;">用户名已存在！</label>
<?= $form->field($model, 'password')->label('密码：',['style'=>'width:150px'])->passwordInput(['style'=>'width:200px','name'=>'password','value' => $list[0]['password']]) ; ?>
<?= $form->field($model, 'contact')->label('联系人：',['style'=>'width:150px'])->input('text',['style'=>'width:200px','name'=>'contact','value' => $list[0]['contact']]) ; ?>
    <label style="margin-left: 80px; margin-right: 30px; margin-bottom: 20px;">性别：</label>
    <?php if( $list[0]['sex']==1) {?>
        <input type="radio" name="sex" checked="checked" value="1"/>男&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="radio" name="sex" value="2"/>女
    <?php }if( $list[0]['sex']==2) { ?>
        <input type="radio" name="sex" value="1"/>男&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <input type="radio" name="sex" checked="checked" value="2"/>女
    <?php } ?>
    <?= $form->field($model, 'email')->label('电子邮件：',['style'=>'width:150px'])->input('text',['style'=>'width:200px','name'=>'email','value' => $list[0]['email'],'onblur'=>'regemail(this)']) ; ?>
    <label id="emaillabel" style="color: red; display: none;  margin-left: 450px; margin-top: -50px; float: left;">电子邮箱格式不正确！</label>
<?= $form->field($model, 'mobile')->label('手机号：',['style'=>'width:150px'])->input('text',['style'=>'width:200px','name'=>'mobile','value' => $list[0]['mobile']]) ; ?>
    <label id="mobilelabel" style="color: red; display: none;  margin-left: 450px; margin-top: -50px; float: left;">手机号格式不正确！</label>
<?= $form->field($model, 'phone')->label('固定电话：',['style'=>'width:150px'])->input('text',['style'=>'width:200px','name'=>'phone','value' => $list[0]['phone'],'onblur'=>'regphone(this)']) ; ?>
    <label id="phonelabel" style="color: red; display: none;  margin-left: 450px; margin-top: -50px; float: left;">固定电话格式不正确！</label>
<?= $form->field($model, 'qq')->label('QQ：',['style'=>'width:150px'])->input('text',['style'=>'width:200px','name'=>'qq','value' => $list[0]['qq'],'onblur'=>'regqq(this)']) ; ?>
    <label id="qqlabel" style="color: red; display: none;  margin-left: 450px; margin-top: -50px; float: left;">QQ格式不正确！</label>

<div class="form-actions">
    <a class="btn btn-primary" onclick="sub()">提交</a>
    <a class="btn cancelBtn" href="javascript:history.go(-1);">取消</a>
</div>
<?php ActiveForm::end(); ?>
</div>
</span>

</body>