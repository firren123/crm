/**
 * Created by neng on 15/4/1.
 */
var common = {
    //全选
    checkAll:function(obj){

            var re= $(obj).prop('checked');
            if(re == true){
                $('input[type=checkbox]').prop('checked', true);
            }else{
                $('input[type=checkbox]').prop('checked', false);
            }


    }
}
var gf = {
    alert:function(mess){
        var d = dialog({
            title: '警告',
            content: '<p style="padding: 0 50px">'+mess+'</p>',
            ok: function () {}
            //statusbar: '<label><input type="checkbox">不再提醒</label>'
        });
        d.showModal();

    },
    go:function(){
        window.history.go(-1);
    },
    confirm1:function(mess){

        return confirm(mess);

    },
    confirm:function(mess,method){
        var d = dialog({
            title: '提示',
            content: '<p style="padding: 0 50px">'+mess+'</p>',
            okValue: '确定',
            ok: function () {
                this.title('提交中…');
                eval(method);
                d.remove();


                return false;
            },
            cancelValue: '取消',
            cancel: function () {}
        });
        d.showModal();
    }
    //abc:function(){
    //    alert(113);
    //}
}