/**
 * Created by Administrator on 2015/4/21.
 */
function Delete(id){
    var msg = "您真的确定要删除吗？";
    var token        = $('#token').val();
    if (confirm(msg)==true){
        $.ajax(
            {
                type: "POST",
                url: '/goods/product/delete',
                data: {'ids':id,'_csrf': token},
                asynic: false,
                dataType: "json",
                beforeSend: function () {
                },
                success: function (result) {
                    if(result==1){
                        window.location.reload()
                    }
                }
            }
        );

    }else{
        return false;
    }

}

/**
 * 全选
 * @return ''
 */
function clickCheckbox(){
    $(".chooseAll").click(function(){
        var status=$(this).prop('checked');
        $(".check").prop("checked",status);
        //$(".chooseAll").prop("checked",status);
    });
}
/**
 * 批量删除
 * @returns {boolean}
 */
function checkSelectd(){
    var falg = 0;
    $("input[name='ids[]']:checkbox").each(function () {
        if ($(this).prop("checked")==true) {
            falg += 1;
        }
    })
    if (falg > 0){
        var token        = $('#token').val();
        var ids = $("input[name='ids[]']:checkbox").valueOf();
        var ids=$('input[id="brandid"]:checked').map(function(){return this.value}).get().join();
        $.ajax(
            {
                type: "POST",
                url: "/goods/product/delete",
                data: {'ids': ids,'_csrf': token},
                asynic: false,
                dataType: "json",
                beforeSend: function () {
                },
                success: function (result) {
                    if(result==1){
                        window.location.reload()
                    }
                }
            });
    }else{
        gf.alert('请选择要删除项');
        return false;
    }
}
    /**
     * 批量上架
     * @returns {boolean}
     */
    function getUpdateOne(){
        var falg = 0;
        $("input[name='ids[]']:checkbox").each(function () {
            if ($(this).prop("checked")==true) {
                falg += 1;
            }
        })
        if (falg > 0){
            var token        = $('#token').val();
            var ids = $("input[name='ids[]']:checkbox").valueOf();
            var ids=$('input[id="brandid"]:checked').map(function(){return this.value}).get().join();
            $.ajax(
                {
                    type: "POST",
                    url: "/goods/product/ajax-update",
                    data: {'ids': ids,'_csrf': token,'number':1},
                    asynic: false,
                    dataType: "json",
                    beforeSend: function () {
                    },
                    success: function (result) {
                        if(result==1){
                            window.location.reload()
                        }
                    }
                });
        }else{
            gf.alert('请选择要上架的商品');
            return false;
        }
    }
/**
 * 批量下架
 * @returns {boolean}
 */
function getUpdateTwo(){
    var falg = 0;
    $("input[name='ids[]']:checkbox").each(function () {
        if ($(this).prop("checked")==true) {
            falg += 1;
        }
    })
    if (falg > 0){
        var token        = $('#token').val();
        var ids = $("input[name='ids[]']:checkbox").valueOf();
        var ids=$('input[id="brandid"]:checked').map(function(){return this.value}).get().join();
        $.ajax(
            {
                type: "POST",
                url: "/goods/product/ajax-update",
                data: {'ids': ids,'_csrf': token,'number':2},
                asynic: false,
                dataType: "json",
                beforeSend: function () {
                },
                success: function (result) {
                    if(result==1){
                        window.location.reload()
                    }
                }
            });
    }else{
        gf.alert('请选择要上架的商品');
        return false;
    }
}
/**
 * 批量热销
 * @returns {boolean}
 */
function getUpdateHot(){
    var falg = 0;
    $("input[name='ids[]']:checkbox").each(function () {
        if ($(this).prop("checked")==true) {
            falg += 1;
        }
    })
    if (falg > 0){
        var token        = $('#token').val();
        var ids = $("input[name='ids[]']:checkbox").valueOf();
        var ids=$('input[id="brandid"]:checked').map(function(){return this.value}).get().join();
        $.ajax(
            {
                type: "POST",
                url: "/goods/product/ajax-update",
                data: {'ids': ids,'_csrf': token,'number':3},
                asynic: false,
                dataType: "json",
                beforeSend: function () {
                },
                success: function (result) {
                    if(result==1){
                        window.location.reload()
                    }
                }
            });
    }else{
        gf.alert('请选择热销的商品');
        return false;
    }
}
/**
 * 批量发布
 * @returns {boolean}
 */
