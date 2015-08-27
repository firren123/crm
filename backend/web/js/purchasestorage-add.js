/**
 * 添加采购入库单
 * @author    linxinliang <linxinliang@iyangpin.com>
 */
var purchaseAdd;
purchaseAdd = {
    //获取采购单信息
    'getPurchaseInfo' : function(purchase_sn) {
        if (!purchase_sn) {
            gf.alert("请输入关联采购单号");
            return false;
        }
        $.ajax(
            {
                type: "GET",
                url : $('#base_url').val()+'storage/purchasestorage/ajax-get-purchase-info',
                data: {'sn':purchase_sn},
                asynic: false,
                dataType: "json",
                beforeSend: function () {
                    /** 提交中 **/
                    $(".search-loading").show();
                },
                success: function (result) {
                    $(".search-loading").hide();
                    if (result['code'] === 'ok') {
                        var supplier_order = result['data']['supplier_order'];
                        var supplier       = result['data']['supplier'];
                        var storage        = result['data']['storage'];
                        var itemlist       = result['data']['itemlist'];
                        //supplier_order 分配信息
                        if (supplier_order['supplier_id']) {
                            $("#hid_supplier_id").val(supplier_order['supplier_id']);
                        }
                        if (supplier_order['id']) {
                            $("#hid_id").val(supplier_order['id']);
                        }
                        if (supplier_order['storage_id']) {
                            $("#hid_storage_id").val(supplier_order['storage_id']);
                        }
                        if (supplier_order['supplier_name']) {
                            $("#hid_supplier_name").val(supplier_order['supplier_name']);
                        }
                        if (supplier_order['storage_id']) {
                            $("#hid_storage_id").val(supplier_order['storage_id']);
                        }
                        //supplier 分配信息
                        if (supplier['company_name']) {
                            $("#supplier_company_name").text(supplier['company_name']);
                        } else {
                            gf.alert('供应商名称为空，请重新输入');
                            return false;
                        }
                        if (supplier['contact']) {
                            $("#supplier_contact").text(supplier['contact']);
                        } else {
                            gf.alert('供应商联系人为空，请重新输入');
                            return false;
                        }
                        if (supplier['mobile']) {
                            $("#supplier_mobile").text(supplier['mobile']);
                        } else {
                            gf.alert('供应商手机为空，请重新输入');
                            return false;
                        }
                        if (supplier['phone']) {
                            $("#supplier_phone").text(supplier['phone']);
                        }
                        if (supplier['email']) {
                            $("#supplier_email").text(supplier['email']);
                        }

                        //storage 分配信息
                        if (storage['name']) {
                            $("#storage_name").text(storage['name']);
                        } else {
                            gf.alert('仓库名称为空，请重新输入');
                            return false;
                        }

                        //itemlist 分配信息
                        $("#itemList").html("");
                        var tr_str = '<tr><th class="text-center">ID</th><th class="text-center">商品名称</th><th class="text-center">商品规格</th><th class="text-center">条形码</th><th class="text-center">良品数量</th><th class="text-center">次品数量</th><th class="text-center">入库说明</th><th class="text-center">操作</th></tr>';
                        if (itemlist.length != 0) {
                            for(var i=0;i<itemlist.length;i++){
                                tr_str += '<tr>';
                                tr_str += '<td style="vertical-align:middle;" class="text-center data_id">'+itemlist[i].goods_id+'</td>';
                                tr_str += '<td class="data_goods_name" style="vertical-align:middle;">'+itemlist[i].name+'</td>';
                                tr_str += '<td style="vertical-align:middle;" class="text-center data_attr_value">'+itemlist[i].attr_value+'</td>';
                                tr_str += '<td style="vertical-align:middle;" class="text-center data_bar_code">'+itemlist[i].bar_code+'</td>';
                                tr_str += '<td style="vertical-align:middle;"><input type="text" class="form-control data_good_number" onchange="purchaseAdd.formatNum(this);" style="width: 60px; margin: 0 auto;" value="0"></td>';
                                tr_str += '<td style="vertical-align:middle;"><input type="text" class="form-control data_defective_number" onchange="purchaseAdd.formatNum(this);" style="width: 60px; margin: 0 auto;" value="0"></td>';
                                tr_str += '<td style="vertical-align:middle;"><input type="text" class="form-control data_remark" style="width: 100px; margin: 0 auto;" placeholder="入库说明"></td>';
                                tr_str += '<td style="vertical-align:middle;" class="text-center"><a class="data_goods_id" href="javascript:;" title="移除" onclick="purchaseAdd.del(this)" data_id="'+itemlist[i].goods_id+'"><span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span></a></td>';
                                tr_str += '</tr>';
                            }
                        } else {
                            tr_str += '<tr><td colspan="7" style="text-align: center;">暂无商品信息</td></tr>';
                            $(".define-submit").attr("disabled","disabled");
                        }
                        $("#itemList").append(tr_str);
                        /** 成功 **/
                        $(".search-main-content").show();
                    } else {
                        /** 失败 **/
                        gf.alert(result['message']);
                    }
                }
            });
    },
    // 验证采购单号
    'checkPurchaseSN' : function (purchase_sn){
        if (!purchase_sn) {
            gf.alert("请输入关联采购单号");
            return false;
        }
        if (purchase_sn.indexOf("CG-")=='-1') {
            gf.alert("关联采购单号格式不正确！");
            return false;
        }
        return true;
    },
    // 移除商品
    'del' : function(obj){
        var goods_id =  $(obj).attr('data_id');
        if (!goods_id) {
            gf.alert("请选择要移除的商品");
        } else {
            // 移除当前行
            var chk_value =[];
            $(".data_goods_id").each(function(){
                chk_value.push($(obj).attr('data_id'));
            });
            $(obj).parent().parent().remove();
            if (chk_value.length==1) {
                $("#itemList").append('<tr><td colspan="7" style="text-align: center;">暂无商品信息</td></tr>');
                $(".define-submit").attr("disabled","disabled");
            }
        }
    },
    //保存
    'submitInfo' : function(){
        var server_url  = $('#base_url').val()+'storage/purchasestorage/ajax-add-purchase';
        var post_data;
        var token       = $('#token').val();
        var create_time = $("#create_time").val();
        if (!create_time) {
            gf.alert('请填写入库日期');
            return false;
        }
        var purchase_order_id = $("#hid_id").val();
        if (!purchase_order_id || purchase_order_id==0) {
            gf.alert('缺少参数');
            return false;
        }
        var storage_sn = $("#storage_sn").val();
        if (!storage_sn) {
            gf.alert('缺少入库单号');
            return false;
        }
        var sp_id = $("#hid_supplier_id").val();
        if (!sp_id || sp_id==0) {
            gf.alert('缺少参数');
            return false;
        }
        var sp_name = $("#hid_supplier_name").val();
        if (!sp_name) {
            gf.alert('缺少参数');
            return false;
        }
        var storage_id = $("#hid_storage_id").val();
        if (!storage_id || storage_id==0) {
            gf.alert('缺少参数');
            return false;
        }
        var remark = $("#remark").val();
        if (!remark) {
            gf.alert('请填写入库说明！');
            return false;
        }
        var data_ids =[];
        $(".data_id").each(function(){
            data_ids.push($(this).text());
        });
        if (data_ids.length==0) {
            gf.alert("非法的商品ID");
            return false;
        }
        var data_goods_names =[];
        $(".data_goods_name").each(function(){
            data_goods_names.push($(this).text());
        });
        if (data_goods_names.length==0) {
            gf.alert("非法的商品名称");
            return false;
        }
        var data_attr_values =[];
        $(".data_attr_value").each(function(){
            data_attr_values.push($(this).text());
        });
        if (data_attr_values.length==0) {
            gf.alert("非法的商品规格");
            return false;
        }
        var data_bar_codes =[];
        $(".data_bar_code").each(function(){
            data_bar_codes.push($(this).text());
        });
        if (data_bar_codes.length==0) {
            gf.alert("非法的商品条形码");
            return false;
        }
        var data_good_numbers =[];
        $(".data_good_number").each(function(){
            data_good_numbers.push($(this).val());
        });
        if (data_good_numbers.length==0) {
            gf.alert("非法的商品良品数");
            return false;
        }
        var data_defective_numbers =[];
        $(".data_defective_number").each(function(){
            data_defective_numbers.push($(this).val());
        });
        if (data_defective_numbers.length==0) {
            gf.alert("非法的商品次品数");
            return false;
        }
        var data_remarks =[];
        $(".data_remark").each(function(){
            data_remarks.push($(this).val());
        });
        if (data_remarks.length==0) {
            gf.alert("非法的商品入库说明");
            return false;
        }
        for(var i=0;i<data_ids.length;i++) {
            if (data_good_numbers[i]==0 && data_defective_numbers[i]==0) {
                gf.alert('商品ID['+data_ids[i]+']的商品，良品数和次品数，不能全部为0！');
                return false;
            }
        }
        post_data = {
            'purchase_order_id':purchase_order_id,
            'storage_sn':storage_sn,
            'sp_id':sp_id,
            'sp_name':sp_name,
            'storage_id':storage_id,
            'remark':remark,
            'create_time':create_time,
            'goods_ids':data_ids,
            'goods_names':data_goods_names,
            'attr_values':data_attr_values,
            'bar_codes':data_bar_codes,
            'good_numbers':data_good_numbers,
            'defective_numbers':data_defective_numbers,
            'goods_remarks':data_remarks,
            '_csrf':token
        };
        $.ajax(
            {
                type: "POST",
                url : server_url,
                data: post_data,
                asynic: false,
                dataType: "json",
                beforeSend: function () {
                    /** 提交中 **/
                    $(".define-submit").hide();
                    $(".define-submit-loading").show();
                },
                success: function (result) {
                    if (result['code'] === 'ok') {
                        window.location.href="/storage/purchasestorage/index";
                        //gf.alert('保存成功');
                    } else {
                        /** 失败 **/
                        gf.alert(result['message']);
                    }
                    $(".define-submit").show();
                    $(".define-submit-loading").hide();
                }
            });
    },
    /**对数量进行处理**/
    'formatNum':function(obj){
        /** 对数量进行处理 **/
        $(obj).val($(obj).val().replace(/[^0-9]/g,''));
        var num = parseInt($(obj).val());
        if (num < 1 || isNaN(num)) {
            $(obj).val('1');
        }
    },
    /**执行查询的功能**/
    'searchData':function(){
        //1.验证采购单号
        var purchase_sn = $.trim($(".old-purchase-sn").val());
        if (purchaseAdd.checkPurchaseSN(purchase_sn)) {
            //2.请求服务器
            purchaseAdd.getPurchaseInfo(purchase_sn);
        }
    }
};

$(document).ready(function(){
    //关联采购单号时区焦点
    $(".old-purchase-sn").blur(function(){
        purchaseAdd.searchData();
    });
    //点击保存
    $(".define-submit").click(function(){
        purchaseAdd.submitInfo();
    });
    //回车事件
    $(".old-purchase-sn").keydown(function(e){
        if(e.keyCode==13){
            purchaseAdd.searchData();
        }
    });
});