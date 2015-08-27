$(function()
{
    $(".zjs_btn_query").click(function()
    {
        query();
    });
});

function query()
{
    var status=$(".zjs_status").val();

    var url="/shop/shopverify/list?verify_status="+status+"";
    window.location.href=url;
}