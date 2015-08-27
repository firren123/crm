<?php
/**
 * 详情-view
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
    'id' => 'id',
    'username' => '用户名',
    'shop_name' => '店铺名称',

    'contact_name' => '联系人',
    'email' => '邮箱',
    'mobile' => '手机',
    'phone' => '电话',

    'province' => '省',
    'city' => '市',
    'district' => '区/县',
    'address' => '店铺地址',
    'htnumber' => '合同号',

    //z20150806注：此处有可能插入银行信息，此处前数量变化需注意

    'manage_type' => '店铺类型',
    'branch_id' => '分公司',
    'is_i500' => 'i500专营',
    'hours' => '店铺营业时间',
    'hours_remarks' => '营业时间备注',
    'introduction' => '店铺介绍',
    'logo' => '店铺LOGO',

    'status' => '状态',
    'business_status' => '营业状态',
    'takepoint' => '是否是自提点',
    'business_id' => '业务员',

    'alipay' => '支付宝账号',
    //'chinapay' => '银联卡号',
    //'account_name' => '户名',
    //'bank_deposit' => '开户行',

    'sent_fee' => '起送费',
    'free_money' => '满多少免运费',
    'freight' => '运费',

    'create_time' => '创建时间',

    //'recode_time' => '最后回访时间',
    //'owner_id' => '业主id',
    //'login_time' => '最后登录时间',
    //'login_ip' => '最后登录IP',
    //'create_time' => '添加时间',
    //'update_time' => '更新时间',
    //'score' => '评分',
    //'comment_count' => '评论总数',
    //'workflow' => '激活步骤',

    'position_x' => '经度',
    'position_y' => '纬度',
    'show_position_map' => '坐标',
);

//z20150806 如果有有效关联的合同，显示银行账户信息
if (isset($arr_shop_info['bank_name'])
    && isset($arr_shop_info['bank_branch'])
    && isset($arr_shop_info['bank_number'])
    && isset($arr_shop_info['bankcard_username'])
) {
    $arr_insert = array(
        'bank_name' => '开户行',
        //'bank_branch' => '开户行支行名称',
        'bank_number' => '银行帐号',
        'bankcard_username' => '户名',
    );
    $arr_head = array_slice($map_field_name, 0, 12, true);
    $arr_tail = array_slice($map_field_name, 12, null, true);
    $map_field_name = array_merge($arr_head, $arr_insert, $arr_tail);
    //echo "<pre>";print_r($map_field_name);echo "</pre>";exit;
}


$this->title = "商家详情";

function get_hours($str_hours)
{
    $pattern = "/^(\d{1,2})~(\d{1,2})$/";
    $arr_match = array();

    if (preg_match($pattern, $str_hours, $arr_match) == 1) {
        $str = $arr_match[1] . ":00-" . $arr_match[2] . ":00";
        return array('start' => $arr_match[1], 'end' => $arr_match[2]);
    } else {
        return array('start' => -1, 'end' => -1);
    }
}

?>

<script src="/js/jquery-1.10.2.min.js"></script>

<style type="text/css">
.zcss_table1 th{font-weight:bold;}
.zcss_table1 th,.zcss_table1 td{border:1px solid #000000;padding:5px;}

.zcss_td_left{width:120px;}
.zcss_td_right{width:650px;}

.zcss_class0401{margin:30px 0;}
.zcss_class0401 a{padding:0 20px;}
</style>



<div>

    <ul class="breadcrumb">
        <li><a href="/">首页</a></li>
        <li class="active"><a href="/shop/shop" style="color:#286090;">商家管理</a></li>
        <li class="active">详情</li>
    </ul>

    <table class="zcss_table1"><tbody>

        <?php foreach($map_field_name as $field=>$name){ ?>
            <tr>
                <td class="zcss_td_left"><?php echo $name; ?></td>
                <td class="zcss_td_right">
                <?php if($field=='province'){ ?>
                    <?php if(isset($arr_cur_province['name'])){echo $arr_cur_province['name'];} ?>
                <?php }elseif($field=='city'){ ?>
                    <?php if(isset($arr_cur_city['name'])){echo $arr_cur_city['name'];} ?>
                <?php }elseif($field=='district'){ ?>
                    <?php if(isset($arr_cur_district['name'])){echo $arr_cur_district['name'];} ?>
                <?php }elseif($field=='manage_type'){ ?>
                    <?php if(isset($arr_cur_manage_type['name'])){echo $arr_cur_manage_type['name'];} ?>
                <?php }elseif($field=='branch_id'){ ?>
                    <?php if(isset($arr_cur_branch['name'])){echo $arr_cur_branch['name'];} ?>
                <?php }elseif($field=='status'){ ?>
                    <?php if(isset($map_status[$arr_shop_info[$field]])){echo $map_status[$arr_shop_info[$field]];} ?>
                <?php }elseif($field=='business_status'){ ?>
                    <?php if(isset($map_business_status[$arr_shop_info[$field]])){echo $map_business_status[$arr_shop_info[$field]];} ?>
                <?php }elseif($field=='takepoint'){ ?>
                    <?php if(isset($map_take_point[$arr_shop_info[$field]])){echo $map_take_point[$arr_shop_info[$field]];} ?>
                <?php }elseif($field=='logo'){ ?>
                    <img src="<?php echo \Yii::$app->params['imgHost'].$arr_shop_info['logo']; ?>" style="max-height:100px;max-width:100px;" />
                <?php }elseif($field=='business_id'){ ?>
                    <?php if(isset($arr_cur_business['name'])){echo $arr_cur_business['name'];} ?>


                <?php }elseif($field=='show_position_map'){ ?>
                    <!--获取坐标 start-->
                    <style type="text/css">
                        #z_id_div_map{height:400px;width:100%;border:1px solid #bcbcbc;}
                    </style>
                    <script type="text/javascript" src="http://api.map.baidu.com/api?v=1.4"></script>
                    <div>
                        <div id="z_id_div_map"></div>
                    </div>
                    <script type="text/javascript">
                        var x=<?php if(isset($arr_shop_info['position_x'])){echo $arr_shop_info['position_x'];}else{echo "116.417321";} ?>;
                        var y=<?php if(isset($arr_shop_info['position_y'])){echo $arr_shop_info['position_y'];}else{echo "39.887979";} ?>;
                        // 百度地图API功能
                        var map = new BMap.Map("z_id_div_map");//创建Map实例
                        var point = new BMap.Point(x, y);
                        map.centerAndZoom(point, 18);
                        var mk = new BMap.Marker(point);
                        map.addOverlay(mk);//加标记
                        map.enableScrollWheelZoom();
                    </script>
                    <!--获取坐标 end-->


                <?php }elseif($field=='hours'){ ?>
                <?php $arr_hours=get_hours($arr_shop_info['hours']); ?>
                    <?php echo $arr_hours['start']; ?>时~<?php echo $arr_hours['end']; ?>时


                <?php }elseif($field=='is_i500'){ ?>
                    <?php if(isset($arr_shop_info['is_i500']) && isset($map_is_i500[$arr_shop_info['is_i500']])){echo $map_is_i500[$arr_shop_info['is_i500']];} ?>
                <?php }elseif($field=='create_time'){ ?>
                    <?php if(isset($arr_shop_info['create_time'])){echo date("Y-m-d H:i:s", $arr_shop_info['create_time']);} ?>
                <?php }elseif($field=='bank_deposit'){ ?>
                    <?php if(isset($arr_shop_info['bank_deposit'])){echo $arr_shop_info['bank_deposit'];} ?><?php if(isset($arr_shop_info['bank_deposit_branch'])){echo $arr_shop_info['bank_deposit_branch'];} ?>

                <?php }elseif($field=='bank_name'){ ?>
                    <?php if(isset($arr_shop_info['bank_name']) && isset($arr_shop_info['bank_branch'])){echo $arr_shop_info['bank_name'].$arr_shop_info['bank_branch'];} ?>

                <?php }elseif($field=='xxxx'){ ?>
                <?php }else{ ?>
                    <?php if(isset($arr_shop_info[$field])){echo $arr_shop_info[$field];} ?>
                <?php } ?>
                </td>
            </tr>
        <?php } ?>

    </tbody></table>
</div>

<div class="zcss_class0401">
    <a href="/shop/shop/index">返回列表</a>
    <a href="/shop/shop/edit?id=<?php echo $arr_shop_info['id']; ?>">修改</a>
</div>


<span class="zjs_id" style="display:none;"><?php echo $arr_shop_info['id']; ?></span>



<script type="text/javascript">
$(function()
{

});

</script>