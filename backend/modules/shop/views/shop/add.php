<?php
/**
 * 添加-view
 *
 * PHP Version 5
 *
 * @category  ADMIN
 * @package   CONTROLLER
 * @author    zhengyu <zhengyu@iyangpin.com>
 * @time      15/4/22 10:56
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      zhengyu@iyangpin.com
 */


$map_field_name = array(
    'username' => '用户名',
    'password' => '密码',
    'shop_name' => '店铺名称',
    'contact_name' => '联系人',
    'email' => '邮箱',
    'mobile' => '手机',
    'phone' => '电话',
    'branch_id' => '分公司',
    'province' => '省',
    'city' => '市',
    'district' => '区/县',
    'address' => '店铺地址',
    'htnumber' => '合同号',
    'manage_type' => '店铺类型',
    'is_i500' => 'i500专营',
    'hours' => '店铺营业时间',
    'hours_remarks' => '营业时间备注',
    'introduction' => '店铺介绍',

    'status' => '状态',

    'logo' => '店铺LOGO',
    'business_id' => '业务员',
    'takepoint' => '是否是自提点',
    //'business_status' => '营业状态',

    'alipay' => '支付宝账号',
    //'chinapay' => '银联卡号',
    //'account_name' => '户名',
    //'bank_deposit' => '开户行',

    'sent_fee' => '起送费',
    'free_money' => '满多少免运费',
    'freight' => '运费',

    //'login_time' => '最后登录时间',
    //'login_ip' => '最后登录IP',
    //'create_time' => '添加时间',
    //'update_time' => '更新时间',
    //'recode_time' => '最后回访时间',
    //'owner_id' => '业主id',
    //'score' => '评分',
    //'comment_count' => '评论总数',
    //'workflow' => '激活步骤',
    'position_x' => '经度',
    'position_y' => '纬度',
    'show_position_map' => '坐标',
);

//$arr_field_require = array(
//    'username', 'password', 'shop_name', 'address', 'manage_type',
//    'position_x', 'position_y', 'hours', 'logo', 'branch_id',
//    'province', 'city', 'district', 'business_status'
//);
$arr_field_require = array(
    'username', 'password', 'shop_name', 'address', 'manage_type',
    'position_x', 'position_y', 'branch_id', 'business_status',
    'province', 'city', 'district', 'business_id', 'contact_name',
    'phone', 'mobile'
);
//$arr_field_require = array();

$this->title = "添加商家";

$arr_bank = array(
    '中国工商银行', '中国农业银行', '中国建设银行', '中国银行', '招商银行',
    '交通银行', '中国民生银行', '兴业银行', '深圳发展银行', '北京银行',
    '华夏银行', '广发银行', '浦发银行', '中国光大银行', '广州银行',
    '上海农商银行', '中国邮政储蓄银行', '中国邮政', '渤海银行', '北京农商银行',
    '南京银行', '中信银行', '宁波银行', '平安银行', '杭州银行',
    '安徽银行', '浙商银行', '上海银行', '江苏银行', 'BEA东亚银行'
);
?>

<!--<link rel="stylesheet" type="text/css" href="/js/jquery-ui-1.11.4/jquery-ui.min.css" />-->

<script src="/js/jquery-1.10.2.min.js"></script>
<!--<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script>window.jQuery || document.write('<script src="//jqueryui.com/jquery-wp-content/themes/jquery/js/jquery-1.11.2.min.js"><\/script>')</script>-->
<script src="/js/json2.js"></script>
<script src="/js/zcommon.js"></script>
<script src="/js/shopcommon.js"></script>

