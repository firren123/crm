$(function()
{
    $(".zjs_radio").change(function()
    {
        var value=$(this).val();
        $(".zjs_tr_hide").hide();
        if (value == 1) {
            var is_position_change=parseInt($(".zjs_is_position_change").html());
            if(is_position_change==1){
                $(".zjs_tr_reset_community").show();
            }
        } else if (value == 2) {
            $(".zjs_tr_reason").show();
        } else {
        }
        $(".zjs_btn_submit").removeAttr("disabled");
    });


    $(".zjs_btn_submit").click(function()
    {
        submit();
    });


});

function submit()
{
    var status=$(".zjs_radio:checked").val();
    var shop_id=$(".zjs_shop_id").html();
    var csrf=$(".zjs_csrf").html();

    var is_position_change=parseInt($(".zjs_is_position_change").html());
    var is_reset_community=0;
    if(is_position_change==1 && $(".zjs_radio_1:checked").length>0){
        is_reset_community=$(".zjs_radio_1:checked").val();
    }else{
        is_reset_community=0;
    }


    var act='';
    var reason='';
    if(status=='1'){
        act='pass';
    } else if(status=='2'){
        act='reject';
        reason=$(".zjs_text_reason").val();
        if(reason.length>50){alert("驳回原因过长，限50字以内");return;}
    } else {
        return;
    }

    $.post
    (
        "/shop/shopverify/ajax-post",
        {
            "_csrf":csrf,
            "act":act,
            "shop_id":shop_id,
            "is_reset_community":is_reset_community,
            "reason":reason
        },
        function(str)
        {
            console.log(str);
            if(str=="1"){
                alert("操作成功");
                //console.log("操作成功");
                window.location.href="/shop/shopverify/list";
            } else {
                alert("操作失败");
                //console.log("操作失败");
            }
        }
    );
}







