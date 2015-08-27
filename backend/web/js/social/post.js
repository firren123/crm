/**
 * Created by lichenjun on 15/8/17.
 */
var post = {
    ids:'',
    forum_list:[],
    'test':function(){
        //alert('test');
    },
    /**
     * 批量转移
     */
    'remove':function(){
        var forum_id = $('#forum_id3').val();
        var token = $('.zjs_csrf').html();
        var ids = $('input[id="brandid"]:checked').map(function () {
            return this.value
        }).get().join();
        console.log(ids);
        if(ids == undefined || ids =='' ||ids ==0){
            alert('请选择话题~');
            return false;
        }
        if(forum_id == undefined || forum_id == '' || forum_id ==0){
            alert('请选择转移板块');
            return false;
        }
        $.post(
            'remove',
            {'forum_id':forum_id,'_scrf':token,'ids':ids},
            function(data){
                if(data == 1){
                    alert('转移成功');
                    window.location.reload();
                }else{
                    alert('转移失败');
                }
            }
        );
    }
};
