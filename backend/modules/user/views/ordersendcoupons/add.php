<?php
$this->title = '订单分发优惠券规则-添加';
?>
<script type="text/javascript" src="/js/orderssendcoupons.js"></script>
<legends  style="fond-size:12px;">
    <ul class="breadcrumb">
        <li>
            <a href="/">首页</a>
        </li>
        <li><a href="/user/ordersendcoupons">订单分发优惠券规则</a></li>
        <li class="active">订单分发优惠券规则-添加</li>
    </ul>
    <div class="tab-content">
        <div class="form-horizontal">
            <div class="form-group field-brand-name required">
                <label class="control-label col-sm-3" for="brand-name">上限值:</label>
                <div class="col-sm-6">
                    <input id="max" class="form-control" type="text" style="width:200px" name="min">
                    <div class="help-block help-block-error "></div>
                </div>
            </div>
            <div class="form-group field-brand-name required">
                <label class="control-label col-sm-3" for="brand-name">下限值:</label>
                <div class="col-sm-6">
                    <input id="min" class="form-control" type="text" style="width:200px" name="max">
                    <div class="help-block help-block-error "></div>
                </div>
            </div>
            <div class="form-group field-brand-name required">
                <label class="control-label col-sm-3" for="brand-name">上下限单位:</label>
                <div class="col-sm-6">
                    <select style="width:100px;height: 30px" name="min_unit" id="min_unit">
                        <option selected="selected" value="1">元</option>
                        <option value="2">%</option>
                    </select>
                </div>
            </div>
            <div class="form-group field-brand-name required">
                <label class="control-label col-sm-3" for="brand-name">最多领取数量:</label>
                <div class="col-sm-6">
                    <input id="number" class="form-control" type="number" style="width:200px;float: left" name="number" min="1" value="1" onkeyup="this.value=this.value.replace(/[^\d]/g, '')" onpaste="return false">
                    <span style="float: left;height: 30px;line-height: 30px;margin-left: 10px">个</span>
                    <div class="help-block help-block-error "></div>
                </div>
            </div>
            <div class="form-group field-brand-name required">
                <label class="control-label col-sm-3" for="brand-name">优惠券有限期:</label>
                <div class="col-sm-6">
                    <input id="validity" class="form-control" type="number" style="width:200px;float: left" name="validity" min="1" value="7" onkeyup="value=value.replace(/[^\d\.]/g,'')">
                    <span style="float: left;height: 30px;line-height: 30px;margin-left: 10px">日</span>
                    <div class="help-block help-block-error "></div>
                </div>
            </div>
            <div class="form-group field-brand-name required">
                <label class="control-label col-sm-3" for="brand-name">状态:</label>
                <div class="col-sm-6">
                    <select style="width:100px;height: 30px" name="min_unit" id="status">
                        <option selected="selected" value="1">开启</option>
                        <option value="2">关闭</option>
                    </select>
                </div>
            </div>
        </div>

    </div>
    <div class="form-actions">
        <input type="hidden" id="csrf" value="<?php echo Yii::$app->getRequest()->getCsrfToken(); ?>" />
        <input type="button" value="提交" onclick="order.postAdd()" class = 'btn btn-primary'>
        <a class="btn cancelBtn" href="/user/ordersendcoupons">返回</a>
    </div>
</legends>
<script>
    $("#add_attr_value").click(function(){
        //gf.confirm('','gf.abc()');
        var html = $('table tbody tr:first-child').html();
        $('table tbody').append('<tr>'+html+'</tr>');
    })
</script>