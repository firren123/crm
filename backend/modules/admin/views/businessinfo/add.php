<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = '添加业务员';
?>
<style>
    .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td{
        text-align: left;
    }
</style>
    <legends  style="fond-size:12px;">
        <ul class="breadcrumb">
            <li>
                <a href="/">首页</a>
            </li>
            <li><a href="/admin/businessinfo/index">业务员信息</a></li>
            <li class="active">增加业务员</li>
        </ul>
        <div class="tab-content">
            <div class="row-fluid">
            </div>
        </div>
    </legends>

    <form action="/admin/businessinfo/add" id="form" method="post">
        <input type="hidden" id="_csrf" name="_csrf" value="<?php echo \Yii::$app->getRequest()->getCsrfToken(); ?>" />
        <table  class="table table-bordered table-hover">
            <tr>
                <th width="20%"><span class="red">*</span>姓名</th>
                <td colspan="2">
                    <input type="text" id="name" name="name" value=""/>
                </td>
            </tr>
            <tr>
                <th><span class="red">*</span>手机</th>
                <td colspan="2">
                    <input id="mobile" name="mobile" value="" />
                    <a href="javascript:;" id="chec_mobile" >检查手机号</a>
                </td>
            </tr>
            <tr>
                <th><span class="red">*</span>密码</th>
                <td colspan="2">
                    <input type="password" placeholder="格式为：数字+字母6-12位" id="pwd" name="pwd" value="" />
                </td>
            </tr>
            <tr>
                <th width="20%">邮箱</th>
                <td colspan="2"><input id="email" name="email" type="text" value=""/></td>
            </tr>
            <tr>
                <th width="20%"><span class="red">*</span>是否禁用</th>
                <td colspan="2" >
                    <div class="radio">
                        <label><input type="radio" name="status" value="0" > 禁止</label>
                        <label><input type="radio" name="status" value="1" > 正常</label>
                    </div>
                </td>
            </tr>
            <tr>
                <th  width="20%"><span class="red">*</span>分公司</th>
                <td colspan="1">
                    <select id="bc_id" name="bc_id" onchange="go(this.value);">
                        <?php if(!empty($branch_arr)){  foreach ($branch_arr as $k=>$item):?>
                        <option  value="<?=$k?>"><?=$item?></option>
                        <?php endforeach;}?>
                    </select>
                </td>
                <td colspan="1">
                    <select id="groupid" name="groupid" style="display: none">
                    </select>
                </td>
            </tr>
            <tr>
                <th><span class="red">*</span>部门</th>
                <td colspan="2">
                    <select id="deptment_id" name="deptment_id">
                        <?php if(!empty($dept_arr)){  foreach ($dept_arr as $k=>$item):?>
                            <option value="<?=$k?>"><?=$item?></option>
                        <?php endforeach;}?>
                    </select>
                    <input type="hidden" id="deptment_name" name="deptment_name" value="<?=$item?>" />
                </td>
            </tr>
            <tr>
                <th><span class="red">*</span>职务</th>
                <td colspan="2">
                    <div class="radio">
                        <label><input type="radio" name="duty_id" value="0" > 普通业务</label>
                        <label><input type="radio" name="duty_id" value="1" > 区域经理</label>
                        <label><input type="radio" name="duty_id" value="2" > 分公司经理</label>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="text-align:center;">
                    <a href="javascript:;" id="but" class="btn btn-primary">提交</a>
                    <a class="btn cancelBtn" href="javascript:history.go(-1);">取消</a>
                </td>
            </tr>

        </table>
    </form>
