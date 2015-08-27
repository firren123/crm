/**
 * Created by lichenjun on 15/5/28.
 */
var order = {
    /**
     * 删除订单中商品
     */
    goodsDel: function (e) {
        $(e).closest("tr").remove();
    },
    /**
     * 计算一个商品的总计
     */
    oneGoodsPrice: function (e) {
        var price = $(e).closest("tr").find(".price").val();
        var num = $(e).closest("tr").find(".num").val();
        (isNaN(num) && num>=0)?num : 0;
        price ? price : 0;
        if(this.priceType(price)==0){alert(price+"价格格式错误");
            $(e).closest("tr").find(".price").val(0);
            return;}
        if(this.numType(num)==0){alert(this.numType(num)+"数量格式错误");return;}

        var total = price * num;
        total=total.toFixed(2);
        $(e).closest("tr").find(".total").val(total);

    },
    allTotal:function (){
        var count =0;
        $(".total").each(function(){
            var num = Number($(this).val());
            count=count+num;
        });
        console.log(typeof(count));
        if(isNaN(count)){count=0};
        $("#order-all-price").html(count);
        console.log(count);
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
    addGoods:function(id){
        //parent.addGoods(id);
        $.get("/supplier/supplierorder/get-goods",{'id':id},function(data){
            var d = '<tr><td>'+data.id+'</td><td>'+data.sub_title+'</td><td>'+data.attr_value+'</td><td>'+data.unit+'</td>';
            d += '<td><input type="text" name="goods['+data.id+'][price]" class="price" value="'+data.supply_price+'"/></td>';
            d += '<td><input type="text" name="goods['+data.id+'][num]" class="num" value="1"/></td>';
            d += '<td><input type="text" name="goods['+data.id+'][total]" class="total" value="'+data.supply_price+'"/></td>';
            d += '<td><input type="text" name="goods['+data.id+'][remark]" class="remark" value=""/></td>';
            d += '<td>' +
            '<a href="#" class="goods-del">删除</a>' +
                '<input type="hidden" name="goods['+data.id+'][id]" class="good_id" value="'+data.id+'"/>'+
            '</td>';
            d += '</tr>';
            var t =0;
            $(".good_id").each(function(){
                var num = Number($(this).val());
                //console.log("num="+num);
                //console.log("id="+data.id);
                if(num == data.id) {
                    t = t + 1;
                    //console.log('重复添加'+data.id);
                }

            });
            if(t==0){
                var ad =$("#add");
                ad.before(d);
                parent.order.allTotal();
            }
        },"json");

    },
    childAddGoods:function(id){
        parent.order.addGoods(id);

    },
    addCom:function(id){
        $.get("/supplier/supplierorder/get-supplier-info",{'id':id},function(data){
            $("#sp-name").html(data.company_name);
            $("#sp-line-man").html(data.contact);
            $("#sp-email").html(data.email);
            $("#sp-phone").html(data.mobile+'    '+data.phone);
            //$("#sp-phone").html(data.phone);
            $("#sp_id").val(data.id);
            $("#company_name").val(data.company_name);




        },"json");
    },
    childAddCom:function(id){
        parent.order.addCom(id);
        d.close().remove();
    },
    getWareHouse:function(id){
        $.get("/supplier/supplierorder/get-ware-house",{'bc_id':id},function(data){
            console.log(data);
            var html_option = '';
            var len=data.length;
            $("#wareHouse").empty();
            var html_option="";
            for(var i=0;i<len;i++)
            {
                html_option+='<option value="'+data[i]['id']+'">'+data[i]['name']+'</option>';
            }
            $("#wareHouse").append(html_option);
        },"json");
    },
    check:function(){
        var sp_id = $("#sp_id").val();
        var pay_site_id = $("#pay_site_id").val();
        var arrange_receive_time = $("#arrange_receive_time").val();
        var ware_house_id = $("#wareHouse").val();
        if(!sp_id){
            alert('请选择供应商');
            return false;
        }

        if(!arrange_receive_time){
            alert('请填写交货日期');
            return false;
        }
        if(!ware_house_id){
            alert('选择交货地');
            return false;
        }
        if(!pay_site_id){
            alert('请选择支付方式');
            return false;
        }
    },
    getOrderDetail:function(order_sn){
        $.get("/supplier/supplierorder/order-detail",{'order_sn':order_sn},function(data){
            var d = '';
            var len=data.length;
            $(".detail_opt").empty();
            for(var i=0;i<len;i++){
                d += '<tr class="detail_opt"><td>'+data[i]['id']+'</td><td>'+data[i]['name']+'</td>' +
                '<td>'+data[i]['attr_value']+'</td><td>'+data[i]['attribute_str']+'</td>';
                d += '<td>'+data[i]['price']+'</td><td>'+data[i]['num']+'</td><td>'+data[i]['total']+'</td>' +
                '<td>'+data[i]['info']+'</td>';

                d += '</tr>';
            }
            var ad =$("#add_order_detail");

            if(len == 0) {
                $("#empty_detail").show();
            }else{
                $("#empty_detail").hide();
                ad.after(d);
            }

        },"json");
    }
}