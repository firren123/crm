<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/6/4
 * Time: 10:26
 */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = '发布APP版本';
?>
<body onload="">
<legends  style="fond-size:12px;">
    <ul class="breadcrumb">
        <li>
            <a href="/">首页</a>
        </li>
        <li><a href="/admin/appversionlist/index">app版本列表</a></li>
        <li class="active">修改app版本</li>
    </ul>
    <div class="tab-content">
        <div class="row-fluid">
        </div>
    </div>
</legends>
<?php
$form = ActiveForm::begin([
    'id' => "login-form",
    'action' => "editapp",
    'method'=>'post',
    'layout' => 'horizontal',
    'enableAjaxValidation' => false,
    'enableClientValidation' => true,
    'options' => ['enctype' => 'multipart/form-data'],
]);
?>
<input type="hidden" value="<?=$AppVersionList_result['id'] ?>" name="id"/>
<?= $form->field($AppVersionList_model, 'name')->label('版本名称：',['style'=>'width:180px'])
    ->input('text',['style'=>'width:200px','maxlength'=>'50','onkeyup'=>'checkreg(this)','value'=> $AppVersionList_result['name']]) ; ?>
<label id="namelabel" style="color: red; display: none;  margin-left: 450px; margin-top: -50px; float: left;">版本名称已存在！</label>
<?= $form->field($AppVersionList_model,'subordinate_item')->label('所属项目：',['style'=>'width:180px'])
    ->dropDownList(array('unknown'=>'请选择项目','0'=>'用户APP','1'=>'商家APP','2'=>'供应商APP','3'=>'店小二APP'),['style'=>'width:200px','onblur'=>'checksubordinate_item(this)']) ;?>
<?= $form->field($AppVersionList_model, 'major')->label('主版本号：',['style'=>'width:180px'])
    ->input('text',['style'=>'width:200px','onblur'=>'checkmajor(this)','value'=> $AppVersionList_result['major']]) ; ?>
<?= $form->field($AppVersionList_model, 'minor')->label('副版本号：',['style'=>'width:180px'])
    ->input('text',['style'=>'width:200px','value'=> $AppVersionList_result['minor']]) ; ?>
<?= $form->field($AppVersionList_model,'type')->label('操作系统：',['style'=>'width:180px'])
    ->dropDownList(array('unknown'=>'请选择操作系统','0'=>'安卓','1'=>'IOS'),['style'=>'width:200px','onchange'=>'changetype(this)']) ;?>
<input type="hidden" value="<?= $AppVersionList_result['type']?>" name="types"/>
<?= $form->field($AppVersionList_model, 'url')->label('下载地址：',['style'=>'width:180px'])
    ->input('text',['style'=>'width:400px','onblur'=>'checkurl(this)','value'=> $AppVersionList_result['url']]) ; ?>
<div id="downloadtype" style="display: none;">
    <label id="abaidu" style=" margin-left: 16px; margin-top: 20px; float: left;"><label style="color: red;">*&nbsp;</label>安卓百度下载渠道：</label>
    <input type="text" name="abaidu" class="form-control" onblur="checkabaidu(this)" value='<?=@$app_channel_result['baidu'] ?>' style="width: 400px; float: left; margin-left: 29px; margin-top: 10px;" />
    <div style="clear: both;"></div>
    <label id="a360" style=" margin-left: 20px; margin-top: 30px; margin-bottom: 30px; float: left;"><label style="color: red;">*&nbsp;</label>安卓360下载渠道：</label>
    <input type="text" name="a360" class="form-control" onblur="checka360(this)" value='<?=@$app_channel_result['360'] ?>' style="width: 400px; float: left; margin-left: 29px; margin-top: 30px; margin-bottom: 30px;" />
    <div style="clear: both;"></div>
</div>
<?= $form->field($AppVersionList_model, 'explain')->label('升级提示：',['style'=>'width:180px'])
    ->textarea(['size'=>60,'maxlength'=>128 ,'onblur'=>'checkexplain(this)'])?>
<?= $form->field($AppVersionList_model, 'status')->label('有效性：',['style'=>'width:180px'])
    ->radioList(['0'=>'删除','1'=>'生效','2'=>'禁用'],['template'=>'<span class="radio">{input}{label}</span>','separator'=>''],['onblur'=>'checkstatus(this)']) ; ?>
<div class="form-actions">
    <a class="btn btn-primary" onclick="check()">保存</a>
    <a class="btn cancelBtn" href="javascript:history.go(-1);">取消</a>
