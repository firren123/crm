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
    .mark{ color: red;}
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
            <th colspan="1" class="td1"><label class="mark">*</label>合同号：</th>
            <th colspan="1" class="td2"><input type="text" name="htnumber" class="htnumber HtNum_Reg" value="<?= $list['htnumber'];?>"></th>
        </tr>
        <tr>
            <td class="td1"><label class="mark">*</label>注册名称：</td>
            <td ><label><input type="text" name="shop_contract_name" class="shop_contract_name NotNull" value="<?= $list['shop_contract_name'];?>"></label></td>
            <td class="td1"><label class="mark">*</label>注册地址：</td>
            <td ><label><input type="text" name="registered_address" class="registered_address NotNull" value="<?= $list['registered_address'];?>"></label></td>
        </tr>
        <tr>
            <td class="td1"><label class="mark">*</label>注册登记号：</td>
            <td ><label><input type="text" name="registered_id" class="registered_id NotNull" value="<?= $list['registered_id'];?>"></label></td>
            <td class="td1">注册资本：</td>
            <td ><label><input type="text" name="registered_capital" class="registered_capital" value="<?= $list['registered_capital'];?>"></label></td>
        </tr>
        <tr>
            <td class="td1"><label class="mark">*</label>法定代表人：</td>
            <td ><label><input name="legal_representative" class="legal_representative NotNull" value="<?= $list['legal_representative'];?>"></label></td>
            <td class="td1">邮箱：</td>
            <td ><label><input name="email" class="email Email_Reg Null" value="<?= $list['email'];?>"></label></td>
        </tr>
        <tr>
            <td class="td1"><label class="mark">*</label>证件类型：</td>
            <td >
                <select name="document_type">
                    <?php
                    foreach ($init_array['document_type_data'] as $k => $v) {
                        ?>
                        <option value="<?= $k; ?>" <?php if ($list['document_type']==$k) {echo 'selected';} ?>><?= $v; ?></option>
                    <?php
                    }
                    ?>
                </select>
            </td>
            <td class="td1"><label class="mark">*</label>证件号：</td>
            <td ><label><input name="document_number" class="document_number DocumentNum_Reg" value="<?= $list['document_number'];?>"></label></td>
        </tr>
        <tr>
            <td class="td1"><label class="mark">*</label>联系人：</td>
            <td ><label><input name="contacts" class="contacts NotNull" value="<?= $list['contacts'];?>"></label></td>
            <td class="td1"><label class="mark">*</label>联系电话：</td>
            <td ><label><input name="contacts_umber" class="contacts_umber Mobile_Reg" value="<?= $list['contacts_umber'];?>"></label></td>
        </tr>
        <tr>
            <td colspan="1" class="td1"><label class="mark">*</label>公司性质：</td>
            <td colspan="3">
                <?php
                foreach ($init_array['company_nature_data'] as $k => $v) {
                    ?>
                    <input type="radio" name="company_nature" value="<?= $k; ?>" <?php if ($list['company_nature']==$k) {echo 'checked';} ?>><?= $v ?>
                <?php
                }
                ?>
                <input id="GongSiXingZhiQiTaXinXi_Input" type="text" name="company_nature_other" value="<?= $list['company_nature_other']; ?>" style="display: <?php if ($list['company_nature']==4) {echo 'inline';} else {echo 'none';}?>" >
            </td>
        </tr>
        <tr>
            <td class="td1">合同状态：</td>
            <td style="color: red;"><b><?php if ($list['status']) {echo "已生效";} else {echo "未生效";};?></b>
            </td>
        </tr>
    </table>
    <table style="border-bottom: 1px solid #D5692B; width: 1000px; height: auto;">
        <tr>
            <th colspan="4" style="">经营信息</th>
        </tr>
        <tr>
            <td colspan="1" class="td1"><label class="mark">*</label>店面注册名称：</td>
            <td colspan="3" class="td2"><input name="business_name" class="business_name NotNull" value="<?= $shop['business_name'];?>"></td>
        </tr>
        <tr>
            <td colspan="1" class="td1"><label class="mark">*</label>经营范围：</td>
            <td colspan="3">
                <label>
                    <?php
                    foreach ($init_array['business_scope_data'] as $k => $v) {
                        ?>
                        <input type="checkbox" name="business_scope" value="<?= $k ?>" <?php if (!empty($shop['business_scope']) && in_array($k, $shop['business_scope'])) {echo 'checked';} ?>><?= $v; ?>
                    <?php
                    }
                    ?>
                </label>
            </td>
        </tr>
        <tr>
            <td colspan="1" class="td1"><label class="mark">*</label>经营地址：</td>
            <td colspan="3"><input name="business_address" class="business_address NotNull" value="<?= $shop['business_address'];?>" style="width:500px;"></td>
        </tr>
    </table>
    <table style="border-bottom: 1px solid #D5692B; width: 1000px; height: auto;">
        <tr>
            <th colspan="4" style="">服务信息</th>
        </tr>
        <tr >
            <td class="td1"><label class="mark">*</label>日常联系人姓名：</td>
            <td class="td2"><input name="common_contacts_name" class="common_contacts_name NotNull" value="<?= $list['common_contacts_name'];?>"></td>
            <td class="td1"><label class="mark">*</label>电话：</td>
            <td><input name="common_contacts_phone" class="common_contacts_phone Mobile_Reg" value="<?= $list['common_contacts_phone'];?>"></td>
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
            <td class="td1"><label class="mark">*</label>营业时间：</td>
            <td >
                上午&nbsp;
                <select name="business_hours_start">
                    <?php for($x=0;$x<=12;$x++){ ?>
                        <option value =<?= $x ?> <?php if ($list['business_hours'][0]==$x) {echo 'selected';} ?>><?= $x ?>：00</option>
                    <?php } ?>
                </select>
                &nbsp;至&nbsp;&nbsp;下午
                <select name="business_hours_end">
                    <?php for($x=12;$x<=24;$x++){ ?>
                        <option value =<?= $x ?> <?php if ($list['business_hours'][1]==$x) {echo 'selected';} ?>><?= $x ?>：00</option>
                    <?php } ?>
                </select>
            </td>
        </tr>
        <tr>
            <td class="td1"><label class="mark">*</label>所在社区名称：</td>
            <td colspan="3"><input name="community_name" class="community_name NotNull" value="<?= $list['community_name'];?>" style="width:500px;"></td>
        </tr>
    </table>
    <table style="border-bottom: 1px solid #D5692B; width: 1000px; height: auto;">
        <tr><th colspan="4">清算信息</th></tr>
        <tr>
            <td colspan="1" class="td1"><label class="mark">*</label>帐户类型：</td>
            <td colspan="3" class="td2">
                <?php
                foreach ($init_array['account_type_data'] as $k => $v) {
                    ?>
                    <input type="radio" name="account_type" value="<?= $k ?>" <?php if($list['account_type']==$k) {echo 'checked';} ?>><?= $v ?>
                <?php
                }
                ?>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <table id="ZhiFuBaoZhangHao_Table" style="width: 1000px; height: auto; display: <?php if ($list['account_type']==1) {echo 'inline';} else {echo 'none';}?>">
                    <tr>
                        <td class="td1"><label class="mark">*</label>支付宝账号：</td>
                        <td><label><input name="alipay_name" class="alipay_name" value="<?= $list['alipay_name'];?>"></label></td>
                    </tr>
                </table>
                <table id="YinHangZhangHao_Table" style="width: 1000px; height: auto; display: <?php if ($list['account_type']==0) {echo 'inline';} else {echo 'none';}?>">
                    <tr><td><b>银行信息</b></td></tr>
                    <tr>
                        <td class="td1"><label class="mark">*</label>开户银行：</td>
                        <td class="td2">
                            <select name="bank">
                                <?php
                                foreach ($bank_list as $k => $v) {
                                    ?>
                                    <option value="<?= $v['id'] ?>" <?php if ($list['bank_id']==$v['id']) {echo 'selected';}  ?>><?= $v['name'] ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </td>
                        <td class="td1"><label class="mark">*</label>开户支行：</td>
                        <td><input type="text" name="bank_branch" class="bank_branch NotNull" value="<?= $list['bank_branch'];?>"></td>
                    </tr>
                    <tr>
                        <td class="td1"><label class="mark">*</label>所在省份：</td>
                        <td>
                            <select name="bank_province" onchange="SelCity(this)">
                                <?php
                                foreach ($province_list as $k => $v) {
                                    ?>
                                    <option value="<?= $v['id'] ?>" <?php if ($list['bank_province']==$v['id']) {echo 'selected';}  ?>><?= $v['name'] ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </td>
                        <td class="td1"><label class="mark">*</label>所在城市：</td>
                        <td>
                            <select id="city" name="bank_city">
                                <?php
                                foreach ($Province_City_list as $k => $v) {
                                    ?>
                                    <option value="<?= $v['id'] ?>" <?php if ($list['bank_city']==$v['id']) {echo 'selected';}  ?>><?= $v['name'] ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="td1"><label class="mark">*</label>银行卡号：</td>
                        <td><input name="bank_number" class="bank_number BankCardNum_Reg" value="<?= $list['bank_number'];?>" size="40"></td>
                        <td class="td1"><label class="mark">*</label>开户名称：</td>
                        <td><label><input name="bankcard_username" class="bankcard_username NotNull" value="<?= $list['bankcard_username'];?>"></label></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table style="border-bottom: 1px solid #D5692B; width: 1000px; height: auto;">
        <tr>
            <th colspan="4">结算信息</th>
        </tr>
        <tr>
            <td class="td1">
                <label class="mark">*</label>服务费用方式：</br>
                <input type="radio" id="gd" name="service_charge" value="0" <?php if($list['service_charge']==0) {echo 'checked';} ?> />固定服务费
                <input type="radio" id="yj" name="service_charge" value="1" <?php if($list['service_charge']==1) {echo 'checked';} ?> />服务佣金
            </td>
            <td class="td2">
                <input type="text" name="FuWuFeiYong" value="<?php if ($list['service_charge']==0) { echo $list['fixed_service_charge'];} if($list['service_charge']==1) {echo $list['service_commission'];}?>" >
                <label id="FeiYong_Sign" style="display: <?php if( $list['service_charge']==0) { echo "none";} else{ echo "inline";} ?>">%</label>
            </td>
            <td class="td1"><label class="mark">*</label>结算周期：</td>
            <td>
                <select name="settlement_cycle">
                    <?php
                    foreach ($init_array['settlement_cycle_data'] as $k => $v) {
                        ?>
                        <option value="<?= $k ?>" <?php if ($list['settlement_cycle']==$k) {echo 'selected';} ?>><?= $v ?></option>
                    <?php
                    }
                    ?>
                </select>
            </td>
        </tr>
    </table>
    <table style="border-bottom: 1px solid #D5692B; width: 1000px; height: auto;">
        <tr>
            <th colspan="4">其他信息</th>
        </tr>
        <tr>
            <td class="td1"><label class="mark">*</label>起止时间</td>
            <td class="td2">开始时间：<input type="text" name="start_time" class="start_time" onFocus="WdatePicker({isShowClear:true,readOnly:false})" value="<?= $list['start_time'];?>"></td>
            <td>结束时间：<input type="text" name="end_time" class="end_time" onFocus="WdatePicker({isShowClear:true,readOnly:false})" value="<?= $list['end_time'];?>"></td>
        </tr>
        <tr>
            <td class="td1"><label class="mark">*</label>业务员信息</td>
            <td>
                <label>业务员ID：<label style="color:red;"><input name="business_id" class="business_id CounterManId_Reg" value="<?= $business['id'];?>"></label></label>
            </td>
            <td>
                <label>业务员姓名：<label style="color:red;"><?= $business['name'];?></label></label>
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
<script type="text/javascript">
    $(document).ready(function(){
        //显示隐藏
        //服务费用的显示和隐藏
        $("input[name='service_charge']").click(function(){
            $("input[name='FuWuFeiYong']").val('');
            if ($(this).val()==1) {
                $("#FeiYong_Sign").css("display","inline");
            } else {
                $("#FeiYong_Sign").css("display","none");
            }
        });
        //支付方式的显示和隐藏
        $("input[name='account_type']").click(function(){
            if ($(this).val()==1) {
                $("#YinHangZhangHao_Table").css("display","none");
                $("#ZhiFuBaoZhangHao_Table").css("display","inline");
            } else {
                $("#ZhiFuBaoZhangHao_Table").css("display","none");
                $("#YinHangZhangHao_Table").css("display","inline");
            }
        });
        //公司性质的显示和隐藏
        $("input[name='company_nature']").click(function(){
            $("#GongSiXingZhiQiTaXinXi_Input").val('');
            if ($(this).val()!=4) {
                $("#GongSiXingZhiQiTaXinXi_Input").css("display","none");
            } else {
                $("#GongSiXingZhiQiTaXinXi_Input").css("display","inline");
            }
        });
    });
</script>
<script type="text/javascript">
    //转换城市
    function SelCity(file) {
        if(file.value!=='0') {
            var city=document.getElementById("city");
            city.options.length=0;//清空select城市的options
            $.ajax(
                {
                    type: "POST",
                    url: '/shop/shopcontract/getcityajax',
                    data: {'province_id':file.value},
                    async: false,
                    dataType: "json",
                    success: function (result) {
                        if(result==0){
                            //alert("省份没东西");
                        }else{
                            //alert("省份有东西");
                            var abc = new Option();
                            abc.text = '请选择城市';
                            abc.value = '0';
                            city.options[0] = abc;
                            for(var x = 0;x < result.length ; x++){
                                //alert(result[x]['name']);
                                var p=new Option();
                                p.text = result[x]['name'];
                                p.value = result[x]['id'];
                                city.options[x+1] = p;
                            }
                        }
                    }
                }
            );
        }
    }
</script>
<script type="text/javascript">
//验证数据规则
    //数字
    var Email_Reg        = /^([0-9a-zA-Z]([-.\w]*[0-9a-zA-Z])*@(([0-9a-zA-Z])+([-\w]*[0-9a-zA-Z])*\.)+[a-zA-Z]{2,9})$/;
    var Mobile_Reg       = /^1\d{10}$/;
    var Phone_Reg        = /^\d{3}-\d{8}|\d{4}-\d{7}$/;
    var QQ_Reg           = /^[1-9][0-9]{4,10}$/;
    var Float_Reg        = /^\d+(\.\d+)?$/;
    var BankCardNum_Reg  = /^[0-9]{0,30}$/;
    var DocumentNum_Reg  = /^[1-9]\d{16}(\d|x|X)$/;
    var CounterManId_Reg = /^\d+$/;
    var ImageFile_Reg    = /^.+.[jpg|gif|bmp|bnp|png]$/;
    var HtNum_Reg        = /^BJ[0-9]{6}$/;

    var Reg = new Array();
    Reg['Email_Reg']        = Email_Reg;
    Reg['Mobile_Reg']       = Mobile_Reg;
    Reg['Phone_Reg']        = Phone_Reg;
    Reg['QQ_Reg']           = QQ_Reg;
    Reg['Float_Reg']        = Float_Reg;
    Reg['BankCardNum_Reg']  = BankCardNum_Reg;
    Reg['DocumentNum_Reg']  = DocumentNum_Reg;
    Reg['CounterManId_Reg'] = CounterManId_Reg;
    Reg['ImageFile_Reg']    = ImageFile_Reg;
    Reg['HtNum_Reg']        = HtNum_Reg;
$(document).ready(function(){
    $("input").blur(function(){
        var Val   = $(this).val();
        //验证空值
        if (Val.length==0) {
            $(this).css('borderColor' ,'#A94442');
            return;
        }
        //验证规则
        var Class = $(this).attr("class");
        var LastSpaceIndex = Class.lastIndexOf(" ")+1;
        if (Class.length<0 || LastSpaceIndex-1<0) {
            return;
        }
        var x;
        //console.log(Class.substr(LastSpaceIndex));
        for (x in Reg) {
            //console.log(x);
            if (x==Class.substr(LastSpaceIndex)) {
                if (Reg[x].test(Val)) {
                    $(this).css('borderColor' ,'#ddd');
                } else {
                    $(this).css('borderColor' ,'#A94442');
                }
            }
        }
        //alert($(this).attr("class").substr(LastSpaceIndex));
    });
});
    //字符

</script>
