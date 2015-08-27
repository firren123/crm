/**
 * Created by Administrator on 2015/8/11 0011.
 */
var order = {
    postAdd:function(){
        var min = $("#min").val();
        var min_unit = $("#min_unit").val();
        var max = $("#max").val();
        var number = $("#number").val();
        var csrf = $("#csrf").val();
        var validity = $("#validity").val();
        var status = $("#status").val();
        if (max=='') {
            gf.alert('上限值 不能为空');
            return false;
        }
        if (max<=0) {
            gf.alert('上限值 不能小于0');
            return false;
        }
        if (isNaN(max)) {
            gf.alert('上限值 必须是数字');
            return false;
        }
        if (min=='') {
            gf.alert('下限值 不能为空');
            return false;
        }
        if (min<=0) {
            gf.alert('下限值 不能小于0');
            return false;
        }
        if (isNaN(min)) {
            gf.alert('下限值 必须是数字');
            return false;
        }
        if (min_unit==2) {
            if (max>=100) {
                gf.alert('上限值 不能大于100');
                return false;
            }
            if (min>=100) {
                gf.alert('下限值 不能大于100');
                return false;
            }
        }
        if (parseInt(min)>parseInt(max)) {
            gf.alert('下限值 不能大于上限值');
            return false;
        }

        if (number<1) {
            gf.alert('最多领取数量 不能小于1');
            return false;
        }
        if (validity<=0) {
            gf.alert('优惠券有效期 必须大于0');
            return false;
        }
        $.post
        (
            "/user/ordersendcoupons/insert",
            {
                "_csrf":csrf,
                "min":min,
                "min_unit":min_unit,
                "max":max,
                "number":number,
                "validity":validity,
                "status" :status
            },
            function(obj)
            {
                if(obj.code==200){
                    gf.alert(obj.message);
                    window.location = "/user/ordersendcoupons";
                }else{
                    gf.alert(obj.message);
                }
            },
            'json'
        );
    },
    postUpdate:function(){
        var min = $("#min").val();
        var min_unit = $("#min_unit").val();
        var max = $("#max").val();
        var number = $("#number").val();
        var csrf = $("#csrf").val();
        var validity = $("#validity").val();
        var status = $("#status").val();
        if (max=='') {
            gf.alert('上限值 不能为空');
            return false;
        }
        if (max<=0) {
            gf.alert('上限值 不能小于0');
            return false;
        }
        if (isNaN(max)) {
            gf.alert('上限值 必须是数字');
            return false;
        }
        if (min=='') {
            gf.alert('下限值 不能为空');
            return false;
        }
        if (min<=0) {
            gf.alert('下限值 不能小于0');
            return false;
        }
        if (isNaN(min)) {
            gf.alert('下限值 必须是数字');
            return false;
        }
        if (min_unit==2) {
            if (max>=100) {
                gf.alert('上限值 不能大于100');
                return false;
            }
            if (min>=100) {
                gf.alert('下限值 不能大于100');
                return false;
            }
        }
        if (parseInt(min)>parseInt(max)) {
            gf.alert('下限值 不能大于上限值');
            return false;
        }

        if (number<1) {
            gf.alert('最多领取数量 不能小于1');
            return false;
        }
        if (validity<=0) {
            gf.alert('优惠券有效期 必须大于0');
            return false;
        }
        $.post
        (
            "/user/ordersendcoupons/update",
            {
                "_csrf":csrf,
                "min":min,
                "min_unit":min_unit,
                "max":max,
                "number":number,
                "validity":validity,
                "status" :status
            },
            function(obj)
            {
                if(obj.code==200){
                    gf.alert(obj.message);
                    window.location = "/user/ordersendcoupons";
                }else{
                    gf.alert(obj.message);
                }
            },
            'json'
        );
    }
};
