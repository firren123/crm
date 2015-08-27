/**
 * Created by lichenjun on 15/5/30.
 */
var userOrder = {
    order_sn:'',
    test:function(){
        alert('test userOrder is used');
    },
    editBut:function(e){
        $(e).parent().next().find(".user_value").hide();
        $(e).parent().next().find(".user_input").show();
        $("#editUserInfo").hide();
        $("#saveUserInfo").show();
    },
    saveBut:function(e){
        var name = $("#input_consignee").val();
        var mobile = $("#input_mobile").val();
        var address = $("#input_address").val();
        var order_sn = $("#order_sn").html();
        var _csrf = $("#csrf").val();
        var url = "/user/userorder/edit-order-info";
        if(!this.checkPhone(mobile)){
            alert('手机号码错误');
            return false;
        }
        if(name == ''){
            alert('收件人不能为空');
            return false;
        }
        if(address == ''){
            alert('收货地址不能为空');
            return false;
        }
        $.post(
            url,
            {'consignee':name,'order_sn':order_sn,'mobile':mobile,'address':address,'_csrf':_csrf},
            function(data){
                console.log(data);
                if(data.code ==200){
                    $("#val_consignee").text(name);
                    $("#val_mobile").text(mobile);
                    $("#val_address").text(address);
                    $(e).parent().next().find(".user_value").show();
                    $(e).parent().next().find(".user_input").hide();
                }else{
                    alert(data.message);
                }
            },
            'json'
        );

        $("#editUserInfo").show();
        $("#saveUserInfo").hide();

    },
    checkPrice:function(str)
    {
        if (!(/^(([1-9]\d*)|0)(\.\d{1,2})?$/.test(str))) {
            return 0;
        }
        return 1;
    },
    checkPhone:function(str){
        if (!(/^1[0-9]{10}$/.test(str))) {
            return 0;
        }
        return 1;

    },
    getOrder:function(){
       return this.order_sn = $("#order_sn").html();
    },
    editShopBut:function(){
        
        var order_sn = $("#order_sn").html();
        var d = dialog({
            title: '修改商家',
            url:"/user/userorder/edit-shop?order_sn="+order_sn,
            ok:function(){
                window.location.reload();
            }
        })
        .width(720).height(520);
        d.show(document.getElementById('option-quickClose'));
    },
    editShop:function(e){
        var order_sn = parent.userOrder.getOrder();
        var shop_id = $(e).parent().parent().find(".shop_id").html();
        var _csrf = $("#csrf").val();
        $.post(
            '/user/userorder/edit-shop-up',
            {'order_sn':order_sn,'shop_id':shop_id,'_csrf':_csrf},
            function(data){
                console.log(data);
                if(data.code ==200){
                    alert(data.msg);
                }else{
                    alert(data.msg);
                }
            },
            'json'
        );
    }
}