function getSingle(){
    var falg = 0;
    $("input[name='ids[]']:checkbox").each(function () {
        if ($(this).prop("checked")==true) {
            falg += 1;
        }
    })
    if (falg > 0){
        var token        = $('#token').val();
        var ids = $("input[name='ids[]']:checkbox").valueOf();
        var ids=$('input[id="brandid"]:checked').map(function(){return this.value}).get().join();
        $.ajax(
            {
                type: "GET",
                url: '/goods/productpre/update-single',
                data: {'product_id':ids},
                asynic: false,
                dataType: "json",
                beforeSend: function () {
                },
                success: function (result) {
                    if(result['code']==200){
                        window.location.reload()
                    } else {
                        gf.alert(result['msg']);
                    }
                }
            });
    }else{
        gf.alert('请选择要发布的商品');
        return false;
    }
}
/**
 * 删除图片
 * @param id
 * @returns {boolean}
 * @constructor
 */
function DeleteImg(id){
    var msg = "您真的确定要删除吗？";
    if (confirm(msg)==true){
        $.ajax(
            {
                type: "GET",
                url: '/goods/product/delete-img',
                data: {'ids':id},
                asynic: false,
                dataType: "json",
                beforeSend: function () {
                },
                success: function (result) {
                    if(result==1){
                        window.location.reload()
                    }
                }
            }
        );

    }else{
        return false;
    }

}
/**
 * 设置主图
 * @param id
 * @param oid_id
 * @returns {boolean}
 * @constructor
 */
function UpdateImg(id,oid_id){
    var msg = "您真的确定设置主图吗？";
    if (confirm(msg)==true){
        $.ajax(
            {
                type: "GET",
                url: '/goods/product/update-img',
                data: {'id':id,'old_id':oid_id},
                asynic: false,
                dataType: "json",
                beforeSend: function () {
                },
                success: function (result) {
                    if(result==1){
                        window.location.reload()
                    }
                }
            }
        );
    }else{
        return false;
    }
}
/**
 * 发布
 * @param $product_id
 */
function getUpdateSingle(product_id){
    var msg = "您真的确定发布吗？";
    if (confirm(msg)==true){
        $.ajax(
            {
                type: "GET",
                url: '/goods/productpre/update-single',
                data: {'product_id':product_id},
                asynic: false,
                dataType: "json",
                beforeSend: function () {
                },
                success: function (result) {
                    if(result['code']==200){
                        window.location.reload()
                    } else {
                        gf.alert(result['msg']);
                    }
                }
            }
        );
    }else{
        return false;
    }
}
/**
 * 单个取消发布
 * @returns {boolean}
 */
function getUpdatePreOne(product_id){
    var msg = "您真的确定取消发布吗？";
    if (confirm(msg)==true){
        $.ajax(
            {
                type: "GET",
                url: '/goods/product/update-single',
                data: {'product_id':product_id},
                asynic: false,
                dataType: "json",
                beforeSend: function () {
                },
                success: function (result) {
                    if(result['code']==200){
                        window.location.reload()
                    } else {
                        gf.alert(result['msg']);
                    }
                }
            }
        );
    }else{
        return false;
    }
}
/**
 * 批量取消发布
 * @returns {boolean}
 */
