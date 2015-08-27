/**
 * Created by lichenjun on 15/3/22.
 */
$(function(){
    /**
     * 全选取消
     */
    $("#all").click(function(){
        var r= $("#all").prop('checked');
        if(r == true){
            $('input[type=checkbox]').prop('checked', true);
        }else{
            $('input[type=checkbox]').prop('checked', false);
        }

    });

    /**
     * 点击按钮提交
     */



})
/**
 * 点击确认
 * @param id
 * @param status
 * @returns {boolean}
 */
function editStatus(id,status){

    if(status !=0){
        alert('已经确认过');
        return false;
    }
    $.ajax({
        type: "GET",
        url: "/shop/shoporder/edit",
        data: "order_sn="+id+"&status=1",
        dataType:"json",
        success: function(msg){
            if(msg.code==200){
                alert('确认成功');
                window.location.href = window.location.href;
                //location.replace(location);
            }else{
                alert('确认失败');
            }
        }
    });
    return false;
}

/**
 * 点击收货
 * @param id
 * @param status
 * @returns {boolean}
 */
function editShipStatus(id,status){
    if(status !=1){
        alert('还没有发货不能点击收货');
        return false;
    }
    $.ajax({
        type: "GET",
        url: "/shop/shoporder/edit",
        data: "order_sn="+id+"&ship_status=2",
        dataType:"json",
        success: function(msg){
            if(msg.code==200){
                alert('确认收货成功');
                window.location.href;
            }else{
                alert('确认收货失败');
            }
        }
    });
    return false;
}
/**
 * 点击收款
 * @param id
 * @param status
 * @returns {boolean}
 */
function editPayStatus(id,status){
    if(status ==1){
        alert('已经收款');
        return false;
    }
    $.ajax({
        type: "GET",
        url: "/shop/shoporder/edit",
        data: "order_sn="+id+"&paystatus=1",
        dataType:"json",
        success: function(msg){
            if(msg.code==200){
                alert('确认收款成功');
                window.location.href;
            }else{
                alert('确认收款失败');
            }
        }
    });
    return false;
}
function successBut(id,status){
    $.ajax({
        type:"GET",
        url :"/admin/userdiscuss/up",
        data:"id="+id+"&status="+status,
        dataType:"json",
        success:function(msg){
            if(msg == 1){
                alert('修改成功');
                window.location.href=window.location.href;
            }else{
                alert('修改失败');
            }
        }
    });
    return false;
}
