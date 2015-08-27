<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = '添加协议';
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
        <li><a href="/shop/cupboardagreement/index">协议信息管理</a></li>
        <li class="active">增加协议</li>
    </ul>
    <div class="tab-content">
        <div class="row-fluid">
        </div>
    </div>
</legends>

<form action="/shop/cupboardagreement/add" id="form" method="post">
    <input type="hidden" id="_csrf" name="_csrf" value="<?php echo \Yii::$app->getRequest()->getCsrfToken(); ?>" />
    <table  class="table table-bordered table-hover">
        <tr>
            <th width="20%"><span class="red">*</span>协议编号</th>
            <td colspan="2"><input type="text" id="sn" name="sn" value="<?= $sn ;?>"/></td>
        </tr>
        <tr>
            <th><span class="red">*</span>商家名称</th>
            <td colspan="2">
                <input type="text" id="shop_name" name="shop_name" value="" />
                <input type="hidden" id="shop_id" name="shop_id" value="" />
                <a onclick="getcupinfo();">　　验证</a>　　　(查看该商家是否有展位信息)
            </td>
        </tr>
        <tr>
            <th><span class="red">*</span>展位名称</th>
            <td colspan="2">
                <input type="hidden" id="title" name="title" value="" />
                <div id="showcup">请先验证商家名称</div>
                <select id="cupboardid" name="cupboardid" style="display: none">
                </select>
            </td>
        </tr>
        <tr>
            <th width="20%"><span class="red">*</span>展位收费类型</th>
            <td colspan="2" >
                <div class="radio">
                    <label><input type="radio" name="type" value="1" checked > 按天</label>
                    <label><input type="radio" name="type" value="2" > 按卖出数量</label>
                </div>
            </td>
        </tr>
        <tr>
            <th>展位租用金额</th>
            <td colspan="2">
                <input id="cupboard_amount" name="cupboard_amount" value="" />
            </td>
        </tr>
        <tr>
            <th>可使用展位时常</th>
            <td colspan="2">
                <input id="cupboard_period" name="cupboard_period" value="" />
            </td>
        </tr>


        <tr>
            <th>协议说明</th>
            <td colspan="2">
                <textarea rows="6" cols="50" name="description" placeholder="请填写协议说明" class="error"></textarea>
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
        $("#but").click(function(){
            var content     = "";
            var sn        = $("#sn").val();
            var shop_name      = $("#shop_name").val();
            var title         = $("#title").val();
            var type     = $('input[name="type"]:checked').val();
            var cupboard_period   = $("#cupboard_period").val();
            var cupboard_amount = $("#cupboard_amount").val();
            var description    = $("#description").val();

            var d = dialog({title:"提示",
                okValue: '确定',
                ok: function () {}
            });
            if(sn == ''){
                content = "协议编号不能为空！！！";
                form.name.focus();
                d.content(content);
                d.show();
                return false;
            }
            if(shop_name == ''){
                content = "商家名称不能为空！！！";
                form.name.focus();
                d.content(content);
                d.show();
                return false;
            }

            if(title == ''){
                content = "展位名称不能为空！！！";
                form.name.focus();
                d.content(content);
                d.show();
                return false;
            }

            if(type == '' || type == undefined){
                content = "请选择展位收费类型！！！";
                form.name.focus();
                d.content(content);
                d.show();
                return false;
            }

            $('#form').submit();
        });
    });


    function getcupinfo()
    {
       var shopname =  $("#shop_name").val();
        if (shopname.length>0) {
            $.getJSON('/shop/cupboardagreement/getshopinfo-byid', {'shop_name': shopname}, function (data) {
                var html = "";
                $.each(data,function(k,v){

                    html += "<option value='"+v.id+"'>"+ v.title+v.number+"</option>";

                    $("#title").val(v.title);
                    $("#shop_id").val(v.shop_id);
                });
                $("#cupboardid").html(html);
                html != '' ? $("#cupboardid").css("display","block") : $("#cupboardid").css("display","none");
                html != '' ? $("#showcup").css("display","none") : $("#showcup").css("display","block");
            });
        }
    }
</script>