function getUpdatePre(){
    var falg = 0;
    $("input[name='ids[]']:checkbox").each(function () {
        if ($(this).prop("checked")==true) {
            falg += 1;
        }
    })
    if (falg > 0){
        var token        = $('#token').val();
        var ids = $("input[name='ids[]']:checkbox").valueOf();
        var ids=$('input[id="brandid"]:checked').map(function(){return this.value}).get().join();
        $.ajax(
            {
                type: "GET",
                url: '/goods/product/update-single',
                data: {'product_id':ids},
                asynic: false,
                dataType: "json",
                beforeSend: function () {
                },
                success: function (result) {
                    if(result['code']==200){
                        window.location.reload()
                    } else {
                        gf.alert(result['msg']);
                    }
                }
            });
    }else{
        gf.alert('请选择要取消发布的商品');
        return false;
    }
}
$(function()
{
    $(document).on("click",".zjs_text_price_3",function(event)
    {
        if (event.preventDefault) {
            event.preventDefault();
        } else {
            event.returnValue = false;
        }

        $(this).closest("tr").find(".zjs_text_3").hide();
        $(this).closest("tr").find(".zjs_input_3").show();
    });
    $(document).on("click",".zjs_btn_3",function(event)
    {
        console.log(123);
        if (event.preventDefault) {
            event.preventDefault();
        } else {
            event.returnValue = false;
        }
        //alert(123);
        var price=$(this).closest("tr").find(".zjs_input_price_3").val();
        var price1=$(this).closest("tr").find(".zjs_input_price_1").val();
        var price2=$(this).closest("tr").find(".zjs_input_price_2").val();
        var id=$(this).closest("tr").find(".zjs_cur_id").html();
        console.log('id='+id);
        var csrf=$(".zjs_csrf").html();
        console.log(price);
        if(z_check_money(price)==0){alert("价格格式错误");return;}
        price=parseFloat(price);
        price1=parseFloat(price1);
        price2=parseFloat(price2);
        if(price<0){alert("价格格式错误");return;}
        if(price < price1 && price !=0){
            alert("铺货价必须大于进货价");
            return;
        }
        if(price > price2 && price !=0){
            alert("铺货价必须小于零售价");
            return;
        }
        price=price.toFixed(2);

        var self=this;
        $.post
        (
            "/goods/product/upprice",
            {
                "_csrf":csrf,
                "id":id,
                "shop_price":price
            },
            function(obj)
            {
                console.log(obj);
                if(obj.code==200){
                    $(self).closest("tr").find(".zjs_text_price_3").html(price);
                    //$(self).closest("tr").find(".zjs_text_stock").html(stock);
                    $(".zjs_input_3").hide();
                    $(".zjs_text_3").show();
                }else{
                    alert(obj.msg);
                }
            },
            'json'
        );
    });
    $(document).on("click",".zjs_text_price_2",function(event)
    {
        if (event.preventDefault) {
            event.preventDefault();
        } else {
            event.returnValue = false;
        }

        $(this).closest("tr").find(".zjs_text_2").hide();
        $(this).closest("tr").find(".zjs_input_2").show();
    });

    $(document).on("click",".zjs_btn_2",function(event)
    {
        if (event.preventDefault) {
            event.preventDefault();
        } else {
            event.returnValue = false;
        }
        var price=$(this).closest("tr").find(".zjs_input_price_2").val();
        var price1=$(this).closest("tr").find(".zjs_input_price_1").val();
        var price3=$(this).closest("tr").find(".zjs_input_price_3").val();
        var id=$(this).closest("tr").find(".zjs_cur_id").html();
        console.log('id='+id);
        var csrf=$(".zjs_csrf").html();
        console.log(price);
        if(z_check_money(price)==0){alert("价格格式错误");return;}
        price=parseFloat(price);
        price1=parseFloat(price1);
        price3=parseFloat(price3);
        if(price<0){alert("价格格式错误");return;}
        if(price < price1){
            alert("零售价必须大于等于进货价");
            return;
        }
        if(price < price3 && price3 !=0){
            alert("零售价必须大于等于铺货价");
            return;
        }
        price=price.toFixed(2);

        var self=this;
        $.post
        (
            "/goods/product/upprice",
            {
                "_csrf":csrf,
                "id":id,
                "origin_price":price
            },
            function(obj)
            {
                console.log(obj);
                if(obj.code==200){
                    $(self).closest("tr").find(".zjs_text_price_2").html(price);
                    //$(self).closest("tr").find(".zjs_text_stock").html(stock);
                    $(".zjs_input_2").hide();
                    $(".zjs_text_2").show();
                }else{
                    alert(obj.msg);
                }
            },
            'json'
        );
    });

    $(document).on("click",".zjs_text_price_1",function(event)
    {
        if (event.preventDefault) {
            event.preventDefault();
        } else {
            event.returnValue = false;
        }

        $(this).closest("tr").find(".zjs_text_1").hide();
        $(this).closest("tr").find(".zjs_input_1").show();
    });


    $(document).on("click",".zjs_btn_1",function(event)
    {
        console.log(123);
        if (event.preventDefault) {
            event.preventDefault();
        } else {
            event.returnValue = false;
        }
        //alert(123);
        var price=$(this).closest("tr").find(".zjs_input_price_1").val();
        var price2=$(this).closest("tr").find(".zjs_input_price_2").val();
        var price3=$(this).closest("tr").find(".zjs_input_price_3").val();
        var id=$(this).closest("tr").find(".zjs_cur_id").html();
        console.log('id='+id);
        var csrf=$(".zjs_csrf").html();
        console.log(price);
        if(z_check_money(price)==0){alert("价格格式错误");return;}
        price=parseFloat(price);
        price2=parseFloat(price2);
        price3=parseFloat(price3);
        if(price<0){alert("价格格式错误");return;}
        if(price > price2){
            alert("进货价必须小于等于零售价");
            return;
        }
        if(price > price3 && price3 !=0){
            alert("进货价必须小于等于铺货价");
            return;
        }
        price=price.toFixed(2);

        var self=this;
        $.post
        (
            "/goods/product/upprice",
            {
                "_csrf":csrf,
                "id":id,
                "sale_price":price
            },
            function(obj)
            {
                console.log(obj);
                if(obj.code==200){
                    $(self).closest("tr").find(".zjs_text_price_1").html(price);
                    //$(self).closest("tr").find(".zjs_text_stock").html(stock);
                    $(".zjs_input_1").hide();
                    $(".zjs_text_1").show();
                }else{
                    alert(obj.msg);
                }
            },
            'json'
        );
    });

});
//$(document).on("click",".zjs_text_price_1,zjs_text_price_2,zjs_text_price_3",function(event)
//{
//    if (event.preventDefault) {
//        event.preventDefault();
//    } else {
//        event.returnValue = false;
//    }
//    console.log(122223);
//    var onk = $(this).attr('class');
//    console.log(onk);
//    if(onk == "zjs_text_price_1"){
//        var otxt = "zjs_text_1";
//        var oinput = "zjs_text_1";
//    }else if(onk == "zjs_text_price_2"){
//        var otxt = "zjs_text_2";
//        var oinput = "zjs_text_2";
//    }else if(onk == "zjs_text_price_3"){
//        var otxt = "zjs_text_3";
//        var oinput = "zjs_text_3";
//    }
//
//    $(this).closest("tr").find(otxt).hide();
//    $(this).closest("tr").find(oinput).show();
//});
var product = {
    edit:function(){
        $(document).on("click",".zjs_btn_1",function(event)
        {
            console.log(123);
            if (event.preventDefault) {
                event.preventDefault();
            } else {
                event.returnValue = false;
            }
            //alert(123);
            var price=$(this).closest("tr").find(".zjs_input_price_1").val();
            var id=$(this).closest("tr").find(".zjs_cur_id").html();
            console.log('id='+id);
            var csrf=$(".zjs_csrf").html();
            console.log(price);
            if(z_check_money(price)==0){alert("价格格式错误");return;}
            price=parseFloat(price);
            if(price<0){alert("价格格式错误");return;}
            price=price.toFixed(2);

            var self=this;
            $.post
            (
                "/goods/product/upprice",
                {
                    "_csrf":csrf,
                    "id":id,
                    "sale_price":price
                },
                function(obj)
                {
                    console.log(obj);
                    if(obj.code==200){
                        $(self).closest("tr").find(".zjs_text_price_1").html(price);
                        //$(self).closest("tr").find(".zjs_text_stock").html(stock);
                        $(".zjs_input_1").hide();
                        $(".zjs_text_1").show();
                    }else{
                        alert(obj.msg);
                    }
                },
                'json'
            );
        });
    }

}

function z_check_money(str)
{
    if (!(/^(([1-9]\d*)|0)(\.\d{1,2})?$/.test(str))) {
        return 0;
    }
    return 1;
}


