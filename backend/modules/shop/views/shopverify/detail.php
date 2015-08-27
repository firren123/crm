<?php
/**
 * 商家信息修改审核详情-view
 *
 * PHP Version 5
 *
 * @category  ADMIN
 * @package   BACKEND
 * @author    zhengyu <zhengyu@iyangpin.com>
 * @time      15/3/26 17:49
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      zhengyu@iyangpin.com
 */


$this->title = "商家信息修改申请详情";
?>

<script src="/js/jquery-1.10.2.min.js"></script>
<script src="/js/shopverifydetail.js"></script>

<style type="text/css">
.zcss_table1 th{font-weight:bold;}
.zcss_table1 th,.zcss_table1 td{border:1px solid #000000;padding:5px;}

.zcss_td_left{width:120px;}
.zcss_td_right{width:400px;}

.class_tac{text-align:center;}

.zcss_span_1{padding:0 20px 0 0;}
</style>




<div>
    <ul class="breadcrumb">
        <li><a href="/">首页</a></li>
        <li class="active"><a href="/shop/shopverify/list" style="color:#286090;">商家资料修改审核</a></li>
        <li class="active">详情</li>
    </ul>
</div>


<div>
    <table class="zcss_table1"><tbody>
        <tr>
            <td class="zcss_td_left">id</td>
            <td class="zcss_td_right"><?php echo $arr_info['id']; ?></td>
        </tr>
        <tr>
            <td>商家id</td>
            <td class="zjs_shop_id"><?php echo $arr_info['shop_id']; ?></td>
        </tr>
        <tr>
            <td>商家名称</td>
            <td><?php echo $arr_info['shop_name']; ?></td>
        </tr>
        <tr>
            <td>联系人</td>
            <td><?php echo $arr_info['contact_name']; ?></td>
        </tr>
        <tr>
            <td>邮箱</td>
            <td><?php echo $arr_info['email']; ?></td>
        </tr>
        <tr>
            <td>手机</td>
            <td><?php echo $arr_info['mobile']; ?></td>
        </tr>
        <tr>
            <td>联系电话</td>
            <td><?php echo $arr_info['phone']; ?></td>
        </tr>
        <tr>
            <td>店铺地址</td>
            <td><?php echo $arr_info['address']; ?></td>
        </tr>
        <tr>
            <td>经度</td>
            <td><?php echo $arr_info['position_x']; ?><?php if($is_position_change==0){echo " (未改变)";} ?></td>
        </tr>
        <tr>
            <td>纬度</td>
            <td><?php echo $arr_info['position_y']; ?><?php if($is_position_change==0){echo " (未改变)";} ?></td>
        </tr>
        <tr>
            <td>店铺介绍</td>
            <td><?php echo $arr_info['introduction']; ?></td>
        </tr>
        <tr>
            <td>店铺LOGO</td>
            <td>
                <span><?php echo \Yii::$app->params['imgHost'].$arr_info['logo']; ?></span>
                <br />
                <image src="<?php echo \Yii::$app->params['imgHost'].$arr_info['logo']; ?>" style="max-width:200px;max-height:200px;" />
            </td>
        </tr>
        <tr>
            <td>申请审核时间</td>
            <td><?php echo $arr_info['verify_apply_time']; ?></td>
        </tr>

        <tr>
            <td>审核状态</td>
            <td><?php echo $map_verify_status[$arr_info['verify_status']]; ?></td>
        </tr>
    <?php if($arr_info['verify_status']!=0){ ?>
        <tr>
            <td>审核操作时间</td>
            <td><?php echo $arr_info['verify_operate_time']; ?></td>
        </tr>
        <tr>
            <td>审核人名称</td>
            <td><?php echo $arr_info['verify_admin_name']; ?></td>
        </tr>
    <?php } ?>
    <?php if($arr_info['verify_status']==2){ ?>
        <tr>
            <td>审核驳回原因</td>
            <td><?php echo $arr_info['verify_reject_reason']; ?></td>
        </tr>
    <?php } ?>

        <tr>
            <td colspan="2" class="class_tac">
                <input type="radio" name="zname_radio_1" id="zid_radio_1" class="zjs_radio" value="1" />
                <label for="zid_radio_1">通过</label>

                <input type="radio" name="zname_radio_1" id="zid_radio_2" class="zjs_radio" value="2" style="margin:0 0 0 100px;" />
                <label for="zid_radio_2">驳回</label>
            </td>
        </tr>
        <tr class="zjs_tr_hide zjs_tr_reason" style="display:none;">
            <td>审核驳回原因</td>
            <td><input type="text" class="zjs_text_reason" style="width:99%;" /></td>
        </tr>
        <tr class="zjs_tr_hide zjs_tr_reset_community" style="display:none;">
            <td>是否重置小区</td>
            <td>
                <div class="class_tac">
                    <span class="zcss_span_1">原经度=<?php echo $arr_shop_info['position_x']; ?></span>
                    <span class="zcss_span_1">原纬度=<?php echo $arr_shop_info['position_y']; ?></span>
                </div>
                <div class="class_tac">
                    <span class="zcss_span_1">新经度=<?php echo $arr_info['position_x']; ?></span>
                    <span class="zcss_span_1">新纬度=<?php echo $arr_info['position_y']; ?></span>
                </div>
                <div class="class_tac">
                    <label for="zjs_id_radio_1">是　</label><input type="radio" name="zjs_name_radio_1" id="zjs_id_radio_1" class="zjs_radio_1 zcss_radio_1" style="margin:0 50px 0 0;" value="1" checked="checked" />
                    <label for="zjs_id_radio_2">否　</label><input type="radio" name="zjs_name_radio_1" id="zjs_id_radio_2" class="zjs_radio_1 zcss_radio_1" style="margin:0 50px 0 0;" value="0" />
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="class_tac">
                <input type="button" class="zjs_btn_submit" value="提交" disabled="disabled" style="width:100px;height:50px;" />
            </td>
        </tr>

        </tbody></table>

</div>

<span class="zjs_csrf" style="display:none;"><?php echo \Yii::$app->getRequest()->getCsrfToken(); ?></span>
<span class="zjs_is_position_change" style="display:none;"><?php echo $is_position_change; ?></span>



