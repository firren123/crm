<?php
/**
 * 简介1
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   admin
 * @filename  add.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/6/1 下午1:31
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = "退货审核";
?>

<style>
    .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td{
        text-align: left;
    }
    .compileForm .imgListForm{padding-top:10px; height:117px;}
    ul li{
        float: left;

    }
    ul{list-style: none;}
    .imgListForm li{width:92px;min-height:117px; border:none; background:#fff;}
    .imgListForm a,.imgListForm span{display:block;}
    .imgListForm a{width:90px; height:90px; text-align:center; border:1px solid #dfdfdf; background:#f5f5f5;}
    .imgListForm span{color:#666; line-height:25px; cursor:pointer;text-align: center;}
</style>
<legends  style="fond-size:12px;">
    <legend>退货订单审核</legend>
</legends>
<?php
$form = ActiveForm::begin([
    'id' => "login-form",
    'layout' => 'horizontal',
    'enableAjaxValidation' => false,
    'options' => ['enctype' => 'multipart/form-data'],
]);
?>
<table class="table table-bordered table-hover">
<tr>
    <th colspan="6" style="text-align: left;">退货申请审核</th>
</tr>
<tr>
    <input type="hidden" id="ex_id" name="ex_id" value="<?=$ex_id;?>">
    <input type="hidden" id="order_sn" name="order_sn" value="<?=$order_sn;?>">
    <input type="hidden" id="apply_time" name="apply_time" value="<?=$apply_time;?>">
    <th><span class="red">*</span>审核:</th>
    <td colspan="5" width="80%">
        <div class="radio">
            <label><input type="radio" name="status" value="0"> 审核驳回</label>
            <label><input type="radio" name="status"  value="1"> 审核通过</label>
        </div>
    </td>
</tr>
<tr>
    <th><span class="red">*</span>是否联系:</th>
    <td colspan="5" width="80%">
        <label><input type="checkbox" value="1"  name="contact[]"/>已联系商家</label>
        <label><input type="checkbox" value="1"  name="contact[]"/>已联系用户</label>
    </td>
</tr>
<tr>
    <th><span class="red">*</span>驳回理由:</th>
    <td>
        <textarea id="remark" name="remark" class="form-control"></textarea>
    </td>
</tr>
</table>

<table class="table table-bordered table-hover">
    <tr>
        <td style="text-align: center;">
            <a class="btn btn-primary sub_ok" href="javascript:;">提交</a>
            <a class="btn cancelBtn" href="javascript:history.go(-1);">取消</a>
        </td>
    </tr>
</table>
<?php ActiveForm::end(); ?>
<script type="text/javascript">
$(function(){
    $(".sub_ok").click(function(){
        var status = $.trim($('input:radio:checked').val());
        var remark = $.trim($("#remark").val());
        var contact = $("input:checkbox[name='contact[]']:checked");
        var dia = dialog({title:"提示",
            okValue: '确定',
            ok: function () {}
        });
        if(status == undefined){
            content = "审核不能为空！！！";
            dia.content(content);
            dia.showModal();
            return false;
        }
        if(contact.length < 2){
            content = "联系商家和用户必须全选！！！";
            dia.content(content);
            dia.showModal();
            return false;
        }
        if(status == 0) {
            if (remark == '') {
                content = "驳回理由不能为空！！！";
                dia.content(content);
                dia.showModal();
                return false;
            }
        }
        $("#login-form").submit();
    })
})
</script>