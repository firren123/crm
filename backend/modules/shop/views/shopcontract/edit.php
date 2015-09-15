<?php
/**
 * 简介1
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   I500
 * @filename  edit.php
 * @author    weitonghe <weitonghe@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/9/10 上午15:00
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */
$this->title = '商家合同信息修改';
?>
<style>
    tr{ width: 1000px; height: auto;}
    .td1{ width: 200px;}
    .td2{ width: 300px;}
    input{ padding-bottom: 7px; padding-top: 7px; padding-left: 10px;border-radius:5px; border: 1px solid #ddd;}
    a{cursor:pointer;}
    .a_choose+input{display: none;}
</style>
<script type="text/javascript" src="/js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="/js/My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        //图片处理
        $(".a_choose").click(function(){
            $("#"+$(this).attr("name")+"Pic").click();
        });
        $(".a_looked").click(function(){
            if ($("#"+$(this).attr("name")+"Pic_img").css("display")=='none') {
                $(this).html("关闭");
            } else {
                $(this).html("查看");
            }
            $("#"+$(this).attr("name")+"Pic_img").slideToggle("slow");
        });
        $(".BeiZhu_Looked").click(function(){
            if ($(".BeiZhu").css("display")=='none') {
                $(".BeiZhu_Looked").html("关闭");
            } else {
                $(".BeiZhu_Looked").html("查看");
            }
            $(".BeiZhu").slideToggle("slow");
        });
    });
    var file_image_reg = /^.+.[jpg|gif|bmp|bnp|png]$/;
    var is_file_image = false;
    function previewImage(file) {
        if (!file_image_reg.test(file.value)) {
            is_file_image = false;
            alert("图片格式不正确,请重新选择!");
            return;
        }
        $('#'+'a_looked_'+file.name).html("关闭");
        is_file_image = true;
        var MAXWIDTH = 0;
        var MAXHEIGHT = 0;
        var div = document.getElementById('preview_'+file.id);
        if (div.style.width) {
            var MAXWIDTH = div.offsetWidth;
            var MAXHEIGHT = div.offsetHeight;
        }
        if (file.files && file.files[0]) {
            div.innerHTML = '<img id='+file.id+'_img'+' style="width:100%;height:100%;">';
            var img = document.getElementById(file.id+'_img');
            img.onload = function () {
                var rect = clacImgZoomParam(MAXWIDTH, MAXHEIGHT, img.offsetWidth, img.offsetHeight);
                img.width = rect.width;
                img.height = rect.height;
//                 img.style.marginLeft = rect.left+'px';
                img.style.marginTop = rect.top + 'px';
            }
            var reader = new FileReader();
            reader.onload = function (evt) {
                img.src = evt.target.result;
            }
            reader.readAsDataURL(file.files[0]);
        }
        else //兼容IE
        {
            var sFilter = 'filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale,src="';
            file.select();
            var src = document.selection.createRange().text;
            div.innerHTML = '<img class="'+file.id+'" style="width:100%;height:100%;">';
            var img = document.getElementsByClassName(file.id);
            img.filters.item('DXImageTransform.Microsoft.AlphaImageLoader').src = src;
            var rect = clacImgZoomParam(MAXWIDTH, MAXHEIGHT, img.offsetWidth, img.offsetHeight);
            status = ('rect:' + rect.top + ',' + rect.left + ',' + rect.width + ',' + rect.height);
            div.innerHTML = "<div id=divhead style='width:" + rect.width + "px;height:" + rect.height + "px;margin-top:" + rect.top + "px;" + sFilter + src + "\"'></div>";
        }
    }

    function clacImgZoomParam(maxWidth, maxHeight, width, height) {
        var param = {top: 0, left: 0, width: width, height: height};
        if (maxWidth) {
            rateWidth = width / maxWidth;
            rateHeight = height / maxHeight;

            if (rateWidth > rateHeight) {
                param.width = maxWidth;
                param.height = Math.round(height / rateWidth);
            } else {
                param.width = Math.round(width / rateHeight);
                param.height = maxHeight;
            }
            param.left = Math.round((maxWidth - param.width) / 2);
            param.top = Math.round((maxHeight - param.height) / 2);
        }
        return param;
    }
