<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = '修改业务员';
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
            <li class="active">修改业务员</li>
        </ul>
        <div class="tab-content">
            <div class="row-fluid">
            </div>
        </div>
    </legends>
    <form action="/admin/businessinfo/edit?id=<?=$data['id']?>" id="form" method="post">
        <input type="hidden" id="_csrf" name="_csrf" value="<?php echo \Yii::$app->getRequest()->getCsrfToken(); ?>" />
        <table  class="table table-bordered table-hover">
            <tr>
                <th width="20%"><span class="red">*</span>姓名</th>
                <td colspan="2">
                    <input type="text" id="name" name="name" value="<?=$data['name']?>"/>
                    <input type="hidden" id="business_id" name="business_id" value="<?=$data['id']?>"/>
                    &nbsp;&nbsp;<a href="javascript:;" id="pwd">修改密码</a>
                </td>
            </tr>
            <tr>
                <th><span class="red">*</span>手机</th>
                <td colspan="2">
                    <input id="mobile" name="mobile" value="<?=$data['mobile']?>" />
                    <a href="javascript:;" id="chec_mobile" >检查手机号</a>
                </td>
            </tr>
            <tr>
                <th width="20%">邮箱</th>
                <td colspan="2"><input id="email" name="email" type="text" value="<?=$data['email']?>"/></td>
            </tr>
            <tr>
                <th width="20%"><span class="red">*</span>是否禁用</th>
                <td colspan="2" >
                    <div class="radio">
                        <label><input type="radio" name="status" value="0" <?php if($data['status'] == 0){echo "checked";}?>> 禁止</label>
                        <label><input type="radio" name="status" value="1" <?php if($data['status'] == 1){echo "checked";}?>> 正常</label>
                    </div>
                </td>
            </tr>
            <tr>
                <th><span class="red">*</span>分公司</th>
                <td colspan="1">
                    <select id="bc_id" name="bc_id" onchange="go(this.value);">
                        <?php if(!empty($branch_arr)){  foreach ($branch_arr as $k=>$item):?>
                            <option value="<?=$k?>"><?=$item?></option>
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
                </td>
            </tr>
            <tr>
                <th><span class="red">*</span>职务</th>
                <td colspan="2">
                    <div class="radio">
                        <label><input type="radio" name="duty_id" value="0" <?php if($data['duty_id'] == 0){echo "checked";}?>> 普通业务</label>
                        <label><input type="radio" name="duty_id" value="1" <?php if($data['duty_id'] == 1){echo "checked";}?>> 区域经理</label>
                        <label><input type="radio" name="duty_id" value="2" <?php if($data['duty_id'] == 2){echo "checked";}?>> 分公司经理</label>
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

<div class="row-fluid">
    <table class="table table-bordered table-hover">
        <tbody>
        <tr>
            <th>ID</th>
            <th>姓名</th>
            <th>状态</th>
            <th>当日拜访目标</th>
            <th>当月开店数</th>
            <th>当月销售目标</th>
        </tr>
        </tbody>
        <?php if(empty($target)) {
            echo '<tr><td colspan="11" style="text-align:center;">暂无记录</td></tr>';
        }else{
            foreach ($target as $item):
                ?>
                <tr>
                    <td><?= $item['id'];?></td>
                    <td><?= $item['name'];?></td>
                    <td><?= $item['status']==1?'正常':'禁用';?></td>
                    <td name="day"><?= $item['day_total'];?></td>
                    <td name="openshop"><?= $item['openshop_total'];?></td>
                    <td name="sales"><?=floor($item['sales_total']);?></td>
                </tr>
            <?php endforeach;
        }
        ?>
    </table>
</div>

<script type="text/javascript">
    $(function(){
        var bckey    = "<?=$bcid?>";
        var groupkey = "<?=$groupid?>";

        if(bckey != '')
        {
            $("#bc_id").val(bckey);
            $.getJSON('/admin/businessinfo/linkage', {'id': bckey}, function (data) {
                var html = "";
                $.each(data,function(k,v){
                    html += "<option value='"+v.id+"'>"+ v.name+"</option>";
                });
                $("#groupid").html(html);
                html != '' ? $("#groupid").css("display","block") : $("#groupid").css("display","none");
                groupkey != '' && groupkey != '0' ? $("#groupid").val(groupkey) : $("#groupid").css("display","none");
            });
        }

        $("#chec_mobile").click(function(){
            var mobile = $("#mobile").val();
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
            var reg =/^(((1[34578]{1}))+\d{9})$/;
            if(!reg.test(mobile))
            {
                content = "您的手机号码不正确，请重新输入";
                form.mobile.focus();
                d.content(content);
                d.showModal();
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
        //提交信息
        $("#but").click(function(){
            var content     = "";
            var name        = $.trim($("#name").val());
            var mobile      = $.trim($("#mobile").val());
            var pwd         = $.trim($("#pwd").val());
            var status      = $('input[name="status"]:checked').val();
            var bc_id       = $('select[name="bc_id"]').val();
            var deptment_id = $('select[name="deptment_id"]').val();
            var groupid     = $('select[name="groupid"]').val();
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
                form.name.focus();
                d.content(content);
                d.show();
                return false;
            }
            var reg =/^1[3|4|5|7|8]\d{9}$/;
            if(!reg.test(mobile))
            {
                content = "您的手机号码不正确，请重新输入";
                form.name.focus();
                d.content(content);
                d.show();
                return false;
            }

            if(status == ''){
                content = "是否禁用必选！！！";
                form.name.focus();
                d.content(content);
                d.show();
                return false;
            }
            if(bc_id == undefined || bc_id == 0){
                content = "分公司不能为空！！！";
                form.name.focus();
                d.content(content);
                d.show();
                return false;
            }
            if(deptment_id == ''){
                content = "部门不能为空！！！";
                form.name.focus();
                d.content(content);
                d.show();
                return false;
            }
            if(duty_id == ''){
                content = "职务不能为空！！！";
                form.name.focus();
                d.content(content);
                d.show();
                return false;
            }

            $('#form').submit();
        });

        //修改密码
        $("#pwd").click(function(){

            var html_one = '';
            html_one += "<table class='waitlist table table-bordered table-hover'>" +
            "<tr><th>输入新密码:</th><td>" +
            "<input type='password' placeholder='格式为：数字+字母6-12位' class='passwd1' value='' />" +
            "</td></tr>" +
            "<tr><th>确认密码:</th><td>" +
            "<input type='password' class='passwd2' value='' />" +
            "</td></tr>" +
            "</table>";
            var id = $("#business_id").val();
                var up = dialog({
                    title: '新密码',
                    content: html_one,
                    okValue: '确定',
                    ok: function () {
                        var passpwd_one = $.trim($(".passwd1").val());
                        var passpwd_two = $.trim($(".passwd2").val());
                        if(passpwd_one !== passpwd_two){
                            var paswd = /^(?![a-z]+$|[0-9]+$)^[a-zA-Z0-9]{6,12}$/;
                            if(!paswd.test(passpwd_one) || passpwd_one.length<5 || passpwd_one.length>12 ){
                                var p = dialog({
                                    title: '新密码',
                                    content: '密码格式不正确！！！',
                                    okValue: '确定',
                                    ok: function () {}
                                })
                                p.show();
                                return false;
                            }
                            var passwdshow = dialog({
                                title: '新密码',
                                content: '两次密码不一致！！！',
                                okValue: '确定',
                                ok: function () {}
                            })
                            passwdshow.showModal();
                            return false;
                        } else {
                            $.getJSON('/admin/businessinfo/password', {
                                'id': id,
                                'pwd': passpwd_one,
                                'updata': 'updata'
                            }, function (data) {
                                var newup = dialog({
                                    title: '新密码',
                                    content: data.msg,
                                    okValue: '确定',
                                    ok: function () {
                                        //window.location.reload();
                                    }
                                })
                                newup.showModal();
                            })
                        }
                    }
                })
                up.showModal();
        })
        go($("#bc_id").val());
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
        }else{
            $("#groupid").css("display","none");
        }
    }
    </script>