</div>
</body>
<?php ActiveForm::end(); ?>
<script language="javascript">
    var appversionlist_name = '';
    var names = false;
    var subordinate_item = false;
    var major = false;
    var type = false;
    var url = false;
    var x = true;
    var y = true;
    var explain = false;
    var status = false;
    window.onload = function(){
        if(document.getElementById('appversionlist-name').value!=''){names = true;}
        if(document.getElementById('appversionlist-subordinate_item').value!=''){subordinate_item = true;}
        if(document.getElementById('appversionlist-major').value!=''){major = true;}
        if(document.getElementById('appversionlist-type').value!=''){type = true;}
        if(document.getElementById('appversionlist-url').value!=''){url = true;}
        if(document.getElementById('appversionlist-explain').value!=''){explain = true;}
        if(document.getElementById('appversionlist-status').value!=''){status = true;}
        appversionlist_name = document.getElementById('appversionlist-name').value;
        if(document.getElementById('appversionlist-type').value=='0'){
            document.getElementById('downloadtype').style.display = 'block';
        }else{
            document.getElementById('downloadtype').style.display = 'none';
        }
    }
    function check(){
        //alert(name);
        if(!names){
            alert("版本名称有误！");
        }else{
            if(!subordinate_item){
                alert("所属项目不能为空！");
            }else{
                if(!major){
                    alert("主版本号不能为空！");
                }else{
                    if(!type){
                        alert("请选择操作系统！");
                    }else{
                        if(!url){
                            alert("下载地址不能为空！");
                        }else{
                            if(!x){
                                alert("安卓百度下载渠道不能为空！");
                            }else{
                                if(!y){
                                    alert("安卓360下载渠道不能为空！")
                                }else{
                                    if(!explain){
                                        alert("升级提示不能为空！")
                                    }else{
                                        if(!status){
                                            alert("状态不能为空！")
                                        }else{
                                            document.getElementById("login-form").submit().click;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        //document.getElementById("login-form").submit().click;
        //alert(document.getElementsByClassName('btn btn-primary').item(0).submit());
    }
    function checkreg(file){
        if(file.value!=''){
            if(appversionlist_name == file.value){
                document.getElementById("namelabel").style.display="none";
                names = true;
            }else{
                $.ajax(
                    {
                        type: "GET",
                        url: '/admin/appversionlist/getnameajax',
                        data: {'namea':file.value},
                        asynic: false,
                        dataType: "json",
                        beforeSend: function () {
                        },
                        success: function (result) {
                            if(result==1){
                                document.getElementById("namelabel").style.display="none";
                                names = true;
                            }else{
                                document.getElementById("namelabel").style.display="block";
                                names = false;
                            }
                        }
                    }
                );
            }
        }else{
            names = false;
            document.getElementById("namelabel").style.display="none";
        }
    }
    function checksubordinate_item(file){
        if(file.value=='unknown'){
            subordinate_item = false;
        }else{
            subordinate_item = true;
        }
    }
    function checkmajor(file){
        if(file.value==''){
            major = false;
        }else{
            major = true;
        }
    }
    function checkurl(file){
        if(file.value==''){
            url = false;
        }else{
            url = true;
        }
    }
    function checkexplain(file){
        if(file.value==''){
            explain = false;
        }else{
            explain = true;
        }
    }
    function checkstatus(file){
        if(file.value==''){
            status = false;
        }else{
            status = true;
        }
    }
    function changetype(file){
        if(file.value==0){
            x = false;
            y = false;
            type = true;
            document.getElementById('downloadtype').style.display = 'block';
        }else{
            x = true;
            y = true;
            type = true;
            document.getElementById('downloadtype').style.display = 'none';
            if(file.value=='unknown'){
                type = false;
            }
        }
    }
    function checkabaidu(file){
        if(file.value==''){
            x = false;
            document.getElementById('abaidu').style.color = '#A94442';
            file.style.borderColor = "#A94442";
        }else{
            x = true;
            document.getElementById('abaidu').style.color = '#3C763D';
            file.style.borderColor = "#3C763D";
        }
    }
    function checka360(file){
        if(file.value==''){
            y = false;
            document.getElementById('a360').style.color = '#A94442';
            file.style.borderColor = "#A94442";
        }else{
            y = true;
            document.getElementById('a360').style.color = '#3C763D';
            file.style.borderColor = "#3C763D";
        }
    }
</script>