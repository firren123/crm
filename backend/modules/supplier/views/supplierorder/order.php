<?php
/**
 * 简介1
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   admin
 * @filename  order.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/5/26 下午2:40
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = '新建采购单';

?>
<script type="text/javascript" src="/js/My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="/js/supplier/order.js"></script>
<style>
    .num{width: 60px;}
    .price{width: 50px;}
    .total{width: 90px;}
    td{
        text-align: left;
    }

</style>
<legends style="fond-size:12px;">
    <legend>新建采购单</legend>
</legends>
<?php
$form = ActiveForm::begin([
    'id' => "login-form",
    'layout' => 'horizontal',
//    'name'=>'add_goods',
    'action' => '/supplier/supplierorder/add-order',
    'options' => ['enctype' => 'multipart/form-data'],
]);
?>
<table  class="table table-bordered table-hover">
    <tr>
        <td colspan="3" style="text-align: left;"><a href="" id="selectedCom" class="btn btn-primary">选择供应商</a></td>
    </tr>
    <tr>
        <th width="20%">供货方</th>
        <td colspan="2" style="text-align: left;"><span id="sp-name" name="sp_name"></span></td>
    </tr>
    <tr>
        <th>联系人</th>
        <td colspan="2" style="text-align: left;"><span readonly name="sp_line_man" id="sp-line-man" ></span></td>
    </tr>
    <tr>
        <th>联系电话</th>
        <td colspan="2" style="text-align: left;"><span readonly name="sp_mobile" id="sp-phone"> </span>

        </td>
    </tr>
    <tr>
        <th>电子邮件</th>
        <td colspan="2" style="text-align: left;"><span name="sp_email" id="sp-email" ></span></td>
            <input type="hidden" name="sp_id" id="sp_id" />
        <input type="hidden" name="company_name" id="company_name" />
        </td>
    </tr>

</table>
<table class="table table-bordered table-hover">
    <tr>
        <th colspan="9">订购商品</th>
    </tr>
    <tr>
        <th width="10%">ID</th>
        <th>物品名称</th>
        <th>规格型号</th>
        <th>单位</th>
        <th>单价</th>
        <th>订购数量</th>
        <th>金额</th>
        <th>备注</th>
        <th>操作</th>
    </tr>

    <tr id="add">
        <td colspan="9" style="text-align: left;">
            <a href="" id="addgoods" class="btn btn-primary">添加商品</a>
            <span style="float: right;">订单总金额：<b id="order-all-price">0</b>
            </span>
        </td>
    </tr>


</table>
<table  class="table table-bordered table-hover">
    <tr>
        <th colspan="3">订购约定</th>
    </tr>
    <tr>
        <th width="20%"><span class="red">*</span>交货日期</th>
        <td colspan="2" style="text-align: left;">
            <input type="text" id="arrange_receive_time" name="arrange_receive_time" onFocus="WdatePicker({isShowClear:true,readOnly:false})" value="<?php if(isset($arrange_receive_time)){echo $arrange_receive_time; };?>"/>
        </td>
    </tr>
    <tr>
        <th><span class="red">*</span>交货地</th>
        <td colspan="2" style="text-align: left;">
            <select name="bc_id" id="bc_id_select">
                <option value="0">请选择</option>
                <?php foreach($branch_list as $k => $v){ ?>
                    <option value="<?= $v['id'];?>"><?= $v['name'];?></option>
                <?php } ?>
            </select>
            <select name="ware_house_id" id="wareHouse">
                <option value="">请选择</option>
            </select>
        </td>
    </tr>
    <tr>
        <th><span class="red">*</span>支付方式</th>
        <td colspan="2" style="text-align: left;">
            <select name="pay_site_id" id="pay_site_id">

                <?php foreach($pay_site_id_data as $k => $v){ ?>
                    <option value="<?= $k;?>"><?= $v;?></option>
                <?php } ?>
            </select>
        </td>
    </tr>
    <tr>
        <th>订单备注</th>
        <td colspan="2" style="text-align: left;">
            <textarea name="remark" id="" cols="60" rows="5"></textarea>
        </td>
    </tr>

</table>
<div class="sub">
<input type="submit" class="tijiao btn btn-primary" style="margin: auto 150px;" value="提交"/>
</form>
<input value="取消" class="btn btn-primary" type="button"/>

</div>
<script>
    $(function(){
        $("#selectedCom").click(function(){
            var d = dialog({
                url:'/supplier/supplierorder/supplier-list',
                title: '选择供应商',
                width:'40em',
                ok: function () {}
            });
            d.showModal();
            return false;

        });

        $("#addgoods").click(function(){
            var sp_id = $("#sp_id").val();
            var d = dialog({
                url:'/supplier/supplierorder/goods-list?sp_id='+sp_id,
                title: '选择商品',
                width:'40em',
                ok: function () {}
            });
            d.showModal();
            return false;

        });
        $(document).on("click",".goods-del",function(){
            order.goodsDel(this);
            order.allTotal();
            return false;
        });
        $(document).on("blur",".num,.price,.total",function(){
            order.oneGoodsPrice(this);
            order.allTotal();
        });
        $("#bc_id_select").change(function(){
            var id = $(this).val();
            console.log(id);
            order.getWareHouse(id);
        });
        $(".tijiao").click(function(){
           return order.check();
        });
    });




</script>












