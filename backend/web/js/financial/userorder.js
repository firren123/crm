/**
 * Created by lichenjun on 15/7/22.
 */
var userOrder = {
    //test:function(){
    //    alert('test userOrder is used 11111');
    //},
    /**
     * 计算一个商品的总计
     */
    oneGoodsPrice: function (e) {
        var price = $(e).closest("tr").find(".price").html();
        var num = $(e).closest("tr").find(".num").val();
        (isNaN(num) && num>=0)?num : 0;
        price ? price : 0;
        if(this.priceType(price)==0){alert("价格格式错误");
            $(e).closest("tr").find(".price").val(0);
            return;}
        if(this.numType(num)==0){alert("数量格式错误");return;}

        var total = price * num;
        total=total.toFixed(2);
        $(e).closest("tr").find(".total").html(total);
    },
    allTotal:function (){
        var count =0;
        $('input[class="choice"]:checked').each(function() {
            var num = $(this).closest("tr").find(".total").html();
            num = Number(num);
            count=count+num;
        });
        var dis_amount = $("#dis_amount").text();
        dis_amount = Number(dis_amount);
        count = count+dis_amount;
        count = count<0?0:count;
        if(isNaN(count)){count=0};
        count=count.toFixed(2);
        $("#order-all-price").html(count);
    },
    priceType: function (str) {
        if (!(/^(([1-9]\d*)|0)(\.\d{1,2})?$/.test(str))) {
            return 0;
        }
        return 1;
    },
    numType:function (str){
        if (!(/^([0-9]\d*)?$/.test(str))) {
            return 0;
        }
        return 1;
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

    }
}