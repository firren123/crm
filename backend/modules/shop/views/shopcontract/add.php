<?php
/**
 * 简介1
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   I500
 * @filename  add.php
 * @author    weitonghe <weitonghe@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/8/6 上午13:32
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */
$this->title = '商家合同信息添加';
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
    //图片处理
    $(document).ready(function(){
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

    var file_image_reg = /^.+.[jpg|gif|bmp|bnp|png]$/i;

    function previewImage(file) {
        if (!file_image_reg.test(file.value)) {
            eval(file.id+'_img_is_ok=false');
            //HeTongPic_img_is_ok = false;
            alert("图片格式不正确,请重新选择!");
            return;
        }
        $('#'+'a_looked_'+file.name).html("关闭");
        eval(file.id+'_img_is_ok=true');
        //HeTongPic_img_is_ok = true;
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
    <h4><a href="/" >首页</a>&gt;<a href="/shop/shopcontract/index">商家合同列表</a><span>&gt;</span>商家合同信息完善</h4>
    <form action="showmsg" method="post" enctype="multipart/form-data">
        <table style="border-bottom: 1px solid #D5692B; width: 1000px; height: auto;">
            <tr>
                <th colspan="4">基本信息</th>
            </tr>
            <tr>
                <th colspan="1" class="td1"><label class="mark">*</label>合同号：</th>
                <th colspan="1" class="td2">
                    <input type="text" id="HtNum_Reg_is_ok" name="htnumber" class="htnumber HtNum_Reg"/>
                    </br>
                    <label id="htnumberlabel" style=" display: none; color: red;">合同号已存在，请重新填写！</label>
                </th>
            </tr>
            <tr>
                <td class="td1"><label class="mark">*</label>注册名称：</td>
                <td >
                    <input type="text" id="shop_contract_name_is_ok" name="shop_contract_name" class="shop_contract_name NotNull"/>
                    </br>
                    <label id="shop_contract_name_label" style=" display: none; color: red;">注册名称已存在，请重新填写！</label>
                </td>
                <td class="td1"><label class="mark">*</label>注册地址：</td>
                <td ><label><input type="text" id="registered_address_is_ok" name="registered_address" class="registered_address NotNull"/></label></td>
            </tr>
            <tr>
                <td class="td1"><label class="mark">*</label>注册登记号：</td>
                <td ><label><input type="text" id="registered_id_is_ok" name="registered_id" class="registered_id NotNull"/></label></td>
                <td class="td1">&nbsp;&nbsp;&nbsp;注册资本：</td>
                <td ><label><input type="text" id="registered_capital_is_ok" name="registered_capital" class="registered_capital Null"/></label></td>
            </tr>
            <tr>
                <td class="td1"><label class="mark">*</label>法定代表人：</td>
                <td ><label><input id="legal_representative_is_ok" name="legal_representative" class="legal_representative NotNull"/></label></td>
                <td class="td1">&nbsp;&nbsp;&nbsp;邮箱：</td>
                <td ><label><input id="email_is_ok" name="email" class="email Email_Reg Null"/></label></td>
            </tr>
            <tr>
                <td class="td1"><label class="mark">*</label>证件类型：</td>
                <td >
                    <select name="document_type">
                        <?php
                        foreach ($document_type_data as $k => $v) {
                            ?>
                            <option value="<?= $k; ?>"><?= $v; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </td>
                <td class="td1"><label class="mark">*</label>证件号：</td>
                <td ><label><input id="document_number_is_ok" name="document_number" class="document_number DocumentNum_Reg"/></label></td>
            </tr>
            <tr>
                <td class="td1"><label class="mark">*</label>联系人：</td>
                <td ><label><input id="contacts_is_ok" name="contacts" class="contacts NotNull"/></label></td>
                <td class="td1"><label class="mark">*</label>联系电话：</td>
                <td ><label><input id="contacts_umber_is_ok" name="contacts_umber" class="contacts_umber Mobile_Reg"/></label></td>
            </tr>
            <tr>
                <td colspan="1" class="td1"><label class="mark">*</label>公司性质：</td>
                <td colspan="3">
                    <?php
                    foreach ($company_nature_data as $k => $v) {
                        ?>
                        <input type="radio" id="company_nature_is_ok" name="company_nature" value="<?= $k; ?>"/><?= $v ?>
                    <?php
                    }
                    ?>
                    <input id="GongSiXingZhiQiTaXinXi_Input" type="text" name="company_nature_other" style="display: none"/>
                </td>
            </tr>
        </table>
        <table style="border-bottom: 1px solid #D5692B; width: 1000px; height: auto;">
            <tr>
                <th colspan="4" style="">经营信息</th>
            </tr>
<!--            <tr>-->
<!--                <td colspan="4">-->
<!--                    &nbsp;&nbsp;&nbsp;同店面注册名称&nbsp;&nbsp;&nbsp;&nbsp;-->
<!--                    <input type="radio" name="store_registration" value="0"/>是&nbsp;&nbsp;-->
<!--                    <input type="radio" name="store_registration" checked value="1"/>否&nbsp;&nbsp;-->
<!--                </td>-->
<!--            </tr>-->
            <tr>
                <td colspan="1" class="td1"><label class="mark">*</label>店面注册名称：</td>
                <td colspan="3" class="td2"><input id="business_name_is_ok" name="business_name" class="business_name NotNull"/></td>
            </tr>
            <tr>
                <td colspan="1" class="td1"><label class="mark">*</label>经营范围：</td>
                <td colspan="3">

                    <?php
                    foreach ($business_scope_data as $k => $v) {
                        ?>
                        <input type="checkbox" name="business_scope[]"/><?= $v; ?>
                    <?php
                    }
                    ?>

                </td>
            </tr>
            <tr>
                <td colspan="1" class="td1"><label class="mark">*</label>经营地址：</td>
                <td colspan="3"><input id="business_address_is_ok" name="business_address" class="business_address NotNull" style="width:500px;"></td>
            </tr>
        </table>
        <table style="border-bottom: 1px solid #D5692B; width: 1000px; height: auto;">
            <tr>
                <th colspan="4" style="">服务信息</th>
            </tr>
<!--            <tr>-->
<!--                <td colspan="4">-->
<!--                    &nbsp;&nbsp;&nbsp;同店面联系人&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-->
<!--                    <input type="radio" name="common_contacts"   value="0" />是&nbsp;&nbsp;-->
<!--                    <input type="radio" name="common_contacts" checked value="1" />否&nbsp;&nbsp;-->
<!--                </td>-->
<!--            </tr>-->
            <tr >
                <td class="td1"><label class="mark">*</label>日常联系人姓名：</td>
                <td class="td2"><input id="common_contacts_name_is_ok" name="common_contacts_name" class="common_contacts_name NotNull"/></td>
                <td class="td1"><label class="mark">*</label>电话：</td>
                <td><input id="common_contacts_phone_is_ok" name="common_contacts_phone" class="common_contacts_phone Mobile_Reg"/></td>
            </tr>
            <tr>
                <td class="td1">&nbsp;&nbsp;&nbsp;职务：</td>
                <td><label><input id="common_contacts_job_is_ok" name="common_contacts_job" class="common_contacts_job Null"/></label></td>
                <td class="td1">&nbsp;&nbsp;&nbsp;月均营业额：</td>
                <td><label><input id="monthly_turnover_is_ok" name="monthly_turnover" class="monthly_turnover Null"/></label></td>
            </tr>
            <tr>
                <td class="td1">&nbsp;&nbsp;&nbsp;面积：</td>
                <td><label><input id="area_is_ok" name="area" class="area Null"/></label>&nbsp;&nbsp;M<sup>2</sup></td>
                <td class="td1"><label class="mark">*</label>营业时间：</td>
                <td >
                    上午&nbsp;
                    <select id="business_hours_start_is_ok" name="business_hours_start">
                        <?php for($x=0;$x<=12;$x++){ ?>
                            <option value =<?= $x ?>><?= $x ?>：00</option>
                        <?php } ?>
                    </select>
                    &nbsp;至&nbsp;&nbsp;下午
                    <select id="business_hours_end_is_ok" name="business_hours_end">
                        <?php for($x=12;$x<=24;$x++){ ?>
                            <option value =<?= $x ?>><?= $x ?>：00</option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="td1"><label class="mark">*</label>所在社区名称：</td>
                <td colspan="3"><input id="community_name_is_ok" name="community_name" class="community_name NotNull" style="width:500px;"></td>
            </tr>
        </table>
        <table style="border-bottom: 1px solid #D5692B; width: 1000px; height: auto;">
            <tr><th colspan="4">清算信息</th></tr>
            <tr>
                <td colspan="1" class="td1"><label class="mark">*</label>帐户类型：</td>
                <td colspan="3" class="td2">
                    <?php
                    foreach ($account_type_data as $k => $v) {
                        ?>
                        <input type="radio" name="account_type" value="<?= $k ?>" <?php if($k==0){echo 'checked';} ?>/><?= $v ?>
                    <?php
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <table id="ZhiFuBaoZhangHao_Table" style="width: 1000px; height: auto; display: none">
                        <tr>
                            <td class="td1"><label class="mark">*</label>支付宝账号：</td>
                            <td><label><input name="alipay_name" class="alipay_name" /></label></td>
                        </tr>
                    </table>
                    <table id="YinHangZhangHao_Table" style="width: 1000px; height: auto; display: inline">
                        <tr><td><b>银行信息</b></td></tr>
                        <tr>
                            <td class="td1"><label class="mark">*</label>开户银行：</td>
                            <td class="td2">
                                <select id="bank_is_ok" name="bank_id">
                                    <option value="0">请选择银行</option>
                                    <?php
                                    foreach ($Bank_result as $k => $v) {
                                        ?>
                                        <option value="<?= $v['id'] ?>"><?= $v['name'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </td>
                            <td class="td1"><label class="mark">*</label>开户支行：</td>
                            <td><input type="text" id="bank_branch_is_ok" name="bank_branch" class="bank_branch NotNull"/></td>
                        </tr>
                        <tr>
                            <td class="td1"><label class="mark">*</label>所在省份：</td>
                            <td>
                                <select id="bank_province_is_ok" name="bank_province" onchange="SelCity(this)">
                                    <option value="0">请选择省份</option>
                                    <?php
                                    foreach ($Province_result as $k => $v) {
                                        ?>
                                        <option value="<?= $v['id'] ?>"><?= $v['name'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </td>
                            <td class="td1"><label class="mark">*</label>所在城市：</td>
                            <td>
                                <select id="city" name="bank_city">
                                    <option value="0">请选择城市</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="td1"><label class="mark">*</label>银行卡号：</td>
                            <td><input id="bank_number_is_ok" name="bank_number" class="bank_number BankCardNum_Reg" size="40"/></td>
                            <td class="td1"><label class="mark">*</label>开户名称：</td>
                            <td><input id="bankcard_username_is_ok" name="bankcard_username" class="bankcard_username NotNull"/></td>
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
                    <input type="radio" id="gd" name="service_charge" value="0" checked/>固定服务费
                    <input type="radio" id="yj" name="service_charge" value="1"/>服务佣金
                </td>
                <td class="td2">
                    <input type="text" id="FuWuFeiYong_is_ok" name="FuWuFeiYong" class="FuWuFeiYong Float_Reg"/>
                    <label id="FeiYong_Sign" style="display: none">%</label>
                </td>
                <td class="td1"><label class="mark">*</label>结算周期：</td>
                <td>
                    <select name="settlement_cycle">
                        <?php
                        foreach ($settlement_cycle_data as $k => $v) {
                            ?>
                            <option value="<?= $k ?>"><?= $v ?></option>
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
                <td class="td1"><label class="mark">*</label>合同起止时间</td>
                <td class="td2">开始时间：<input type="text" id="start_time_is_ok" name="start_time" class="start_time Time_Reg" onFocus="WdatePicker({isShowClear:true,readOnly:false})"/></td>
                <td>结束时间：<input type="text" id="end_time_is_ok" name="end_time" class="end_time Time_Reg" onFocus="WdatePicker({isShowClear:true,readOnly:false})"/></td>
            </tr>
            <tr>
                <td class="td1"><label class="mark">*</label>业务员信息</td>
                <td>
                    <label>业务员ID：<label style="color:red;"><input id="business_id_is_ok" name="counterman" class="business_id CounterManId_Reg"/></label></label>
                </td>
                <td>
                    <label>业务员姓名：<label id="countermanmsg" style="color:red;"></label></label>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <th colspan="1" class="td1">合同图片</th>
                <td colspan="1">
                    <a class="a_looked" name="HeTong" id="a_looked_HeTong"></a>&nbsp;&nbsp;&nbsp;&nbsp;
                    <a class="a_choose" name="HeTong">选择图片</a>
                    <input id="HeTongPic" type="file" name="HeTong" onchange="previewImage(this)"/>
                    <input type="hidden" id="HeTongPic_img_is_ok" class="a_i" />
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <div id="preview_HeTongPic">
                        <img id="HeTongPic_img" style=" display: none; max-width: 1000px; height: auto;"/>
                    </div>
                </td>
            </tr>
            <tr>
                <th colspan="1" class="td2">营业执照图片</th>
                <td colspan="1">
                    <a class="a_looked" name="YingYeZhiZhao" id="a_looked_YingYeZhiZhao"></a>&nbsp;&nbsp;&nbsp;&nbsp;
                    <a class="a_choose" name="YingYeZhiZhao">选择图片</a>
                    <input id="YingYeZhiZhaoPic" type="file" name="YingYeZhiZhao" onchange="previewImage(this)"/>
                    <input type="hidden" id="YingYeZhiZhaoPic_img_is_ok" class="b_i"/>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <div id="preview_YingYeZhiZhaoPic">
                        <img id="YingYeZhiZhaoPic_img" style=" display: none; max-width: 1000px; height: auto;"/>
                    </div>
                </td>
            </tr>
            <tr>
                <th colspan="1" class="td3">银行卡图片</th>
                <td colspan="1">
                    <a class="a_looked" name="YinHangKa" id="a_looked_YinHangKa"></a>&nbsp;&nbsp;&nbsp;&nbsp;
                    <a class="a_choose" name="YinHangKa">选择图片</a>
                    <input id="YinHangKaPic" type="file" name="YinHangKa" onchange="previewImage(this)"/>
                    <input type="hidden" id="YinHangKaPic_img_is_ok" class="c_i"/>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <div id="preview_YinHangKaPic">
                        <img id="YinHangKaPic_img" style=" display: none; max-width: 1000px; height: auto;"/>
                    </div>
                </td>
            </tr>
            <tr>
                <th colspan="1" class="td4">身份证图片</th>
                <td colspan="1">
                    <a class="a_looked" name="ShenFenZheng" id="a_looked_ShenFenZheng"></a>&nbsp;&nbsp;&nbsp;&nbsp;
                    <a class="a_choose" name="ShenFenZheng">选择图片</a>
                    <input id="ShenFenZhengPic" type="file" name="ShenFenZheng" onchange="previewImage(this)"/>
                    <input type="hidden" id="ShenFenZhengPic_img_is_ok" class="d_i"/>
                </td>
            </tr>
            <tr>
                <td colspan="4">
                    <div id="preview_ShenFenZhengPic">
                        <img id="ShenFenZhengPic_img" style=" display: none; max-width: 1000px; height: auto;"/>
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
                    <textarea style=" display: none;" maxlength="255" rows="5" cols="100" class="BeiZhu" name="remark"></textarea>
                </td>
            </tr>
        </table>
        <a class="btn btn-primary" onclick="Confirm()">添加</a>
        <a class="btn cancelBtn" href="javascript:history.go(-1);">取消</a>
        <input type="hidden" name="_csrf" value="<?php echo \Yii::$app->getRequest()->getCsrfToken(); ?>"/>
    </form>
</div>
<script type="text/javascript">
    $(document).ready(function(){
//        //是否同上
//        //是否同店面注册名称
//        $("input[name='store_registration']").click(function(){
//            var Val = $(this).val();
//            var top = $("input[name='shop_contract_name']");
//            var below = $("input[name='business_name']");
//            if (Val==0) {//是
//                if (top.val()=='') {
//                    shop_contract_name_is_ok = false;
//                    business_name_is_ok = false;
//                    top.css('borderColor' ,'#A94442');
//                    below.css('borderColor' ,'#A94442');
//                    //below.css('borderColor' ,'#ddd');
//                } else {
//                    shop_contract_name_is_ok = true;
//                    business_name_is_ok = true;
//                    top.css('borderColor' ,'#ddd');
//                    below.css('borderColor' ,'#ddd');
//                }
//                below.val(top.val());
//            } else {//否
//                business_name_is_ok = false;
//                below.css('borderColor' ,'#ddd');
//                below.val('');
//            }
//        });
//        //如果同店面联系人时要上下级联
//        $("input[name='shop_contract_name']").keyup(function(){
//            var is_go = $("input[name='store_registration']:checked");
//            var Val   = $("input[name='business_name']");
//            if (is_go.val()==0) {
//                Val.val($(this).val());
//            }
//          //console.log(Val+'shop_contract_name'+is_go.val());
//        });
//        $("input[name='business_name']").keyup(function(){
//            var is_go = $("input[name='store_registration']:checked");
//            var Val   = $("input[name='shop_contract_name']");
//            if (is_go.val()==0) {
//                Val.val($(this).val());
//            }
//            //console.log(Val+'shop_contract_name'+is_go.val());
//        });
//        //是否同联系人 姓名和电话
//        $("input[name='common_contacts']").click(function(){
//            var Val = $(this).val();
//            var top_name = $("input[name='contacts']");
//            var top_mobile = $("input[name='contacts_umber']");
//            var below_name = $("input[name='common_contacts_name']");
//            var below_mobile = $("input[name='common_contacts_phone']");
//            if (Val==0) {//是
//                if (contacts_is_ok) {
//                    common_contacts_name_is_ok = true;
//                    top_name.css('borderColor' ,'#ddd');
//                    below_name.css('borderColor' ,'#ddd');
//                } else {
//                    common_contacts_name_is_ok = false;
//                    top_name.css('borderColor' ,'#A94442');
//                    below_name.css('borderColor' ,'#A94442');
//                }
//                if (contacts_umber_is_ok) {
//                    common_contacts_phone_is_ok = true;
//                    top_mobile.css('borderColor' ,'#ddd');
//                    below_mobile.css('borderColor' ,'#ddd');
//                } else {
//                    common_contacts_phone_is_ok = false;
//                    top_mobile.css('borderColor' ,'#A94442');
//                    below_mobile.css('borderColor' ,'#A94442');
//                }
//                below_name.val(top_name.val());
//                below_mobile.val(top_mobile.val());
//            } else {//否
//                common_contacts_name_is_ok = false;
//                common_contacts_phone_is_ok = false;
//                below_name.css('borderColor' ,'#ddd');
//                below_name.val('');
//                below_mobile.css('borderColor' ,'#ddd');
//                below_mobile.val('');
//            }
//
//        });

        //显示隐藏
        //服务费用的显示和隐藏
        $("input[name='service_charge']").click(function(){
            FuWuFeiYong_is_ok = false;
            $("input[name='FuWuFeiYong']").css('borderColor' ,'#A94442');
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
    var BankCardNum_Reg  = /^[0-9]{16}$|^[0-9]{19}$|^[0-9]{21}$/;
    var DocumentNum_Reg  = /^[1-9]\d{16}(\d|x|X)$/;
    var CounterManId_Reg = /^\d+$/;
    var ImageFile_Reg    = /^.+.[jpg|gif|bmp|bnp|png]$/i;
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

    var HtNum_Reg_is_ok = false;            //合同号
    var shop_contract_name_is_ok = false;   //注册名称
    var registered_address_is_ok = false;   //注册地址
    var registered_id_is_ok = false;        //注册登记号
    var registered_capital_is_ok = true;    //注册资本
    var legal_representative_is_ok = false; //法定代表人
    var email_is_ok = true;                 //邮箱
    var document_number_is_ok = false;      //证件号
    var contacts_is_ok = false;             //联系人
    var contacts_umber_is_ok = false;       //联系电话
    var company_nature_is_ok = false;       //公司性质
    var business_name_is_ok = false;        //店面注册名称
    var business_scope_is_ok = false;       //经营范围
    var business_address_is_ok = false;     //经营地址
    var common_contacts_name_is_ok = false; //日常联系人姓名
    var common_contacts_phone_is_ok = false;//电话
    var common_contacts_job_is_ok = true;   //职务
    var monthly_turnover_is_ok = true;      //月均营业额
    var area_is_ok = true;                  //面积
    var business_hours_start_is_ok = true;  //营业时间
    var business_hours_end_is_ok = true;    //营业时间
    var community_name_is_ok = false;       //所在社区名
    var bank_is_ok = false;                 //开户银行
    var bank_branch_is_ok = false;          //开户支行
    var bank_province_is_ok = false;        //所在省份
    var city_is_ok = false;                 //所在城市
    var bank_number_is_ok = false;          //银行卡号
    var bankcard_username_is_ok = false;    //开户名称
    var FuWuFeiYong_is_ok = false;          //费用
    var start_time_is_ok = false;           //开始时间
    var end_time_is_ok = false;             //结束时间
    var business_id_is_ok = false;          //业务员ID
    var HeTongPic_img_is_ok = false;        //合同图片
    var YingYeZhiZhaoPic_img_is_ok = true;  //营业执照图片
    var YinHangKaPic_img_is_ok = true;      //银行卡图片
    var ShenFenZhengPic_img_is_ok = true;   //身份证图片
    $(document).ready(function(){

        //银行卡格式
        $("#bank_number_is_ok").keyup(function(){
            $(this).val($(this).val().replace(/\s/g,'').replace(/(\d{4})(?=\d)/g,"$1 "));//输入银行卡号，4位自动加空格
        });

        //blur事件
        $("input").blur(function(){
            var Val = $(this).val();

            //是否同店面注册名称
            if ($(this).attr("name")=='store_registration') {
                return;
            }
            //是否同店面联系人
            if ($(this).attr("name")=='common_contacts') {
                return;
            }

            //费用
            if ($(this).attr("name")=='FuWuFeiYong') {
                if (Float_Reg.test(Val) && Val!=0) {
                    FuWuFeiYong_is_ok = true;
                    $(this).css('borderColor' ,'#ddd');
                } else {
                    FuWuFeiYong_is_ok = false;
                    $(this).css('borderColor' ,'#A94442');
                }
                return;
            }

            //业务员
            if ($(this).attr("name")=='counterman') {
                if ($.trim(Val)=='') {
                    business_id_is_ok = false;
                    $("#business_id_is_ok").css('borderColor' ,'#A94442');
                    return;
                } else {
                    if (!CounterManId_Reg.test(Val)) {
                        business_id_is_ok = false;
                        $("#business_id_is_ok").css('borderColor' ,'#A94442');
                        return;
                    } else {
                        $.ajax({
                            type: "POST",
                            url: '/shop/shopcontract/getcountermanidajax',
                            data: {'msg':Val},
                            asynic: false,
                            dataType: "json",
                            success: function (result) {
                                if (result==0) {
                                    business_id_is_ok = false;
                                    document.getElementById('countermanmsg').innerHTML="未找到ID为:"+Val+"的业务员！";
                                    $("#business_id_is_ok").css('borderColor' ,'#A94442');
                                } else {
                                    business_id_is_ok = true;
                                    document.getElementById('countermanmsg').innerHTML=result['name'];
                                    $("#business_id_is_ok").css('borderColor' ,'#ddd');
                                }
                            }
                        });
                        return;
                    }
                }
            }

            //合同号单独验证 并判断唯一性
            if ($(this).attr("name")=='htnumber') {
                if ($.trim(Val)=='') {
                    HtNum_Reg_is_ok = false;
                    $("#HtNum_Reg_is_ok").css('borderColor' ,'#A94442');
                    return;
                } else {
                    if (!HtNum_Reg.test(Val)) {
                        HtNum_Reg_is_ok = false;
                        $("#HtNum_Reg_is_ok").css('borderColor' ,'#A94442');
                        return;
                    } else {
                        $.ajax({
                            type: "GET",
                            url: '/shop/shopcontract/gethtnumberajax',
                            data: {
                                'htnumber':Val,
                                'id':$("#id").val()
                            },
                            asynic: false,
                            dataType: "json",
                            success: function (result) {
                                if (result==1) {
                                    $("#HtNum_Reg_is_ok").css('borderColor' ,'#ddd');
                                    $("#htnumberlabel").css("display","none");
                                    HtNum_Reg_is_ok = true;
                                } else {
                                    $("#HtNum_Reg_is_ok").css('borderColor' ,'#A94442');
                                    $("#htnumberlabel").css("display","block");
                                    HtNum_Reg_is_ok = false;
                                }
                            }
                        });
                        return;
                    }
                }
            }

            //注册名称单独验证 并判断唯一性
            if ($(this).attr("name")=='shop_contract_name') {
                if ($.trim(Val)=='') {
                    shop_contract_name_is_ok = false;
                    $("#shop_contract_name_is_ok").css('borderColor' ,'#A94442');
                    return;
                } else {
                    $.ajax({
                        type: "GET",
                        url: '/shop/shopcontract/getzcmcajax',
                        data: {
                            'shop_contract_name':Val,
                            'id':$("#id").val()
                        },
                        asynic: false,
                        dataType: "json",
                        success: function (result) {
                            if (result==1) {
                                $("#shop_contract_name_is_ok").css('borderColor' ,'#ddd');
                                $("#shop_contract_name_label").css("display","none");
                                shop_contract_name_is_ok = true;
                            } else {
                                $("#shop_contract_name_is_ok").css('borderColor' ,'#A94442');
                                $("#shop_contract_name_label").css("display","block");
                                shop_contract_name_is_ok = false;
                            }
                        }
                    });
                    return;
                }
            }

            //开始时间和结束时间处理
            if ($(this).attr("name")=='start_time') {
                var end_time = $("#end_time_is_ok").val();
                if ($.trim(Val)!='0000-00-00' && $.trim(Val)<end_time) {
                    eval($(this).attr("id")+'=true');
                    $(this).css('borderColor' ,'#ddd');
                    eval($("#end_time_is_ok").attr("id")+'=true');
                    $("#end_time_is_ok").css('borderColor' ,'#ddd');
                } else {
                    eval($(this).attr("id")+'=false');
                    $(this).css('borderColor' ,'#A94442');
                    eval($("#end_time_is_ok").attr("id")+'=false');
                    $("#end_time_is_ok").css('borderColor' ,'#A94442');
                }
                return;
            }
            if ($(this).attr("name")=='end_time') {
                var start_time = $("#start_time_is_ok").val();
                if ($.trim(Val)!='0000-00-00' && $.trim(Val)>start_time) {
                    eval($(this).attr("id")+'=true');
                    $(this).css('borderColor' ,'#ddd');
                    eval($("#start_time_is_ok").attr("id")+'=true');
                    $("#start_time_is_ok").css('borderColor' ,'#ddd');
                } else {
                    eval($(this).attr("id")+'=false');
                    $(this).css('borderColor' ,'#A94442');
                    eval($("#start_time_is_ok").attr("id")+'=false');
                    $("#start_time_is_ok").css('borderColor' ,'#A94442');
                }
                return;
            }

            //公司性质处理
            if ($(this).attr("name")=='company_nature') {
                $("input[name='company_nature']").each(function (index, element) {
                    if (element.value!=4 && element.checked) {
                        company_nature_is_ok = true; return false;
                    }
                    if (element.value==4 && element.checked && $.trim($("#GongSiXingZhiQiTaXinXi_Input").val())==''){
                        company_nature_is_ok = false; return false;
                    }
                });
                //console.log('公司性质:'+company_nature_is_ok);
                return;
            }
            //公司性质其他信息
            if ($(this).attr("name")=='company_nature_other') {
                if ($.trim($(this).val())=='') {
                    company_nature_is_ok = false;
                } else {
                    company_nature_is_ok = true;
                }
                //console.log('公司性质其他信息:'+company_nature_is_ok);
                return;
            }

            //经营范围处理
            if ($(this).attr("name")=='business_scope[]') {
                //console.log($(this).attr("name"));
                //console.log($(this).val());
                //console.log($(this).is(':checked'));
                $("input[type='checkbox'").each(function (index, element) {
                    //console.log(index);
                    if (element.checked) {
                        business_scope_is_ok = true;return false;
                    } else {
                        business_scope_is_ok = false;
                    }
                });
                //console.log($("input:[name='business_scope']:checked").val());
                //console.log('经营范围:'+business_scope_is_ok);
                return;
            }

//        //验证空值
//        if (Val.length==0) {
//            $(this).css('borderColor' ,'#A94442');
//            return;
//        }
            //获得class标签中最后一个空格后的class
            var Class = $(this).attr("class");
            var LastSpaceIndex = Class.lastIndexOf(" ")+1;
            if (Class.length<0 || LastSpaceIndex-1<0) {
                return;
            }
            //验证规则
            //NotNull
            if ('NotNull'==Class.substr(LastSpaceIndex)) {
                if ($.trim(Val)!='') {
                    eval($(this).attr("id")+'=true');
                    $(this).css('borderColor' ,'#ddd');
                } else {
                    eval($(this).attr("id")+'=false');
                    $(this).css('borderColor' ,'#A94442');
                }
                return;
            }

            //Null
            if ('Null'==Class.substr(LastSpaceIndex)) {
                //注册资本
                if ($(this).attr("name")=='registered_capital') {
                    if ($.trim(Val)=='') {
                        eval($(this).attr("id")+'=true');
                        $(this).css('borderColor' ,'#ddd');
                    } else {
                        if (Float_Reg.test(Val)) {
                            eval($(this).attr("id")+'=true');
                            $(this).css('borderColor' ,'#ddd');
                        } else {
                            eval($(this).attr("id")+'=false');
                            $(this).css('borderColor' ,'#A94442');
                        }
                    }
                }
                //邮箱
                if ($(this).attr("name")=='email') {
                    if ($.trim(Val)=='') {
                        eval($(this).attr("id")+'=true');
                        $(this).css('borderColor' ,'#ddd');
                    } else {
                        if (Email_Reg.test(Val)) {
                            eval($(this).attr("id")+'=true');
                            $(this).css('borderColor' ,'#ddd');
                        } else {
                            eval($(this).attr("id")+'=false');
                            $(this).css('borderColor' ,'#A94442');
                        }
                    }
                }
                //面积
                if ($(this).attr("name")=='area') {
                    if ($.trim(Val)=='') {
                        eval($(this).attr("id")+'=true');
                        $(this).css('borderColor' ,'#ddd');
                    } else {
                        if (Float_Reg.test(Val)) {
                            eval($(this).attr("id")+'=true');
                            $(this).css('borderColor' ,'#ddd');
                        } else {
                            eval($(this).attr("id")+'=false');
                            $(this).css('borderColor' ,'#A94442');
                        }
                    }
                }
                //月均营业额
                if ($(this).attr("name")=='monthly_turnover') {
                    if ($.trim(Val)=='') {
                        eval($(this).attr("id")+'=true');
                        $(this).css('borderColor' ,'#ddd');
                    } else {
                        if (Float_Reg.test(Val)) {
                            eval($(this).attr("id")+'=true');
                            $(this).css('borderColor' ,'#ddd');
                        } else {
                            eval($(this).attr("id")+'=false');
                            $(this).css('borderColor' ,'#A94442');
                        }
                    }
                }
                //职务
                if ($(this).attr("name")=='monthly_turnover') {

                }
                return;
            }

            //Reg
            var x;
            //console.log(Class.substr(LastSpaceIndex));
            //console.log(Val);
            //银行卡号处理
            if (Class.substr(LastSpaceIndex)=='BankCardNum_Reg') {
                var bank_number = Val;
                while (bank_number.indexOf(" ")!=-1) {
                    bank_number = bank_number.replace(" ","");
                }
                Val = bank_number;
            }

            //循环比对
            for (x in Reg) {
                //console.log(x);
                if (x==Class.substr(LastSpaceIndex)) {
                    //console.log('=');
                    //console.log(Val);
                    //console.log(Reg[x].test(Val));
                    if (Reg[x].test(Val)) {
                        eval($(this).attr("id")+'=true');
                        $(this).css('borderColor' ,'#ddd');
                    } else {
                        eval($(this).attr("id")+'=false');
                        $(this).css('borderColor' ,'#A94442');
                    }
                    //console.log(bank_number_is_ok);
                    return;
                }
            }
            //alert($(this).attr("class").substr(LastSpaceIndex));
        });
    });

</script>
<script type="text/javascript">
    //提交
    function Confirm() {
        if (!HtNum_Reg_is_ok) {
            alert("合同号格式不正确!");return false;
        }
        if (!shop_contract_name_is_ok) {
            alert("注册名称不能为空!");return false;
        }
        if (!registered_address_is_ok) {
            alert("注册地址不能为空!");return false;
        }
        if (!registered_id_is_ok) {
            alert("注册登记号不能为空!");return false;
        }
        if (!registered_capital_is_ok) {
            alert("注册资本格式不正确!");return false;
        }
        if (!legal_representative_is_ok) {
            alert("法定代表人不能为空!");return false;
        }
        if (!email_is_ok) {
            alert("邮箱格式不正确!");return false;
        }
        if (!document_number_is_ok) {
            alert("证件号格式不正确!");return false;
        }
        if (!contacts_is_ok) {
            alert("联系人不能为空!");return false;
        }
        if (!contacts_umber_is_ok) {
            alert("联系电话不能为空!");return false;
        }
        if (!company_nature_is_ok) {
            alert("公司性质不能为空!");return false;
        }
        if (!business_name_is_ok) {
            alert("店面注册名称不能为空!");return false;
        }
        if (!business_scope_is_ok) {
            alert("请勾选经营范围!");return false;
        }
        if (!business_address_is_ok) {
            alert("经营地址不能为空!");return false;
        }
        if (!common_contacts_name_is_ok) {
            alert("日常联系人姓名不能为空!");return false;
        }
        if (!common_contacts_phone_is_ok) {
            alert("日常联系人电话格式不正确!");return false;
        }
        if (!monthly_turnover_is_ok) {
            alert("月均营业额格式不正确!");return false;
        }
        if (!area_is_ok) {
            alert("面积格式不正确!");return false;
        }
        if (!community_name_is_ok) {
            alert("所在社区名称不能为空!");return false;
        }
        if (!bank_branch_is_ok) {
            alert("开户支行不能为空!");return false;
        }
        if ($("#bank_is_ok option:selected").val()==0) {
            alert("请选择银行!");return false;
        }
        if ($("#bank_province_is_ok option:selected").val()==0) {
            alert("请选择省份!");return false;
        }
        if ($("#city option:selected").val()==0) {
            alert("请选择城市!");return false;
        }
        if (!bank_number_is_ok) {
            alert("银行卡号格式不正确!");return false;
        }
        if (!bankcard_username_is_ok) {
            alert("开户名称不能为空!");return false;
        }
        if (!FuWuFeiYong_is_ok) {
            alert("费用格式不正确!");return false;
        }
        if (!start_time_is_ok || !end_time_is_ok) {
            alert("开始时间应小于结束时间!");return false;
        }
        if (!business_id_is_ok) {
            alert("请填写正确的业务员ID!");return false;
        }
        if (!HeTongPic_img_is_ok) {
            alert("合同图片格式不正确!");return false;
        }
        $("form").submit();
    }
</script>