<style type="text/css">
.zcss_table1 th{font-weight:bold;}
.zcss_table1 th,.zcss_table1 td{border:1px solid #000000;padding:5px;}

.zcss_td_left{width:120px;}
.zcss_td_right{width:650px;}
.zcss_table1 tr>td>input{width:95%;}

.zcss_class0401{margin:30px 0;}
.zcss_class0401 a{padding:0 20px;}

.zcss_field_require{color:#FF0000;padding:0 0 0 10px;font-family:"sans serif", tahoma, verdana, helvetica;font-size:16px;font-weight:bold;}
</style>



<div>

    <ul class="breadcrumb">
        <li><a href="/">首页</a></li>
        <li class="active"><a href="/shop/shop" style="color:#286090;">商家管理</a></li>
        <li class="active">添加</li>
    </ul>

    <table class="zcss_table1"><tbody>

    <?php foreach($map_field_name as $key=>$value){ ?>
        <tr>
            <td class="zcss_td_left"><?php echo $value; ?><?php if(in_array($key,$arr_field_require)){echo "<span class=\"zcss_field_require\">*</span>";} ?></td>
            <td class="zcss_td_right">


        <?php if($key=='manage_type'){ ?>
            <select class="zjs_select_manage_type">
                <option value="0">未选择</option>
                <?php foreach($arr_all_manage_type_list as $value){ ?>
                    <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
                <?php } ?>
            </select>
        <?php }elseif($key=='takepoint'){ ?>
            <select class="zjs_select_takepoint">
                <?php foreach($map_take_point as $key=>$value){ ?>
                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                <?php } ?>
            </select>
        <?php }elseif($key=='business_status'){ ?>
            <select class="zjs_select_business_status">
                <?php foreach($map_business_status as $key=>$value){ ?>
                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                <?php } ?>
            </select>
        <?php }elseif($key=='branch_id'){ ?>
            <select class="zjs_select_branch_open_v4">
                <?php if($cur_admin_bcid==0 || sizeof($arr_all_branch_list)>1 || sizeof($arr_all_branch_list)==0){ ?>
                    <option value="0">未选择</option>
                <?php } ?>
                <?php foreach($arr_all_branch_list as $value){ ?>
                    <option value="<?php echo $value['id']; ?>" <?php if($cur_admin_bcid==$value['id']){echo " selected=\"selected\" ";} ?>><?php echo $value['name']; ?></option>
                <?php } ?>
            </select>
        <?php }elseif($key=='province'){ ?>
            <select class="zjs_select_province_open_v4">
                <?php if($cur_admin_bcid==0 || sizeof($arr_all_province_list)==0){ ?>
                    <option value="0">请选择省</option>
                <?php } ?>
                <?php foreach($arr_all_province_list as $a_provice){ ?>
                    <option value="<?php echo $a_provice['id']; ?>" <?php if($cur_admin_pid==$a_provice['id']){echo " selected=\"selected\" ";} ?>><?php echo $a_provice['name']; ?></option>
                <?php } ?>
            </select>
        <?php }elseif($key=='city'){ ?>
            <select class="zjs_select_city_v4">
                <?php if(sizeof($arr_city_list)==0){ ?>
                    <option value="0" class="zjs_default_v">请选择城市</option>
                <?php } ?>
                <?php foreach($arr_city_list as $value){ ?>
                    <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
                <?php } ?>
            </select>
        <?php }elseif($key=='district'){ ?>
            <select class="zjs_select_district_v4">
                <?php if(sizeof($arr_district_list)==0){ ?>
                    <option value="0" class="zjs_default_v">请选择区/县</option>
                <?php } ?>
                <?php foreach($arr_district_list as $value){ ?>
                    <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>
                <?php } ?>
            </select>
        <?php }elseif($key=='logo'){ ?>
            <div class="">
                <form action="/shop/shop/ajax" method="post" enctype="multipart/form-data" id="form_upload_img" name="form_upload_img" target="hidden_iframe">
                    <span><b>选择图片：</b></span>
                    <input type="file" size="20" class="" id="z_input_file" name="z_input_file" style="" />
                    <input type="hidden" class="zjs_csrf" name="_csrf" value="<?php echo \Yii::$app->getRequest()->getCsrfToken(); ?>" />
                    <input type="hidden" name="act" value="uploadimg" />
                    <input type="button" value="上传图片" class="js_btn_upload_img zcss_btn" style="width:100px;height:28px;" />
                </form>

                <iframe name="hidden_iframe" id="hidden_iframe" style="display:none;"></iframe>
            </div>
            <div class="zjs_area_upload_new_img">
                <img src="" class="zjs_logo_full_url" style="max-height:100px;max-width:100px;" />
                <input type="hidden" class="zjs_logo" value="" />
            </div>

        <?php }elseif($key=='business_id'){ ?>
            <span>输入id或姓名进行查询:</span>
            <input type="text" class="zjs_input_salesman_kw" style="width:70px;" />
            <input type="button" class="zjs_btn_query_salesman" value="查询" style="width:70px;" />
            &nbsp;&nbsp;&nbsp;&nbsp;
            <span>选择业务员:</span>
            <select class="zjs_select_salesman">
                <option value="0">请先进行查询</option>
            </select>
        <?php }elseif($key=='show_position_map'){ ?>

            <!--获取坐标 start-->
            <style type="text/css">
                #z_id_div_map{height:400px;width:100%;border:1px solid #bcbcbc;}
            </style>
            <script type="text/javascript" src="http://api.map.baidu.com/api?v=1.4"></script>
            <div>
                <p>请点击位置获取坐标。若找不到，可到 <a href="http://api.map.baidu.com/lbsapi/getpoint/index.html" target="_blank">百度拾取</a> 获取坐标，并把坐标复制粘贴到上面的文本框中</p>
                <div id="z_id_div_map"></div>
            </div>
            <script type="text/javascript">
                var x=116.417321;
                var y=39.887979;
                // 百度地图API功能
                var map = new BMap.Map("z_id_div_map");//创建Map实例
                var point = new BMap.Point(x, y);
                map.centerAndZoom(point, 18);
                var mk = new BMap.Marker(point);
                map.addOverlay(mk);//加标记
                mk.enableDragging();//marker可拖拽
                mk.addEventListener("dragend", function(e){
                    $(".zjs_position_x").val(e.point.lng);
                    $(".zjs_position_y").val(e.point.lat);
                });
                map.enableScrollWheelZoom();
                map.addEventListener("click",function(e){
                    $(".zjs_position_x").val(e.point.lng);
                    $(".zjs_position_y").val(e.point.lat);
                });
            </script>
            <!--获取坐标 end-->

        <?php }elseif($key=='hours'){ ?>
            <select class="zjs_hours_start">
                <option value="-1">加载中</option>
            </select>
            时~
            <select class="zjs_hours_end">
                <option value="-1">加载中</option>
            </select>
            时

        <?php }elseif($key=='sent_fee'){ ?>
            <input type="text" class="zjs_<?php echo $key; ?>" value="19.00" />
        <?php }elseif($key=='free_money'){ ?>
            <input type="text" class="zjs_<?php echo $key; ?>" value="50.00" />
        <?php }elseif($key=='freight'){ ?>
            <input type="text" class="zjs_<?php echo $key; ?>" value="5.00" />
        <?php }elseif($key=='is_i500'){ ?>
            <select class="zjs_<?php echo $key; ?>">
                <option value="0">否</option>
                <option value="1">是</option>
            </select>
        <?php }elseif($key=='bank_deposit'){ ?>
            <select class="zjs_bank_deposit">
            <?php foreach($arr_bank as $value){ ?>
                <option value="<?php echo $value ?>"><?php echo $value ?></option>
            <?php } ?>
            </select>
            <input type="text" class="zjs_bank_deposit_branch" style="width:460px;" />
        <?php }elseif($key=='status'){ ?>
            <span>禁用</span>
        <?php }elseif($key=='xxxx'){ ?>

        <?php }else{ ?>
            <input type="text" class="zjs_<?php echo $key; ?>" />
        <?php } ?>



            </td>
        </tr>
    <?php } ?>
        </tbody></table>
</div>


<div class="zcss_class0401">
    <a href="/shop/shop/index">返回列表</a>
    <input type="button" class="zjs_btn_submit" value="确认添加" />
</div>





<span class="zjs_csrf" style="display:none;"><?php echo \Yii::$app->getRequest()->getCsrfToken(); ?></span>
<span class="zjs_img_host" style="display:none;"><?php echo \Yii::$app->params['imgHost']; ?></span>




<script type="text/javascript">
$(function()
{
    reset_hours_start();
    reset_hours_end();

    $(document).on("click",".js_btn_upload_img",function()
    {
        $("#form_upload_img").submit();
    });

    $(".zjs_btn_submit").click(function()
    {
        add();
    });

    $(document).on("click",".zjs_btn_query_salesman",function()
    {
        get_salesman_list();
    });

});

function add()
{
    var shop_name=$(".zjs_shop_name").val();
    var contact_name=$(".zjs_contact_name").val();
    var email=$(".zjs_email").val();
    var mobile=$(".zjs_mobile").val();
    var phone=$(".zjs_phone").val();
    var address=$(".zjs_address").val();
    var manage_type=$(".zjs_select_manage_type").val();
    var position_x=$(".zjs_position_x").val();
    var position_y=$(".zjs_position_y").val();
    var hours=$(".zjs_hours_start").val()+"~"+$(".zjs_hours_end").val();
    var hours_remarks=$(".zjs_hours_remarks").val();
    var introduction=$(".zjs_introduction").val();
    var logo=$(".zjs_logo").val();
    var alipay=$(".zjs_alipay").val();
    var takepoint=$(".zjs_select_takepoint").val();
    //var chinapay=$(".zjs_chinapay").val();
    //var account_name=$(".zjs_account_name").val();
    //var bank_deposit=$(".zjs_bank_deposit").val();
    //var bank_deposit_branch=$(".zjs_bank_deposit_branch").val();
    var sent_fee=$(".zjs_sent_fee").val();
    var free_money=$(".zjs_free_money").val();
    var freight=$(".zjs_freight").val();
    //var business_status=$(".zjs_select_business_status").val();
    var province=$(".zjs_select_province_open_v4").val();
    var city=$(".zjs_select_city_v4").val();
    var district=$(".zjs_select_district_v4").val();
    var is_i500=$(".zjs_is_i500").val();

    var username=$(".zjs_username").val();
    var password=$(".zjs_password").val();
    var branch_id=$(".zjs_select_branch_open_v4").val();
    var business_id=$(".zjs_select_salesman").val();
    var htnumber=$(".zjs_htnumber").val();

    var csrf=$(".zjs_csrf").html();

    if(isNaN(province)===true || province=='0'){alert("未选择省");z_log("province not num");return;}
    if(isNaN(city)===true || city=='0'){alert("未选择市");z_log("city not num");return;}
    if(isNaN(district)===true || district=='0'){alert("未选择区县");z_log("district not num");return;}
    if(isNaN(position_y)===true){alert("纬度不正确");z_log("lng not num");return;}
    if(isNaN(position_x)===true){alert("经度不正确");z_log("lat not num");return;}
    if(isNaN(manage_type)===true || manage_type=='0'){alert("未选择店铺类型");z_log("manage_type not num");return;}
    if(isNaN(takepoint)===true){alert("未选择是否自提点");z_log("takepoint not num");return;}
    //if(isNaN(business_status)===true){alert("未选择营业状态");z_log("business_status not num");return;}
    if(isNaN(branch_id)===true || branch_id=='0'){alert("未选择分公司");z_log("branch_id not num");return;}
    if(isNaN(business_id)===true || business_id=='0'){alert("未选择业务员");z_log("business_id not num");return;}

    if(check_lng(position_x)==0){alert("经度不正确");return;}
    if(check_lat(position_y)==0){alert("纬度不正确");return;}
    if(z_check_mobile_num(mobile)==0){alert("手机号不正确");return;}
    if(check_hours()==0){alert("营业时间不正确");return;}
    if(email!='' && z_check_email(email)==0){alert("邮箱格式不正确");return;}

    if(username==''){alert("用户名 不可为空");return;}
    if(password==''){alert("密码 不可为空");return;}
    if(password!='' && z_check_password(password)==0){alert("密码不符合格式，至少6位，字符范围：a-z A-z 0-9");return;}
    if(shop_name==''){alert("店铺名称 不可为空");return;}
    if(address==''){alert("店铺地址 不可为空");return;}
    if(sent_fee!='' && z_check_money(sent_fee)==0){alert("起送费 不符合格式");return;}
    if(free_money!='' && z_check_money(free_money)==0){alert("满多少免运费 不符合格式");return;}
    if(freight!='' && z_check_money(freight)==0){alert("运费 不符合格式");return;}
    if(contact_name==''){alert("联系人 不可为空");return;}
    if(phone==''){alert("电话 不可为空");return;}
    //if(chinapay==''){alert("银联卡号 不可为空");return;}
    //if(account_name==''){alert("户名 不可为空");return;}
    //if(bank_deposit==''){alert("开户行 不可为空");return;}
    //if(bank_deposit_branch==''){alert("开户行支行信息 不可为空");return;}
    //if(xxxx==''){alert("xxxx 不可为空");return;}


    $.post
    (
        "/shop/shop/ajax",
        {
            "act":"add",
            "_csrf":csrf,
            'shop_name':shop_name,
            'contact_name':contact_name,
            'email':email,
            'mobile':mobile,
            'phone':phone,
            'address':address,
            'manage_type':manage_type,
            'is_i500':is_i500,
            'position_x':position_x,
            'position_y':position_y,
            'hours':hours,
            'hours_remarks':hours_remarks,
            'introduction':introduction,
            'logo':logo,
            'alipay':alipay,
            'takepoint':takepoint,
            //'chinapay':chinapay,
            //'account_name':account_name,
            //'bank_deposit':bank_deposit,
            //'bank_deposit_branch':bank_deposit_branch,
            'sent_fee':sent_fee,
            'free_money':free_money,
            'freight':freight,
            //'business_status':business_status,
            'province':province,
            'city':city,
            'district':district,
            'username':username,
            'password':password,
            'branch_id':branch_id,
            'htnumber':htnumber,
            'business_id':business_id
        },
        function(json)
        {
            var obj=JSON.parse(json);
            if(obj.code=='200'){
                z_tip("添加成功",3000,function(){
                    window.location.href="/shop/shop/index";
                });
            }else{
                //z_tip(obj.msg,3000);
                alert(obj.msg);
                return;
            }
        }
    );
}


function upload_return(msgtype,msg)
{
    //console.log(msgtype);
    //console.log(msg);
    //return;
    if(msgtype==1){
        var obj=JSON.parse(msg);
        //console.log(obj);
        show_new_logo(obj);
    }
    else if(msgtype==0){
        z_tip(msg, 4000);
    }
    else{}
}

function show_new_logo(obj)
{
    var img_host=$(".zjs_img_host").html();
    $(".zjs_logo_full_url").attr("src",img_host+"/"+obj.group_name+"/"+obj.filename);
    $(".zjs_logo").val("/"+obj.group_name+"/"+obj.filename);
}






</script>

