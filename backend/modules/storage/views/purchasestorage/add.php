<?php
use yii\helpers\Html;
$this->title = '新增采购入库单';
?>
<!-- 引用 datetimepicker -->
<link href="/plug/datetimepicker/bootstrap-datetimepicker.min.css" rel="stylesheet">
<script src="/plug/datetimepicker/bootstrap-datetimepicker.js"></script>
<script src="/js/purchasestorage-add.js"></script>

<legends  style="fond-size:12px;">
    <ul class="breadcrumb">
        <li>
            <a href="/">首页</a>
        </li>
        <li><a href="/storage/purchasestorage/index">采购入库单管理</a></li>
        <li class="active">新增采购入库单</li>
    </ul>
    <div class="tab-content">
        <div class="row-fluid">
        </div>
    </div>
</legends>
<div class="input-group" style="margin-top: 5px;">
    <span class="input-group-addon" id="sizing-addon1">入库单号</span>
    <input type="text" readonly="readonly" id="storage_sn" class="form-control" value="<?php echo $sn;?>">
</div>
<div class="input-group date form_date" data-date="" data-date-format="dd MM yyyy" data-link-field="dtp_input2" data-link-format="yyyy-mm-dd" style="margin-top: 5px;">
    <span class="input-group-addon" id="sizing-addon1">入库日期</span>
        <input class="form-control" size="16" id="create_time" type="text" value="" readonly>
        <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
</div>
<div class="input-group" style="margin-top: 5px;">
    <span class="input-group-addon" id="sizing-addon1">关联采购单</span>
    <input type="text" id="title" class="form-control old-purchase-sn" placeholder="请输入关联采购单号">
</div>
<div class="input-group" style="margin-top: 5px;">
    <span class="input-group-addon search-loading" style="line-height: 28px;display: none;">正在努力加载中...</span>
</div>

<div class="search-main-content" style="display: none;">
    <div class="div-one" style="margin-top: 20px;">
        <p>供应商信息</p>
        <table class="table table-bordered table-hover" style="font-size: 14px;">
            <thead>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center" style="width: 100px;">供货方</td>
                    <td><span id="supplier_company_name" style="margin-left: 10px;"></span></td>
                </tr>
                <tr>
                    <td class="text-center" style="width: 100px;">联系人</td>
                    <td><span id="supplier_contact" style="margin-left: 10px;"></span></td>
                </tr>
                <tr>
                    <td class="text-center" style="width: 100px;">手机</td>
                    <td>
                        <span id="supplier_mobile" style="margin-left: 10px;"></span>
                        <span style="margin-left: 40px;"> 固话： </span>
                        <span id="supplier_phone"></span>
                    </td>
                </tr>
                <tr>
                    <td class="text-center" style="width: 100px;">电子邮箱</td>
                    <td><span id="supplier_email" style="margin-left: 10px;"></span></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="div-two" style="margin-top: 20px;">
        <p>入库基本信息</p>
        <table class="table table-bordered table-hover" style="font-size: 14px;">
            <thead>
            </thead>
            <tbody>
            <tr>
                <td class="text-center" style="width: 100px;">仓库名称</td>
                <td><span id="storage_name" style="margin-left: 10px;"></span></td>
            </tr>
            <tr>
                <td class="text-center" style="width: 100px;">入库说明</td>
                <td><span id="" style="margin-left: 10px;">
                        <textarea id="remark" class="form-control" rows="3"></textarea>
                    </span>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="div-two" style="margin-top: 20px;">
        <p>入库商品</p>
        <table class="table table-bordered table-hover" style="font-size: 14px;">
            <thead>
            </thead>
            <tbody id="itemList">
            </tbody>
        </table>
    </div>
    <a class="btn btn-primary define-submit" href="javascript:;">保存</a>
    <a class="btn btn-primary define-submit-loading" style="display: none;" href="javascript:;">保存中...</a>
    <a style="margin-left:20px;"  class="btn btn-default define-cancel" href="javascript:window.opener=null;window.open('','_self');window.close();">取消</a>
</div>
<div style="display:none" class="hidvalue">
    <input type="hidden" value="0" id="hid_supplier_id">
    <input type="hidden" value="0" id="hid_id">
    <input type="hidden" value="0" id="hid_storage_id">
    <input type="hidden" value="" id="hid_supplier_name">
    <input type="hidden" value="0" id="hid_storage_id">
</div>
<script>
    //加载时间控件
    $('.form_date').datetimepicker({
        format: 'yyyy-mm-dd',
        language:  'zh-CN',
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        minView: 3,
        forceParse: 0
    });
</script>


