<?php
/**
 * 导入excel-view
 *
 * PHP Version 5
 *
 * @category  ADMIN
 * @package   CONTROLLER
 * @author    zhengyu <zhengyu@iyangpin.com>
 * @time      15/4/24 18:04
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      zhengyu@iyangpin.com
 */

$this->title = "小区信息导入";
?>

<script src="/js/jquery-1.10.2.min.js"></script>
<script src="/js/json2.js"></script>
<script src="/js/zcommon.js"></script>

<style type="text/css">


</style>




<div>
    <div class="">
        <form action="/admin/community/import?act=import_excel" method="post" enctype="multipart/form-data" id="form_upload_excel" name="form_upload_excel" target="hidden_iframe">
            <div class="" style="margin:30px 0;">
                <select class="zjs_select_province" name="province_id">
                    <option value="0">请选择省</option>
                    <?php foreach($arr_select_province as $a_provice){ ?>
                        <option value="<?php echo $a_provice['id']; ?>"><?php echo $a_provice['name']; ?></option>
                    <?php } ?>
                </select>

                <select class="zjs_select_city" name="city_id">
                    <option value="1" class="zjs_default_v">请选择城市</option>
                </select>

                <select class="zjs_select_district" name="district_id" style="display:none;">
                    <option value="0" class="zjs_default_v">请选择区/县</option>
                </select>
            </div>


            <span><b>选择excel文件(文件体积小于2M)：</b></span>
            <input type="file" size="20" class="" id="z_input_file" name="z_input_file" />


            <input type="hidden" class="zjs_csrf" name="_csrf" value="<?php echo \Yii::$app->getRequest()->getCsrfToken(); ?>" />
            <input type="hidden" name="act" value="import_excel" />


            <input type="button" value="导入数据" class="js_btn_upload zcss_btn" style="width:100px;height:28px;margin:30px 0;" />
        </form>

        <iframe name="hidden_iframe" id="hidden_iframe" style="display:none;"></iframe>
    </div>

    <div class="zjs_import_result" style="border:1px solid #ccc;margin:10px 0 0 0;display:none;">
    </div>
</div>




<script type="text/javascript">
$(function()
{
    $(".js_btn_upload").click(function()
    {
        $("#form_upload_excel").submit();
    });
});


function upload_return(msgtype,msg)
{
    //console.log(msgtype);
    //console.log(msg);
    //return;
    $(".zjs_import_result").html("").show();
    if(msgtype==1){
        var obj=JSON.parse(msg);
        //console.log(obj);
        $(".zjs_import_result").append("<div>数据导入结果如下:</div>");
        $(".zjs_import_result").append("<div>导入总记录数:"+obj.all_num+"</div>");
        $(".zjs_import_result").append("<div>导入成功记录数:"+obj.succ_num+"</div>");
        $(".zjs_import_result").append("<div>导入失败记录数:"+obj.fail_num+"</div>");
        if(obj.error_info.length > 0){
            var len=obj.error_info.length;
            var obj2=obj.error_info;
            for(var i=0;i<len;i++){
                $(".zjs_import_result").append("<div>id="+obj2[i].id+",name="+obj2[i].name+",info="+obj2[i].info+"</div>");
            }
        }
    }
    else if(msgtype==0){
        $(".zjs_import_result").append("<div>"+msg+"</div>");
    }
    else{}

}
</script>




