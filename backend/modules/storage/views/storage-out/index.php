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
$this->title = '供应商出库单';
?>

<style>
    .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td{
        text-align: left;
    }
</style>
<script src="/js/storageout/storage.js"></script>
<div>
    <ul class="breadcrumb">
        <li><a href="/">首页</a></li>
        <li class="active"><a href="/storage/storage-out/index" style="color:#286090;">采购出库单管理</a></li>
        <li class="active">添加出库</li>
    </ul>
</div>
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
        <th colspan="6" style="text-align: left;">出库基本信息</th>
    </tr>
    <tr>
        <th width="20%"><span class="red">*</span>出库原因</th>
        <td colspan="5" width="80%">
            <select id="reason" name="reason">
                <option value="1">销售</option>
                <option value="2">员工福利</option>
                <option value="3">清理库存</option>
                <option value="4">调拨出库</option>
            </select>
        </td>
    </tr>
    <tr>
        <th width="20%"><span class="red">*</span>选择仓库</th>
        <td colspan="5" width="80%">
            <select id="depots" name="depots">
                <option value="">请选择仓库</option>
                <?php if(!empty($ware)){?>
                    <?php foreach($ware as $item):?>
                        <option value="<?=$item['sn']?>"><?=$item['name']?></option>
                    <?php endforeach;?>
                <?php }?>
            </select>
        </td>
    </tr>
    <tr>
        <th width="20%"><span class="red">*</span>出库说明</th>
        <td colspan="5" width="80%">
            <textarea id="explain" name="explain" placeholder='请填写入库说明'></textarea>
        </td>
    </tr>
</table>

<div id="div" style="display: none">
    <table class="table table-bordered table-hover" id="goods">
        <tr>
            <th colspan="10" style="text-align: left;">出库商品</th>
        </tr>
        <tr>
            <th>行号</th>
            <th>物品名称</th>
            <th>规格型号</th>
            <th>单位</th>
            <th>单价</th>
            <!--<th>待入数量</th>-->
            <th>入库数量</th>
            <th>金额</th>
            <th>备注</th>
            <th>操作</th>
        </tr>
    </table>
</div>
    <a href="javascript:;" id="add_good" class="btn btn-primary sub_ok">添加商品</a>
    <!--<a href="/storage/suppliers/good-add" id="add_good" class="btn btn-primary sub_ok">添加商品</a>-->
<div style="display: none">
    <input type="hidden" id="info_id" name="info_id" value="">
    <input type="hidden" id="info_name" name="info_name" value="">
    <input type="hidden" id="info_pric" name="info_pric" value="">
    <input type="hidden" id="info_attr_value" name="info_attr_value" value="">
    <input type="hidden" id="info_num" name="info_num" value="">
    <input type="hidden" id="info_sum" name="info_sum" value="">
    <input type="hidden" id="info_bar_code" name="info_bar_code" value="">
    <input type="hidden" id="info_storage_sn" name="info_storage_sn" value="">
    <input type="hidden" id="info_remark" name="info_remark" value="">
</div>
    <table class="table table-bordered table-hover" style="margin-top: 10px">
        <tr>
            <td style="text-align: center;">
                <a href="javascript:;" id="sub" class="btn btn-primary sub_ok">提交</a>
                <a class="btn cancelBtn" href="/storage/warehouse/index">取消</a>
                <span class="green" id="msg"></span>
            </td>

        </tr>
    </table>
<?php ActiveForm::end(); ?>
