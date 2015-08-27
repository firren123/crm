/**
 * Created by Administrator on 2015/4/21.
 */
function Delete(id){
    var msg = "您真的确定要删除吗？";
    if (confirm(msg)==true){
        $.ajax(
            {
                type: "GET",
                url: '/goods/category/delete',
                data: {'id':id},
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
clickCheckbox();
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
 * 判断是否选中
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
                url: "/goods/category/ajax-delete",
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
        alert('请选择要删除项');
        return false;
    }
}