</script>
<div style=" width: 1000px; height: auto; ">
    <h4><a href="/" >首页</a>&gt;<a href="/shop/shopcontract/index">商家合同列表</a><span>&gt;</span>商家合同信息修改</h4>
    <table style="border-bottom: 1px solid #D5692B; width: 1000px; height: auto;">
        <tr>
            <th colspan="4">基本信息</th>
        </tr>
        <tr>
            <th colspan="1" style="" class="td1">合同号：</th>
            <th colspan="1" class="td2"><input name="htnumber" class="htnumber" value="<?= $list['htnumber'];?>"></th>
        </tr>
        <tr>
            <td class="td1">注册名称：</td>
            <td ><label><input name="shop_contract_name" class="shop_contract_name" value="<?= $list['shop_contract_name'];?>"></label></td>
            <td class="td1">注册地址：</td>
            <td ><label><input name="registered_address" class="registered_address" value="<?= $list['registered_address'];?>"></label></td>
        </tr>
        <tr>
            <td class="td1">注册登记号：</td>
            <td ><label><input name="registered_id" class="registered_id" value="<?= $list['registered_id'];?>"></label></td>
            <td class="td1">注册资本：</td>
            <td ><label><input name="registered_capital" class="registered_capital" value="<?= $list['registered_capital'];?>"></label></td>
        </tr>
        <tr>
            <td class="td1">法定代表人：</td>
            <td ><label><input name="legal_representative" class="legal_representative" value="<?= $list['legal_representative'];?>"></label></td>
            <td class="td1">邮箱：</td>
            <td ><label><input name="email" class="email" value="<?= $list['email'];?>"></label></td>
        </tr>
        <tr>
            <td class="td1">证件类型：</td>
            <td >
                <label>
                    <?php if ($list['document_type']==0){echo "二代身份证" ;} ?>
                    <?php if ($list['document_type']==1){echo "港澳通行证" ;} ?>
                    <?php if ($list['document_type']==2){echo "台湾通行证" ;} ?>
                    <?php if ($list['document_type']==3){echo "护照" ;} ?>
                </label>
            </td>
            <td class="td1">证件号：</td>
            <td ><label><input name="document_number" class="document_number" value="<?= $list['document_number'];?>"></label></td>
        </tr>
        <tr>
            <td class="td1">联系人：</td>
            <td ><label><input name="contacts" class="contacts" value="<?= $list['contacts'];?>"></label></td>
            <td class="td1">联系电话：</td>
            <td ><label><input name="contacts_umber" class="contacts_umber" value="<?= $list['contacts_umber'];?>"></label></td>
        </tr>
        <tr>
            <td colspan="1" class="td1">公司性质：</td>
            <td colspan="3">
                <label>
                    <?php
                    foreach($list['company_nature'] as $k=>$v){
                    if ($list['company_nature'][$k]==0){echo "个体商户" ;}
                    ?>
                </label>
                <label>
                    <?php
                    if ($list['company_nature'][$k]==1){echo "民办非企业" ;}
                    ?>
                </label>
                <label>
                    <?php
                    if ($list['company_nature'][$k]==2){echo "股份制" ;}
                    ?>
                </label>
                <label>
                    <?php
                    if ($list['company_nature'][$k]==3){echo "有限责任制" ;}
                    ?>
                </label>
                <label>
                    <?php
                    if ($list['company_nature'][$k]==4){echo "其他信息：".$list['company_nature_other'] ;}
                    }
                    ?>
                </label>
            </td>
        </tr>
        <tr>
            <td class="td1">合同状态：</td>
            <td style="color: red;"><b><?php if ($list['status']) {
                        echo "已生效";
                    }else{
                        echo "未生效";
                    };?></b></td>
        </tr>
    </table>
    <table style="border-bottom: 1px solid #D5692B; width: 1000px; height: auto;">
        <tr>
            <th colspan="4" style="">经营信息</th>
        </tr>
        <tr>
            <td colspan="1" class="td1">店面注册名称：</td>
            <td colspan="3" class="td2">
                <label><input name="business_name" class="business_name" value="<?= $shop['business_name'];?>"></label>
            </td>
        </tr>
        <tr>
            <td colspan="1" class="td1">经营范围：</td>
            <td colspan="3">
                <label>
                    <?php
                    foreach($shop['business_scope'] as $k=>$v){
                    if ($shop['business_scope'][$k] == 1) {
                        echo "日用百货";
                    }
                    ?>
                </label>
                <label>
                    <?php
                    if ($shop['business_scope'][$k] == 2) {
                        echo "工艺美术品";
                    }
                    ?>
                </label>
                <label>
                    <?php
                    if ($shop['business_scope'][$k] == 3) {
                        echo "文教用品";
                    }
                    ?>
                </label>
                <label>
                    <?php
                    if ($shop['business_scope'][$k] == 4) {
                        echo "副食品";
                    }
                    }
                    ?>
                </label>
            </td>
        </tr>
        <tr>
            <td colspan="1" class="td1">经营地址：</td>
            <td colspan="3"><label><input name="business_address" class="business_address" value="<?= $shop['business_address'];?>"></label></td>
        </tr>
    </table>
    <table style="border-bottom: 1px solid #D5692B; width: 1000px; height: auto;">
        <tr>
            <th colspan="4" style="">服务信息</th>
        </tr>
        <tr >
            <td style="" class="td1">日常联系人姓名：</td>
            <td style="" class="td2"><label><input name="common_contacts_name" class="common_contacts_name" value="<?= $list['common_contacts_name'];?>"></label></td>
            <td style="" class="td1">电话：</td>
            <td style="" ><label><input name="common_contacts_phone" class="common_contacts_phone" value="<?= $list['common_contacts_phone'];?>"></label></td>
        </tr>
        <tr>
            <td class="td1">职务：</td>
            <td><label><input name="common_contacts_job" class="common_contacts_job" value="<?= $list['common_contacts_job'];?>"></label></td>
            <td class="td1">月均营业额：</td>
            <td><label><input name="monthly_turnover" class="monthly_turnover" value="<?= $list['monthly_turnover'];?>"></label></td>
        </tr>
        <tr>
            <td class="td1">面积：</td>
            <td><label><input name="area" class="area" value="<?= $list['area'];?>"></label>&nbsp;&nbsp;M<sup>2</sup></td>
            <td class="td1">营业时间：</td>
            <td >
                上午&nbsp;
                <label><?= $list['business_hours'][0]."：00";?></label>
                &nbsp;至&nbsp;&nbsp;下午
                <label><?= $list['business_hours'][1]."：00";?></label>
            </td>
        </tr>
        <tr>
            <td class="td1">所在社区名称：</td>
            <td><label><input name="community_name" class="community_name" value="<?= $list['community_name'];?>"></label></td>
        </tr>
    </table>
    <table style=" width: 1000px; height: auto;">
        <tr><th colspan="4">清算信息</th></tr>
        <tr>
            <td colspan="1" class="td1"><label>帐户类型：</label></td>
            <td colspan="3" class="td2">
                <label>
                    <?php
                    if ($list['account_type']==0) { echo "银行账号";}
                    if ($list['account_type']==1) { echo "支付宝账号";}
                    ?>
                </label>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <?php
                if ($list['account_type']==1) {
                    ?>
                    <table style="border-bottom: 1px solid #D5692B; width: 1000px; height: auto;">
                        <tr>
                            <td class="td1">支付宝账号：</td>
                            <td><label><input name="alipay_name" class="alipay_name" value="<?= $list['alipay_name'];?>"></label></td>
                        </tr>
                    </table>
                <?php
                }
                ?>
                <?php
                if ($list['account_type']==0) {
                    ?>
                    <table style=" border-bottom: 1px solid #D5692B; width: 1000px; height: auto;">
                        <tr><td><b>银行信息</b></td></tr>
                        <tr>
                            <td class="td1">开户银行：</td>
                            <td class="td2"><label><?= $list['bank_name'];?></label></td>
                            <td class="td1">开户支行：</td>
                            <td><label><?= $list['bank_branch'];?></label></td>
                        </tr>
                        <tr>
                            <td class="td1">所在省份：</td>
                            <td><label><?= $province['name'];?></label></td>
                            <td class="td1">所在城市：</td>
                            <td><label><?= $city['name'];?></label></td>
                        </tr>
                        <tr>
                            <td class="td1">银行卡号：</td>
                            <td><label><input name="bank_number" class="bank_number" value="<?= $list['bank_number'];?>"></label></td>
                            <td class="td1">开户名称：</td>
                            <td><label><input name="bankcard_username" class="bankcard_username" value="<?= $list['bankcard_username'];?>"></label></td>
                        </tr>
                    </table>
                <?php
                }
                ?>
            </td>
        </tr>
    </table>
    <table style="border-bottom: 1px solid #D5692B; width: 1000px; height: auto;">
        <tr>
            <th colspan="4">结算信息</th>
        </tr>
        <tr>
            <td class="td1">
                服务费用方式：</br>
                <input type="radio" id="gd" name="service_charge" value="0" <?php if($list['service_charge']==0) {echo 'checked';} ?> />固定服务费
                <input type="radio" id="yj" name="service_charge" value="1" <?php if($list['service_charge']==1) {echo 'checked';} ?> />服务佣金
            </td>
            <td class="td2">
                <input type="text" value="<?php if ($list['service_charge']==0) { echo $list['fixed_service_charge'];} if($list['service_charge']==1) {echo $list['service_commission'];}?>" ><label id="FeiYong_Sign">%</label>
            </td>
            <td class="td1"><label>结算周期：</label></td>
            <td>
                <label>
                    <?php
                    if ($list['settlement_cycle']==0) { echo "1天";}
                    if ($list['settlement_cycle']==1) { echo "5天";}
                    if ($list['settlement_cycle']==2) { echo "7天";}
                    if ($list['settlement_cycle']==3) { echo "14天";}
                    if ($list['settlement_cycle']==4) { echo "30天";}
                    if ($list['settlement_cycle']==5) { echo "60天";}
                    if ($list['settlement_cycle']==6) { echo "每月1次";}
                    if ($list['settlement_cycle']==7) { echo "每月2次";}
                    ?>
                </label>
            </td>
        </tr>
    </table>
    <table style="border-bottom: 1px solid #D5692B; width: 1000px; height: auto;">
        <tr>
            <th colspan="4">其他信息</th>
        </tr>
        <tr>
            <td class="td1">起止时间</td>
            <td class="td2"><label>开始时间：<input type="text" onFocus="WdatePicker({isShowClear:true,readOnly:false})" value="<?= $list['start_time'];?>"></label></td>
            <td><label>结束时间：<input type="text" onFocus="WdatePicker({isShowClear:true,readOnly:false})" value="<?= $list['end_time'];?>"></label></td>
        </tr>
        <tr>
            <td class="td1">业务员信息</td>
            <td>
                <label>业务员ID：<label style="color:red;"><input name="business_id" class="business_id" value="<?= $business['id'];?>"></label></label>
            </td>
            <td>
                <label >业务员姓名：<label style="color:red;"><?= $business['name'];?></label></label>
            </td>
        </tr>
    </table>
    <table>
        <tr>
            <th colspan="1" class="td1">合同图片</th>
            <td colspan="1">
                <a class="a_looked" name="HeTong" id="a_looked_HeTong">查看</a>&nbsp;&nbsp;&nbsp;&nbsp;
                <a class="a_choose" name="HeTong">重新选择</a>
                <input id="HeTongPic" type="file" name="HeTong" onchange="previewImage(this)"/>
                <input type="hidden" class="a_i" value="<?php if(empty($list['image'])){echo "/images/05_mid.jpg";}else{echo \Yii::$app->params['imgHost'].$list['image'];} ?>">
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <div id="preview_HeTongPic">
                    <img id="HeTongPic_img" style=" display: none; max-width: 1000px; height: auto;" src="<?php if(empty($list['image'])){echo "/images/05_mid.jpg";}else{echo \Yii::$app->params['imgHost'].$list['image'];} ?>" />
                </div>
            </td>
        </tr>
        <tr>
            <th colspan="1" class="td2">营业执照图片</th>
            <td colspan="1">
                <a class="a_looked" name="YingYeZhiZhao" id="a_looked_YingYeZhiZhao">查看</a>&nbsp;&nbsp;&nbsp;&nbsp;
                <a class="a_choose" name="YingYeZhiZhao">重新选择</a>
                <input id="YingYeZhiZhaoPic" type="file" name="YingYeZhiZhao" onchange="previewImage(this)"/>
                <input type="hidden" class="b_i" value="<?php if(empty($list['business_licence_image'])){echo "/images/05_mid.jpg";}else{echo \Yii::$app->params['imgHost'].$list['business_licence_image'];} ?>">
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <div id="preview_YingYeZhiZhaoPic">
                    <img id="YingYeZhiZhaoPic_img" style=" display: none; max-width: 1000px; height: auto;" src="<?php if(empty($list['business_licence_image'])){echo "/images/05_mid.jpg";}else{echo \Yii::$app->params['imgHost'].$list['business_licence_image'];} ?>" />
                </div>
            </td>
        </tr>
        <tr>
            <th colspan="1" class="td3">银行卡图片</th>
            <td colspan="1">
                <a class="a_looked" name="YinHangKa" id="a_looked_YinHangKa">查看</a>&nbsp;&nbsp;&nbsp;&nbsp;
                <a class="a_choose" name="YinHangKa">重新选择</a>
                <input id="YinHangKaPic" type="file" name="YinHangKa" onchange="previewImage(this)"/>
                <input type="hidden" class="c_i" value="<?php if(empty($list['bank_number_image'])){echo "/images/05_mid.jpg";}else{echo \Yii::$app->params['imgHost'].$list['bank_number_image'];} ?>">
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <div id="preview_YinHangKaPic">
                    <img id="YinHangKaPic_img" style=" display: none; max-width: 1000px; height: auto;" src="<?php if(empty($list['bank_number_image'])){echo "/images/05_mid.jpg";}else{echo \Yii::$app->params['imgHost'].$list['bank_number_image'];} ?>" />
                </div>
            </td>
        </tr>
        <tr>
            <th colspan="1" class="td4">身份证图片</th>
            <td colspan="1">
                <a class="a_looked" name="ShenFenZheng" id="a_looked_ShenFenZheng">查看</a>&nbsp;&nbsp;&nbsp;&nbsp;
                <a class="a_choose" name="ShenFenZheng">重新选择</a>
                <input id="ShenFenZhengPic" type="file" name="ShenFenZheng" onchange="previewImage(this)"/>
                <input type="hidden" class="d_i" value="<?php if(empty($list['IDcard_image'])){echo "/images/05_mid.jpg";}else{echo \Yii::$app->params['imgHost'].$list['IDcard_image'];} ?>">
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <div id="preview_ShenFenZhengPic">
                    <img id="ShenFenZhengPic_img" style=" display: none; max-width: 1000px; height: auto;" src="<?php if(empty($list['IDcard_image'])){echo "/images/05_mid.jpg";}else{echo \Yii::$app->params['imgHost'].$list['IDcard_image'];} ?>" />
                </div>
            </td>
        </tr>
        <tr>
            <th colspan="1" class="td5">备注</th>
            <td colspan="1">
                <a class="BeiZhu_Looked">查看</a>&nbsp;&nbsp;&nbsp;&nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <textarea style=" display: none;" maxlength="255" rows="5" cols="100" class="BeiZhu"><?php echo $list['remark'] ?></textarea>
            </td>
        </tr>
    </table>
    <table>
        <tr>
            <th><a href="http://qyxy.baic.gov.cn" target="_blank">商家信息真实性查询</a><label style="margin-left: 20px;">网址：http://qyxy.baic.gov.cn</label></th>
        </tr>
    </table>
</div>