<script type="text/javascript">
    $(function(){
        $("#mobile").val(" ");
        $("#pwd").val("");

        $("#chec_mobile").click(function(){
            var mobile = $.trim($("#mobile").val());
            var d = dialog({
                title: '提示',
                okValue: '确定',
                ok: function () {}
            });
            if(mobile == ''){
                content = "电话号码不能为空！！！";
                form.mobile.focus();
                d.content(content);
                d.showModal();
                return false;
            }

            if(mobile.length != 11){
                content = "电话号码不合法！！！";
                form.mobile.focus();
                d.content(content);
                d.show();
                return false;
            }
            //var reg =/^(((1[34578]{1}))+\d{9})$/;
            //var reg =/^(13[0-9]|15[012356789]|17[0678]|18[0-9]|14[57])[0-9]{8}$/;
            var reg =/^1[3|4|5|7|8]\d{9}$/;
            if(!reg.test(mobile))
            {
                content = "您的手机号码不正确，请重新输入";
                form.mobile.focus();
                d.content(content);
                d.show();
                return false;
            }
            $.getJSON('/admin/businessinfo/check', {
                'mobile': mobile,
                'check': 'check_mobile'
            }, function (data) {
                if(data.status == 1){
                    form.mobile.focus();
                    d.content(data.msg);
                    d.showModal();
                    return false;
                }else{
                    d.content(data.msg);
                    d.showModal();
                }
            })
        })

        $("#but").click(function(){
            var content     = "";
            var name        = $.trim($("#name").val());
            var mobile      = $.trim($("#mobile").val());
            var pwd         = $.trim($("#pwd").val());
            var status     = $('input[name="status"]:checked').val();
            var bc_id       = $('select[name="bc_id"]').val();
            var groupid     = $('select[name="groupid"]').val();
            var deptment_id = $('select[name="deptment_id"]').val();
            var duty_id     = $('input[name="duty_id"]:checked').val();
            var day_total   = $("#day_total").val();
            var openshop_total = $("#openshop_total").val();
            var sales_total    = $("#sales_total").val();

            var d = dialog({title:"提示",
                okValue: '确定',
                ok: function () {}
            });
            if(name == ''){
                content = "姓名不能为空！！！";
                form.name.focus();
                d.content(content);
                d.show();
                return false;
            }

            if(mobile == ''){
                content = "电话号码不能为空！！！";
                form.mobile.focus();
                d.content(content);
                d.show();
                return false;
            }
            var reg =/^1[3|4|5|7|8]\d{9}$/;
            if(!reg.test(mobile))
            {
                content = "您的手机号码不正确，请重新输入";
                form.mobile.focus();
                d.content(content);
                d.show();
                return false;
            }

            if(pwd == ''){
                content = "密码不能为空！！！";
                form.pwd.focus();
                d.content(content);
                d.show();
                return false;
            }
            //var paswd = /^[A-Za-z0-9]+$/;
            var paswd = /^(?![a-z]+$|[0-9]+$)^[a-zA-Z0-9]{6,12}$/;
            if(!paswd.test(pwd) || pwd.length<5 || pwd.length>12 ){
                content = "密码格式不正确！！！";
                form.pwd.focus();
                d.content(content);
                d.show();
                return false;
            }
            if(status == '' || status == undefined){
                content = "是否禁用必选！！！";
                d.content(content);
                d.show();
                return false;
            }

            if(bc_id == '0' || bc_id == undefined){
                content = "分公司不能为空！！！";
                d.content(content);
                d.show();
                return false;
            }
            if(deptment_id == '' || deptment_id == undefined){
                content = "部门不能为空！！！";
                d.content(content);
                d.show();
                return false;
            }
            if(duty_id == '' || duty_id == undefined){
                content = "职务不能为空！！！";
                d.content(content);
                d.show();
                return false;
            }
            $('#form').submit();
        });
    });
    function go(id)
    {
        if (id>0) {
            $.getJSON('/admin/businessinfo/linkage', {'id': id}, function (data) {
                var html = "";
                $.each(data,function(k,v){
                    html += "<option value='"+v.id+"'>"+ v.name+"</option>";
                });
                $("#groupid").html(html);
                html != '' ? $("#groupid").css("display","block") : $("#groupid").css("display","none");
            });
        }
    }
    </script>