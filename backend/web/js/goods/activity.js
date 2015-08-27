/**
 * Created by lichenjun on 15/6/1.
 */
/**
 * Created by lichenjun on 15/5/28.
 */
var activity = {

    si_address:'',
    val_province :'',
    val_city:'',
    val_district:'',
    start_time:'',
    end_time:'',
    getVal:function(){
        this.is_address = $("#is_address").val();
        this.val_province = $("#val_province").val();
        this.val_city = $("#val_city").val();
        this.val_district = $("#val_district").val();
        this.start_time = $("#start_time").val();
        this.end_time = $("#end_time").val();
        return this;
    },
    //初始化三级联动
    childCate:function(){
        this.is_address = $("#hide_area_statua").val();
        this.val_province = $("#hide_province").val();
        this.val_city = $("#hide_city").val();
        this.val_district = $("#hide_district").val();

        this.isAddress(this.is_address);
        this.selectList(this.val_province,2);
        this.selectList(this.val_city,3);

    },
    /**
     * 删除订单中商品
     */
    goodsDel: function (e) {
        $(e).closest("tr").remove();
    },
    check:function(d){
        //d.end_time = d.end_time.substr(0,10);
        //d.end_time += ' 23:59:59';
        //$('#end_time').val(d.end_time);
        var msg = "";
        if((d.goods_name != undefined && d.goods_name =='') || (d.id != undefined && d.id =='')){
            msg += '商品不能为空\n';
        }

        if(d.start_time ==""){
            msg += '开始不能为空\n';
        }
        if(d.end_time ==""){
            msg += '结束时间不能为空\n';
        }
        if(d.start_time > d.end_time){
            msg += '开始时间不能大于等于结束时间\n';
        }
        console.log(d.price);
        if(d.price != undefined && (d.price =='' || !this.priceType(d.price))){
            msg += '价格不能为空或者格式错误\n';
        }
        console.log(d.day_num);
        //if(d.day_num == undefined  || !this.numType(d.day_num)){
        //    msg += '每日限购不能为空或者格式错误\n';
        //}

        if(msg.length > 0){
            alert(msg);
            return false;
        }
        return true;
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
    addGoods1:function(data){
        console.log(data);
            var d = '<tr class="goods_list1">';
            d += '<td class="goods_name">'+data.goods_name+'</td>';
            //d += '<td class="total">'+data.total+'</td>';
            d += '<td class="day_num">'+data.day_num+'</td>';
            d += '<td class="price">'+data.price+'</td>';
            d += '<td class="start_time">'+data.start_time+'</td>';
            d += '<td class="end_time">'+data.end_time+'</td>';
            d += '<td class="del"><a href="" class="goods_del1">删除</a>' +
            '<input type="hidden" class="goods_id1" name="goods_id1[]" value="'+data.id+'" />' +
            "<input type='hidden' name='goods1_json[]' value='"+JSON.stringify(data) +"' />" +
            '</td>';
            d += '</tr>';
            var t =0;
            $(".goods_id1").each(function(){

                var num = Number($(this).val());
                if(num == data.id) {
                    t = t + 1;
                }

            });

            if(t==0){
                var ad =$("#_buy_after_goods");
                ad.before(d);
            }
            activity.allNUm(".goods_list1",".goods_list1_num");
    },
    childAddGoods1:function(id){
        parent.activity.addGoods1(id);

    },
    addGoods2:function(data){
            var d = '<tr class="goods_list2">';
            d += '<td class="goods_name">'+data.goods_name+'</td>';
            //d += '<td class="total">'+data.total+'</td>';
            d += '<td class="day_num">'+data.day_num+'</td>';
            d += '<td class="start_time">'+data.start_time+'</td>';
            d += '<td class="end_time">'+data.end_time+'</td>';
            d += '<td class="del"><a href="" class="goods_del2">删除</a>' +
            '<input type="hidden" class="goods_id2" name="goods_id2[]" value="'+data.id+'" />' +
            "<input type='hidden' name='goods2_json[]' value='"+JSON.stringify(data) +"' />" +
            '</td>';
            d += '</tr>';

            var t =0;
            $(".goods_id2").each(function(){
                var num = Number($(this).val());
                if(num == data.id) {
                    t = t + 1;
                }
            });
            if(t==0){
                var ad =$("#_gift_after_goods");
                ad.before(d);
            }
            activity.allNUm(".goods_list2",".goods_list2_num");
    },
    childAddGoods2:function(id){

        parent.activity.addGoods2(id);

    },
    addShop:function(id){
        $.get("/goods/activity/get-shop-info",{'id':id},function(data){
            var d ='<tr class="shop_list">';
            d +='<td class="id">'+data.id+'</td>';
            d +='<td class="shop_name">'+data.shop_name+'</td>';
            d +='<td class="manage_type">'+data.manage_type+'</td>';
            d +='<td class="del"><a href="" class="shop_del">删除</a>' +
                '<input type="hidden" class="shop_id_val" name="shop_id[]" value="'+data.id+'">' +
            '</td>';
            d +='</tr>';
            var t =0;
            $(".shop_id_val").each(function(){
                var num = Number($(this).val());
                if(num == data.id) {
                    t = t + 1;
                }
            });
            if(t==0){
                var ad =$("#after_shop");
                ad.before(d);

            }
            activity.allNUm(".shop_list","#shop_num");
        },"json");
    },
    childAddShop:function(id){
        parent.activity.addShop(id);
    },

    isAddress:function(val){
       var p = $("#act_province");
        var c = $("#act_city");
        var d = $("#act_district");
        var s = $("#add_shop_list");
        c.hide();
        d.hide();
        s.hide();
       switch (val){
           case '1':
               break;
           case '2':
               c.show();
               break;
           case '3':
               c.show();
               d.show();
               break;
           case '4':
               c.show();
               d.show();
               s.show();
               break;
           default :
               break;
       }
    },
    //省市区三级联动
    selectList:function(id,type){
        var city =(this.val_city);
        var district = (this.val_district);
        var url = '/goods/activity/select-list';
        $.get(
            url,
            {'id':id,'type':type},
            function(data){
                if(data.code ==200){
                    var html = '<option value="">请选择 </option>';
                    var len = data.data.length;
                    for(var i in data.data){

                        if((i==city && type==2) || (i==district && type==3)){
                            html += '<option value="'+i+'" selected >'+data.data[i]+' </option>';
                        }else{
                            html += '<option value="'+i+'">'+data.data[i]+' </option>';
                        }
                    }
                    if(type == 2){
                        $("#val_city").html("");
                        $("#val_city").html(html);
                    }
                    if(type == 3){
                        $("#val_district").html("");
                        $("#val_district").html(html);
                    }

                }else{
                    alert('网络错误');
                }
            },
            'json'
        );
    },
    allNUm:function(e,n){
        var num = $(e).length;
        $(n).html(num);

    },
    subOk:function(){
        var error = "";
        var name = $("#name").val();
        if(name.length == 0){
            error +='活动名称不能为空\n';
        }
        if(name.length >20){
            error += '活动名称不能大于20个字\n';
        }
        var type = $("#type").val();
        if(type.length == 0){
            error +='活动类型不能为空\n';
        }

        var register_start_time = $("#register_start_time").val();
        if(register_start_time.length == 0){
            error +='报名开始时间不能为空\n';
        }
        var register_end_time = $("#register_end_time").val();
        if(register_end_time.length == 0){
            error +='报名结束不能为空\n';
        }
        if(register_end_time < register_start_time){
            error +='报名结束不能小于报名开始时间\n';
        }
        var start_time = $("#start_time").val();
        if(start_time.length == 0){
            error +='活动开始时间不能为空\n';
        }
        var end_time = $("#end_time").val();
        if(end_time.length == 0){
            error +='活动结束时间不能为空\n';
        }
        if(end_time < start_time){
            error +='结束不能小于开始时间\n';
        }
        if(register_end_time > start_time){
            error +='报名结束时间必须小于活动开始时间\n';
        }
        var status = $("input:radio[name='status']:checked").val();
        if(status == null){
            error +='活动有效性不能为空\n';
        }
        var pay_type = $("input:checkbox[name='pay_type[]']:checked").val();
        if(pay_type == null){
            error +='支付方式限定不能为空\n';
        }
        var platform = $("input:checkbox[name='platform[]']:checked").val();
        if(platform == null){
            error +='活动平台不能为空\n';
        }
        var new_user_site = $("input:radio[name='new_user_site']:checked").val();
        if(new_user_site == null){
            error +='新用户限定不能为空\n';
        }
        var display = $("input:radio[name='display']:checked").val();
        if(display == null){
            error +='活动页面链接状态不能为空\n';
        }
        var display_url = $("input[name='display_url']").val();
        if(display_url.length == 0 && display==1){
            error +='活动页面链接地址不能为空\n';
        }
        var confine_num = $("input:radio[name='confine_num']:checked").val();
        if(confine_num == null){
            error +='参与次数限制状态不能为空\n';
        }
        var confine_num2 = $("input[name='confine_num2']").val();
        if(confine_num2.length == 0 && display==1){
            error +='参与次数限制数量不能为空\n';
        }
        var sort = $("input[name='sort']").val();
        if(sort.length == 0){
            error +='活动排序不能为空\n';
        }
        var images1 = $("#images1").val();
        if(images1.length == 0){
            error +='必须上传第一张图片\n';
        }
        var is_address = $("#is_address").val();
        if(is_address == ''){
            error +='区域限定范围不能为空\n';
        }
        var val_province = $("#val_province").val();
        if(is_address == 1 && val_province==''){
            error +='省份不能为空\n';
        }
        var val_city = $("#val_city").val();
        if(is_address == 2 && val_city==''){
            error +='市不能为空\n';
        }
        var val_district = $("#val_district").val();
        if(is_address == 3 && val_district==''){
            error +='区/县不能为空\n';
        }
        var shop_num = $("#shop_num").html();
        if(is_address == 4 && shop_num=='0'){
            error +='商家不能为空\n';
        }
        var meet_amount = $("input:radio[name='meet_amount']:checked").val();
        if(meet_amount == null){
            error +='活动金额设定状态不能为空\n';
        }
        var meet_amount2 = $("input[name='meet_amount2']").val();
        if(meet_amount2.length == 0 && meet_amount==1){
            error +='活动金额设定数量不能为空\n';
        }
        var describe = $("textarea[name='describe']").val();
        if(describe.length == 0 ){
            error +='活动说明不能为空\n';
        }
        var goods_type = $("input:radio[name='goods_type']:checked").val();
        if(goods_type == null){
            error +='活动商品设定类型不能为空\n';
        }
        var goods_cate = $("#goods_cate").val();
        if(goods_cate.length == 0 && goods_type==1){
            error +='活动商品分类不能为空\n';
        }
        var goods_list1_num = $(".goods_list1_num").html();
        if(goods_list1_num =='0' && goods_type==0){
            error +='商品数不能为空\n';
        }
        var gift_type = $("input:radio[name='gift_type']:checked").val();
        if(gift_type == null){
            error +='赠送类型不能为空\n';
        }
        //已经选择的商品开始时间
        $(".goods_list1>.start_time").each(function(){
            var goods_time = $(this).text();

            var goods_name = $(this).closest("tr").find(".goods_name").text();
            console.log(goods_name);
            if(start_time > goods_time){
                console.log("start_time="+start_time+";goods_time="+goods_time);
                error += goods_name+"商品开始时间不能小于活动开始时间\n";
            }

        });
        //已经选择的商品结束时间
        $(".goods_list1>.end_time").each(function(){
            var goods_time = $(this).text();
            var goods_name = $(this).closest("tr").find(".goods_name").text();
            console.log(goods_name);
            if(end_time < goods_time){
                console.log("start_time="+start_time+";goods_time="+goods_time);
                error += goods_name+"商品结束时间不能大于活动结束时间\n";
            }

        });
        var goods_list2_num = $(".goods_list2_num").html();
        if(goods_list2_num == '0' && gift_type==0){
            error +='赠品数不能为空\n';
        }
        var coupons_id = $("#coupons_id").val();
        if(coupons_id.length == 0 && gift_type==1){
            error +='赠品优惠券不能为空\n';
        }
        if(goods_list2_num != '0' && gift_type==0) {
            //已经选择的赠品商品开始时间
            $(".goods_list2>.start_time").each(function () {
                var goods_time = $(this).text();
                var goods_name = $(this).closest("tr").find(".goods_name").text();
                console.log(goods_name);
                if (start_time > goods_time) {
                    error += goods_name + "赠品开始时间不能小于活动开始时间\n";
                }

            });
            //已经选择的商品结束时间
            $(".goods_list2>.end_time").each(function () {
                var goods_time = $(this).text();
                var goods_name = $(this).closest("tr").find(".goods_name").text();
                console.log(goods_name);
                if (end_time < goods_time) {
                    error += goods_name + "赠品结束时间不能大于活动结束时间\n";
                }

            });
        }






        if(error.length >0){
            alert(error);
            return false;
        }
        return true;


    },
    mvCursor:function(e){
        var val = $(e).val();
        var id = $(e).attr("id");
        if(val ==''){
            return;
        }
        var register_start_time = $("#register_start_time").val();
        var register_end_time = $("#register_end_time").val();
        var start_time = $("#start_time").val();
        var end_time = $("#end_time").val();
        //console.log('register_start_time='+register_start_time);
        //console.log('register_end_time='+register_end_time);
        //console.log('start_time='+start_time);
        //console.log('end_time='+end_time);


        switch (id){
            case "register_start_time":
                $("div .register_start_time").html('');
                if(register_end_time < register_start_time && register_end_time){
                    var msg = '报名开始时间不能大于报名结束时间';
                    $("div .register_start_time").html('<span class="red">'+msg+'</span>');
                    break;
                }

                break;
            case "register_end_time":
                $("div .register_end_time").html('');
                $("div .register_start_time").html('');
                if(register_end_time < register_start_time && register_start_time){
                    var msg = '报名开始时间不能大于报名结束时间';
                    $("div .register_start_time").html('<span class="red">'+msg+'</span>');
                    break;

                }
                if(start_time < register_end_time && start_time){
                    var msg = '报名结束时间不能大于活动开始时间';
                    $("div .register_end_time").html('<span class="red">'+msg+'</span>');
                    $("div .start_time").html('<span class="red">'+msg+'</span>');
                    break;

                }
                break;
            case "start_time":
                $("div .start_time").html('');
                $("div .register_end_time").html('');
                if(start_time < register_end_time && register_end_time){
                    var msg = '活动开始时间不能小于报名结束时间';
                    $("div .start_time").html('<span class="red">'+msg+'</span>');
                    $("div .register_end_time").html('<span class="red">'+msg+'</span>');
                    break;
                }
                if(start_time > end_time && end_time){
                    var msg = '活动开始时间不能大于活动结束时间';
                    $("div .end_time").html('<span class="red">'+msg+'</span>');
                    break;
                }
                break;
            case "end_time":
                $("div .end_time").html('');
                if(start_time > end_time && start_time){
                    var msg = '活动结束时间不能小于活动开始时间';
                    $("div .end_time").html('<span class="red">'+msg+'</span>');
                    break;
                }
                break;
            default :
                break;
        }
    },
    airBubbles:function(id,msg){
        var msg = msg ==undefined?'不能为空':msg;
        console.log("msg="+msg);
        var d = dialog({
            content: '<span class="red">'+msg+'</span>',
            align: 'bottom',
            quickClose: true// 点击空白处快速关闭
        });
        d.show(document.getElementById(id));
    }


}