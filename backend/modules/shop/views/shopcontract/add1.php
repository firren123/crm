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
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets;
$this->title = '商家合同添加';
?>
<script src="/js/My97DatePicker/WdatePicker.js"></script>
<script language="JavaScript">
    var shop_contract_name=false;//注册名称
    function check_shop_contract_name(file){
        if(file.value==''){
            file.style.borderColor="#A94442";
            shop_contract_name = false;
        }else{
            file.style.borderColor="#ddd";
            shop_contract_name = true;
            reg_shop_contract_name(file);
        }
    }
    //验证注册名称唯一性
    function reg_shop_contract_name(file){
        var shop_contract_name_msg=file.value;
        if(file.value==''){
            document.getElementById("shop_contract_name_label").style.display="none";
        }else{
            $.ajax(
                {
                    type: "GET",
                    url: '/shop/shopcontract/getzcmcajax',
                    data: {'shop_contract_name':shop_contract_name_msg},
                    asynic: false,
                    dataType: "json",
                    beforeSend: function () {
                    },
                    success: function (result) {
                        if(result==1){
                            document.getElementById("shop_contract_name_label").style.display="none";
                            shop_contract_name=true;
                        }else{
                            document.getElementById("shop_contract_name_label").style.display="inline";
                            shop_contract_name=false;
                        }
                    }
                });
        }
    }
    var registered_address=false;//注册地址
    function check_registered_address(file){
        if(file.value==''){
            file.style.borderColor="#A94442";
            registered_address=false;
        }else{
            file.style.borderColor="#ddd";
            registered_address=true;
        }
    }
    var registered_id=false;//注册登记号
    function check_registered_id(file){
        if(file.value==''){
            file.style.borderColor="#A94442";
            registered_id=false;
        }else{
            file.style.borderColor="#ddd";
            registered_id=true;
        }
    }
    var registered_capital=true;//注册资本
    function check_registered_capital(file){
        if(file.value==''){
            file.style.borderColor="#ddd";
            registered_capital=true;
        }else{
            file.style.borderColor="#ddd";
            registered_capital=true;
        }
    }
    var legal_representative=false;//法定代表人
    function check_legal_representative(file){
        if(file.value==''){
            file.style.borderColor="#A94442";
            legal_representative=false;
        }else{
            file.style.borderColor="#ddd";
            legal_representative=true;
        }
    }
    var email=true;//邮箱                                          1
    function check_email(file){
        if(file.value==''){
            file.style.borderColor="#A94442";
            email=true;
        }else{
            file.style.borderColor="#ddd";
            email=true;
        }
    }
    var document_type=false;//证件类型
    function check_document_type(file){
        if(file.value==''){
            file.style.borderColor="#A94442";
            document_type=false;
        }else{
            file.style.borderColor="#ddd";
            document_type=true;
        }
    }
    var document_number=false;//证件号码                          1
    function check_document_number(file){
        if(file.value==''){
            file.style.borderColor="#A94442";
            document_number=false;
        }else{
            file.style.borderColor="#ddd";
            document_number=true;
        }
    }
    var contacts=false;//联系人
    function check_contacts(file){
        if(file.value==''){
            file.style.borderColor="#A94442";
            contacts=false;
        }else{
            file.style.borderColor="#ddd";
            contacts=true;
        }
    }
    var phonea=false;//联系电话                           1
    function check_contacts_umber(file){
        if(file.value==''){
            file.style.borderColor="#A94442";
            phonea=false;
        }else{
            file.style.borderColor="#ddd";
            phonea=true;
        }
    }
    var company_nature=false;//公司性质
    function check_company_nature(file){
        if(file.value==''){
            file.style.borderColor="#A94442";
            company_nature=false;
        }else{
            file.style.borderColor="#ddd";
            company_nature=true;
        }
    }
    var company_nature_other=false;//公司性质其他信息
    function check_company_nature_other(file){
        if(file.value==''){
            file.style.borderColor="#A94442";
            company_nature_other=false;
        }else{
            file.style.borderColor="#ddd";
            company_nature_other=true;
        }
    }

    var store_registration_name=false;//店面注册名称
    function check_store_registration_name(file){
        if(file.value==''){
            file.style.borderColor="#A94442";
            store_registration_name=false;
        }else{
            file.style.borderColor="#ddd";
            store_registration_name=true;
            reg_store_registration_name(file);
        }
    }
    //验证店面注册名称唯一性
    function reg_store_registration_name(file){
        var shop_contract_name_msg=file.value;
        if(file.value==''){
            document.getElementById("store_registration_name_label").style.display="none";
        }else{
            $.ajax(
                {
                    type: "GET",
                    url: '/shop/shopcontract/getzcmcajax',
                    data: {'shop_contract_name':shop_contract_name_msg},
                    asynic: false,
                    dataType: "json",
                    beforeSend: function () {
                    },
                    success: function (result) {
                        if(result==1){
                            document.getElementById("store_registration_name_label").style.display="none";
                            shop_contract_name=true;
                        }else{
                            document.getElementById("store_registration_name_label").style.display="inline";
                            shop_contract_name=false;
                        }
                    }
                });
        }
    }
    var business_scope=false;//经营范围
    function check_business_scope(file){
        if(file.value==''){
            file.style.borderColor="#A94442";
            business_scope=false;
        }else{
            file.style.borderColor="#ddd";
            business_scope=true;
        }
    }
    var business_address=false;//经营地址
    function check_business_address(file){
        if(file.value==''){
            file.style.borderColor="#A94442";
            business_address=false;
        }else{
            file.style.borderColor="#ddd";
            business_address=true;
        }
    }

    var common_contacts=false;//日常联系人
    function check_common_contacts(file){
        if(file.value==''){
            file.style.borderColor="#A94442";
            common_contacts=false;
        }else{
            file.style.borderColor="#ddd";
            common_contacts=true;
        }
    }
    var common_contacts_job=true;//非日常联系人职务
    function check_common_contacts_job(file){
        if(file.value==''){
            file.style.borderColor="#ddd";
            common_contacts_job=true;
        }else{
            file.style.borderColor="#ddd";
            common_contacts_job=true;
        }
    }
    var common_contacts_name=false;//非日常联系人姓名
    function check_common_contacts_name(file){
        if(file.value==''){
            file.style.borderColor="#A94442";
            common_contacts_name=false;
        }else{
            file.style.borderColor="#ddd";
            common_contacts_name=true;
        }
    }
    var phoneb=false;//非日常联系人电话             1
    function check_common_contacts_phone(file){
        if(file.value==''){
            file.style.borderColor="#A94442";
            phoneb=false;
        }else{
            file.style.borderColor="#ddd";
            phoneb=true;
        }
    }
    var business_hours_start=false;//营业时间上午
    function check_business_hours_start(file){
        if(file.value==''){
            file.style.borderColor="#A94442";
            business_hours_start=false;
        }else{
            file.style.borderColor="#ddd";
            business_hours_start=true;
        }
    }
    var business_hours_end=false;//营业时间下午
    function check_business_hours_end(file){
        if(file.value==''){
            file.style.borderColor="#A94442";
            business_hours_end=false;
        }else{
            file.style.borderColor="#ddd";
            business_hours_end=true;
        }
    }
    var area=true;//面积                                             1
    function check_area(file){
        if(file.value==''){
            file.style.borderColor="#A94442";
            area=true;
        }else{
            file.style.borderColor="#ddd";
            area=true;
        }
    }
    var community_name=false;//所在社区名称
    function check_community_name(file){
        if(file.value==''){
            file.style.borderColor="#A94442";
            community_name=false;
        }else{
            file.style.borderColor="#ddd";
            community_name=true;
        }
    }
    var monthly_turnover=true;//月均营业额                           1
    function check_monthly_turnover(file){
        if(file.value==''){
            file.style.borderColor="#ddd";
            monthly_turnover=true;
        }else{
            file.style.borderColor="#ddd";
            monthly_turnover=true;
        }
    }

    var account_type=false;//账户类型
    var bank=false;
    var alipay=false;
    function check_account_type(file){
        if(file.value==''){
            file.style.borderColor="#A94442";
            account_type=false;
        }else{
            file.style.borderColor="#ddd";
            account_type=true;
        }
    }
    var alipay_name=false;//支付宝账号                                   1
    function check_alipay_name(file){
        if(file.value==''){
            file.style.borderColor="#A94442";
            alipay_name=false;
        }else{
            file.style.borderColor="#ddd";
            alipay_name=true;
        }
    }
    var bank_id=false;//银行ID                                           1
    function check_bank_id(file){
        if(file.value==''){
            file.style.borderColor="#A94442";
            bank_id=false;
        }else{
            file.style.borderColor="#ddd";
            bank_id=true;
        }
    }
    var bank_province=false;//银行所在省份
    function check_bank_province(file){
        if(file.value==''){
            file.style.borderColor="#A94442";
            bank_province=false;
        }else{
            file.style.borderColor="#ddd";
            bank_province=true;
        }
    }
    var bank_city=false;//银行所在城市
    function check_bank_city(file){
        if(file.value==''){
            file.style.borderColor="#A94442";
            bank_city=false;
        }else{
            file.style.borderColor="#ddd";
            bank_city=true;
        }
    }
    var bank_branch=false;//开户支行名称
    function check_bank_branch(file){
        if(file.value==''){
            file.style.borderColor="#A94442";
            bank_branch=false;
        }else{
            file.style.borderColor="#ddd";
            bank_branch=true;
        }
    }
    var bank_number=false;//银行卡号                                     1
    function check_bank_number(file){
        if(file.value==''){
            file.style.borderColor="#A94442";
            bank_number=false;
        }else{
            file.style.borderColor="#ddd";
            bank_number=true;
        }
    }
    var bankcard_username=false;//开户名称
    function check_bankcard_username(file){
        if(file.value==''){
            file.style.borderColor="#A94442";
            bankcard_username=false;
        }else{
            file.style.borderColor="#ddd";
            bankcard_username=true;
        }
    }

    var settlement_cycle=false;//结算周期
    function check_settlement_cycle(file){
        if(file.value==''){
            file.style.borderColor="#A94442";
            settlement_cycle=false;
        }else{
            file.style.borderColor="#ddd";
            settlement_cycle=true;
        }
    }
    var servera=false;//固定服务费用
    function check_service_charge(file){
        if(file.value==''){
            file.style.borderColor="#A94442";
            service_charge=false;
        }else{
            file.style.borderColor="#ddd";
            service_charge=true;
        }
    }
    var serverb=false;//服务佣金用             2            1
    function check_fixed_service_charge(file){
        if(file.value==''){
            file.style.borderColor="#A94442";
            fixed_service_charge=false;
        }else{
            file.style.borderColor="#ddd";
            fixed_service_charge=true;
        }
    }
    var service_commission=false;//服务佣金                    2            1
    function check_service_commission(file){
        if(file.value==''){
            file.style.borderColor="#A94442";
            service_commission=false;
        }else{
            file.style.borderColor="#ddd";
            service_commission=true;
        }
    }
    var start_time=false;//开始时间
    function check_start_time(file){
        if(file.value==''){
            file.style.borderColor="#A94442";
            start_time=false;
        }else{
            file.style.borderColor="#ddd";
            start_time=true;
        }
    }
    var end_time=false;//结束时间
    function check_end_time(file){
        if(file.value==''){
            file.style.borderColor="#A94442";
            end_time=false;
        }else{
            file.style.borderColor="#ddd";
            end_time=true;
        }
    }
    var counterman=false;//业务员
    function check_counterman(file){
        if(file.value==''){
            file.style.borderColor="#A94442";
            counterman=false;
        }else{
            file.style.borderColor="#ddd";
            counterman=true;
        }
    }

    //转换城市
    function selectprovince(file) {
        if(file.value!=='0') {
            var city=document.getElementById("city");
            city.options.length=0;//清空select城市的options
            $.ajax(
                {
                    type: "POST",
                    url: '/shop/shopcontract/getcityajax',
                    data: {'province_id':file.value},
                    //data: {['bank':bank],['province',province],['city',city]},
                    asynic: false,
                    dataType: "json",
                    beforeSend: function () {
                    },
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
        //showbcbank();//省份选择结束立即执行ajax判断 支行是否存在
    }
    //转换支行
    function showbcbank() {
        var bank=document.getElementById("bank").value;
        var pro=document.getElementById("pro").value;
        var city=document.getElementById("city").value;
        if(bank!=='0' && city!=='0' && pro!=='0'){
            var bank_branch=document.getElementById("bank_branch");
            bank_branch.options.length=0;//清空select支行的options
            var msg=new Array();
            msg[0]=bank;
            msg[1]=city;
            $.ajax(
                {
                    type: "POST",
                    url: '/shop/shopcontract/getbcbankajax',
                    data: {'msg':msg},
                    //data: {['bank':bank],['province',province],['city',city]},
                    asynic: false,
                    dataType: "json",
                    beforeSend: function () {
                    },
                    success: function (result) {
                        if(result==0){
                            //alert("支行没东西");
                        }else{
                            //alert("支行有东西");
                            var abc = new Option();
                            abc.text = '请选择支行';
                            abc.value = '0';
                            bank_branch.options[0] = abc;
                            for(var x = 0;x < result.length ; x++){
                                //alert(result[x]['name']);
                                var p = new Option(result[x]['name']);
                                p.value = result[x]['id'];
                                bank_branch.options[x+1] = p;
                            }
                        }
                    }
                }
            );
        }else{
            //alert(bank);
            //alert(city);
        }
    }
    //是否同店面注册名称
    function changeDm(file){
        var mc1=document.getElementById("mc1");
        var dmmc=document.getElementById("mc2");
        if(file.value==0){
            store_registration_name = true;
            document.getElementById("mc2").value=mc1.value;
            //reg_Dm_shop_contract_name(dmmc);
        }else{
            store_registration_name = false;
            document.getElementById("mc2").value='';
        }
    }
    //验证 是否同店面 注册名称唯一性
//    function reg_Dm_shop_contract_name(file){
//        var shop_contract_name_msg=file.value;
//        alert(shop_contract_name_msg);
//        if(file.value==''){
//            document.getElementById("shop_contract_name_label").style.display="none";
//            document.getElementById("store_registration_name_label").style.display="none";
//        }else{
//            $.ajax(
//                {
//                    type: "GET",
//                    url: '/shop/shopcontract/getzcmcajax',
//                    data: {'shop_contract_name':shop_contract_name_msg},
//                    asynic: false,
//                    dataType: "json",
//                    beforeSend: function () {
//                    },
//                    success: function (result) {
//                        if(result==1){
//                            document.getElementById("shop_contract_name_label").style.display="none";
//                            document.getElementById("store_registration_name_label").style.display="none";
//                            shop_contract_name = true;
//                            store_registration_name=true;
//                        }else{
//                            document.getElementById("shop_contract_name_label").style.display="inline";
//                            document.getElementById("store_registration_name_label").style.display="inline";
//                            shop_contract_name = false;
//                            store_registration_name=false;
//                        }
//                    }
//                });
//        }
//    }
    //是否同店面联系人姓名和电话
    function changeLxr(file){
        var lxr1=document.getElementById("lxr1");
        var lxdianhua1=document.getElementById("lxdianhua1");
        if(file.value==0){
            if(contacts){
                common_contacts_name = true;
            }else{
                common_contacts_name = false;
            }
            if(phonea){
                phoneb = true;
            }else{
                phoneb = false;
            }
            document.getElementById("lxr2").value=lxr1.value;
            document.getElementById("lxdianhua2").value=lxdianhua1.value;
        }else{
            common_contacts_name = false;
            phoneb = false;
            document.getElementById("lxr2").value='';
            document.getElementById("lxdianhua2").value='';
        }
    }
    //公司性质其他信息的显示
    function controlothersmsg(file){
        var x=document.getElementById('checkbox5');
        if(file.checked){
            x.style.display="inline";
        }else{
            x.value="";
            x.style.display="none";
        }
    }
    //公司性质其他信息的隐藏
    function noneothersmsg(file){
        var x=document.getElementById('checkbox5');
        if(file.checked){
            x.value="";
            x.style.display="none";
        }else{
            x.style.display="inline";
        }
    }
    //服务佣金/固定佣金显示和隐藏
    function changefy(file){
        var fwyj=document.getElementById("fwyj");
        var gdfwf=document.getElementById("gdfwf");
        if(file.value==0){
            gdfwf.style.display="block";
            fwyj.style.display="none";
            document.getElementById("service_commission").value='';
        }else{
            gdfwf.style.display="none";
            fwyj.style.display="block";
            document.getElementById("fixed_service_charge").value='';
        }
    }

    //chenge账号显示和隐藏
    function changezh(file){
        //alert(file.value);
        var zfbtable=document.getElementById("zfbtable");
        var yhtable=document.getElementById("yhtable");
        if(file.value==1){
            yhtable.style.display="none";
            zfbtable.style.display="block";
            document.getElementById("yhkh").value='';
            document.getElementById("khmc").value='';
            document.getElementById("bank_branch").value='';
            document.getElementById("bank").value=0;
            document.getElementById("pro").value=0;
            document.getElementById("city").value=0;
        }
        if(file.value==0){
            yhtable.style.display="block";
            zfbtable.style.display="none";
            document.getElementById("zfb").value='';
        }
    }

    //验证
    var emailreg = new RegExp("^([0-9a-zA-Z]([-.\w]*[0-9a-zA-Z])*@(([0-9a-zA-Z])+([-\w]*[0-9a-zA-Z])*\.)+[a-zA-Z]{2,9})$");
    var mobilereg = /^1\d{10}$/;
    var phonereg = /^\d{3}-\d{8}|\d{4}-\d{7}$/;
    var QQreg = /^[1-9][0-9]{4,10}$/;
    var serverreg = /^\d+(\.\d+)?$/;
    var banknumreg=/^[0-9]{0,30}$/;
    var document_numberreg=/^[1-9]\d{16}(\d|x|X)$/;
    var countermanidreg=/^\d+$/;
    var is_file_image = false;//图片
    var file_image_reg = /^.+.[jpg|gif|bmp|bnp|png]$/i;
    var htnumberreg = /^BJ[0-9]{6}$/;
    //简单验证 二代身份证 格式
    function regdocument_number(file){
        if(!document_numberreg.test(file.value)){
            file.style.borderColor="#A94442";
            document_number=false;
        }else{
            file.style.borderColor="#ddd";
            document_number=true;
        }
        if(file.value==''){file.style.borderColor="#ddd";}
    }
    //验证 email 格式
    function regemail(file){
        if(!emailreg.test(file.value)){
            file.style.borderColor="#A94442";
            email=false;
        }else{
            file.style.borderColor="#ddd";
            email=true;
        }
        if(file.value==''){file.style.borderColor="#ddd";email=true;}
    }
    //验证 基本信息 联系电话 格式
    function regphonea(file){
        if(file.value.length==11) {
            if (!mobilereg.test(file.value)) {
                file.style.borderColor = "#A94442";
                phonea = false;
            } else {
                file.style.borderColor = "#ddd";
                phonea = true;
            }
        }else{
            file.style.borderColor = "#A94442";
            phonea = false;
        }
        if(file.value==''){file.style.borderColor="#ddd";}
    }
    //验证 服务信息 联系电话 格式
    function regphoneb(file){
        if(file.value.length==11) {
            if (!mobilereg.test(file.value)) {
                file.style.borderColor = "#A94442";
                phoneb = false;
            } else {
                file.style.borderColor = "#ddd";
                phoneb = true;
            }
        }else{
            file.style.borderColor = "#A94442";
            phoneb = false;
        }
        if(file.value==''){file.style.borderColor="#ddd";}
    }
    //验证 面积 格式
    function regarea(file){
        //alert(emailreg.test(file.value));
        if(!serverreg.test(file.value)){
            file.style.borderColor="#A94442";
            area=false;
        }else{
            file.style.borderColor="#ddd";
            area=true;
        }
        if(file.value==''){file.style.borderColor="#ddd";area=true;}
    }
    //验证 固定服务费用 格式
    function regservicea(file){
        //alert(emailreg.test(file.value));
        if(!serverreg.test(file.value)){
            file.style.borderColor="#A94442";
            servera=false;
        }else{
            file.style.borderColor="#ddd";
            servera=true;
        }
        if(file.value==''){file.style.borderColor="#ddd";}
    }
    //验证 服务佣金 格式
    function regserviceb(file){
        //alert(emailreg.test(file.value));
        if(!serverreg.test(file.value)){
            file.style.borderColor="#A94442";
            serverb=false;
        }else{
            file.style.borderColor="#ddd";
            serverb=true;
        }
        if(file.value==''){file.style.borderColor="#ddd";}
    }
    //验证 银行卡号 格式
    function changebanknum(file){
        //file.value =file.value.replace(/\s/g,'').replace(/(\d{4})(?=\d)/g,"$1 ");//输入银行卡号，4位自动加空格
        var banknum = file.value;
        while(banknum.indexOf(" ")!=-1){
            banknum=banknum.replace(" ","");
        }
        if(!banknumreg.test(banknum)){
            bank_number=false;
            file.style.borderColor="#A94442";
        }else{
            bank_number=true;
            file.style.borderColor="#ddd";
            file.value =file.value.replace(/\s/g,'').replace(/(\d{4})(?=\d)/g,"$1 ");//输入银行卡号，4位自动加空格
        }
    }
    //验证 银行卡号 长度
    function checknumlen(file){
        var banknum = file.value;
        while(banknum.indexOf(" ")!=-1){
            banknum=banknum.replace(" ","");
        }
        if((banknum.length == 19) || (banknum.length == 16) || (banknum.length == 21)){
            bank_number=true;
            file.style.borderColor="#ddd";
        }else{
            bank_number=false;
            file.style.borderColor="#A94442";
        }
    }
    //验证 业务员 格式 并查找 业务员姓名 select_countermanid
    function select_countermanid(file){
        if(!countermanidreg.test(file.value)){
            file.style.borderColor="#A94442";
            counterman = false;
            //countermanmsg.innerText="<i><u>设置或获取位于对象起始和结束标签内的文本.</u></i>";
            document.getElementById('countermanmsg').innerHTML="业务员ID错误";
        }else{
            //根据业务员ID查找姓名
            $.ajax(
                {
                    type: "POST",
                    url: '/shop/shopcontract/getcountermanidajax',
                    data: {'msg':file.value},
                    //data: {['bank':bank],['province',province],['city',city]},
                    asynic: false,
                    dataType: "json",
                    beforeSend: function () {
                    },
                    success: function (result) {
                        if(result==0){
                            //alert("业务员没东西");
                            counterman = false;
                            document.getElementById('countermanmsg').innerHTML="未找到ID为:";
                            document.getElementById('countermanmsg').innerHTML+=file.value;
                            document.getElementById('countermanmsg').innerHTML+="的业务员！";
                        }else{
                            //alert("业务员有东西");
                            //alert(result['name']);
                            counterman = true;
                            document.getElementById('countermanmsg').innerHTML=result['name'];
                            file.style.borderColor="#ddd";
                        }
                    }
                }
            );

        }
    }
    //验证合同号格式
    var htnumber=false;
    function reghtnumbermsg(file){
        if(!htnumberreg.test(file.value)){
            file.style.borderColor="#A94442";
            htnumber=false;
        }else{
            file.style.borderColor="#ddd";
            htnumber=true;
            reghtnumber(file);
        }
        if(file.value==''){file.style.borderColor="#ddd";}
    }
    //验证合同号唯一性
    function reghtnumber(file){
        var htnumbermsg=file.value;
        $.ajax(
            {
                type: "GET",
                url: '/shop/shopcontract/gethtnumberajax',
                data: {'htnumber':htnumbermsg},
                asynic: false,
                dataType: "json",
                beforeSend: function () {
                },
                success: function (result) {
                    if(result==1){
                        document.getElementById("htnumberlabel").style.display="none";
                        htnumber=true;
                    }else{
                        document.getElementById("htnumberlabel").style.display="inline";
                        htnumber=false;
                    }
                }
            }
        );
        if(file.value==''){document.getElementById("htnumberlabel").style.display="none";}
    }
    //判断 input文本框里业务员Id的 状态
    function change_counterman(file){
        if(file.value.length==0){
            counterman = false;
            document.getElementById('countermanmsg').innerHTML='';
            file.style.borderColor="#ddd";
        }
    }
    //检查开始与结束的时间
    function checktimes(){
        var start_timemsg=document.getElementById("start_time");
        var end_timemsg=document.getElementById("end_time");
        if(start_timemsg.value!='' && end_timemsg.value!=''){
            if(start_timemsg.value>end_timemsg.value){
                start_timemsg.style.borderColor="#A94442";
                end_timemsg.style.borderColor="#A94442";
                start_time=false;
                end_time=false;
                alert("开始时间不能大于结束时间！");
            }else{
                start_time=true;
                end_time=true;
                start_timemsg.style.borderColor="#ccc";
                end_timemsg.style.borderColor="#ccc";
            }
        }
    }
    //onchange
    function notnull(file){
        if(file.value.length){
            file.style.borderColor="#ddd";
        }else{
            file.style.borderColor="#A94442";
        }
    }
    //提交动作
    function click_sub() {
        if (!htnumber) {
            alert("合同号不可用，无法提交！");
        } else {
        if (!shop_contract_name) {
            alert("注册名称不可用，无法提交！");
        } else {
            if (!registered_id) {
                alert("注册登记号不可用，无法提交！");
            } else {
                if (!registered_address) {
                    alert("注册地址不可用，无法提交！");
                } else {
                    if (!registered_capital) {
                        alert("注册资本不可用，无法提交！");
                    } else {
                        if (!legal_representative) {
                            alert("法定代表人不可用，无法提交！");
                        } else {
                            if (!email) {
                                alert("邮箱不可用，无法提交！");
                            } else {
                                if (!document_number) {
                                    alert("证件号不可用，无法提交！");
                                } else {
                                    if (!contacts) {
                                        alert("联系人不可用，无法提交！");
                                    } else {
                                        if (!phonea) {
                                            alert("联系人电话不可用，无法提交！");
                                        } else {
                                            if (!(document.getElementById("checkbox1").checked || document.getElementById("checkbox2").checked || document.getElementById("checkbox3").checked || document.getElementById("checkbox4").checked || document.getElementById("checkbox5").value.length > 0)) {
                                                alert("公司性质为空，无法提交！");
                                            } else {
                                                if (!store_registration_name) {
                                                    alert("店面注册名称为空，无法提交！");
                                                } else {
                                                    if (!(document.getElementById("checkbox11").checked || document.getElementById("checkbox22").checked || document.getElementById("checkbox33").checked || document.getElementById("checkbox44").checked)) {
                                                        alert("经营范围为空，无法提交！");
                                                    } else {
                                                        if (!business_address) {
                                                            alert("经营地址为空，无法提交！");
                                                        } else {
                                                            if (!common_contacts_name) {
                                                                alert("日常联系人姓名为空，无法提交！");
                                                            } else {
                                                                if (!phoneb) {
                                                                    alert("日常联系人电话格式错误，无法提交！");
                                                                } else {
                                                                    if (!common_contacts_job) {
                                                                        alert("日常联系人工作格式错误，无法提交！");
                                                                    } else {
                                                                        if (!area) {
                                                                            alert("面积格式错误，无法提交！");
                                                                        } else {
                                                                            if (!community_name) {
                                                                                alert("所在社区名称为空，无法提交！");
                                                                            } else {
                                                                                if (!monthly_turnover) {
                                                                                    alert("月均营业额为空，无法提交！");
                                                                                } else {
                                                                                    if (document.getElementById("at1").checked) {
                                                                                        if (document.getElementById("bank").value == '0') {
                                                                                            alert("开户银行为空，无法提交！");
                                                                                        } else {
                                                                                            if (document.getElementById("pro").value == '0') {
                                                                                                alert("所在省份为空，无法提交！");
                                                                                            } else {
                                                                                                if (document.getElementById("city").value == '0') {
                                                                                                    alert("所在城市为空，无法提交！");
                                                                                                } else {
                                                                                                    if (document.getElementById("bank_branch").value.length == '0') {
                                                                                                        alert("开户支行为空，无法提交！");
                                                                                                    } else {
                                                                                                        if (!bank_number) {
                                                                                                            alert("银行卡号为空，无法提交！");
                                                                                                        } else {
                                                                                                            if (!bankcard_username) {
                                                                                                                alert("开户名称为空，无法提交！");
                                                                                                            } else {
                                                                                                                bank = true;
                                                                                                            }
                                                                                                        }
                                                                                                    }
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    } else {
                                                                                        if (!alipay_name) {
                                                                                            alert("支付宝为空，无法提交！");
                                                                                        } else {
                                                                                            alipay = true;
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
        if(bank || alipay){
            if(document.getElementById("gd").checked){
                if(!servera){
                    serverb=false;
                    alert("固定服务费用错误,无法提交！");
                }else{
                    servera=true;
                }
            }
            if(document.getElementById("yj").checked){
                if(!serverb){
                    servera=false;
                    alert("服务佣金错误,无法提交！");
                }else{
                    serverb=true;
                }
            }
            if(servera || serverb){
                if(!start_time){
                    alert("开始时间不正确！");
                }else{
                    if(!end_time){
                        alert("结束时间不正确！");
                    }else{
                        if(!counterman){
                            alert("请选择业务员！");
                        }else{
                            if(!is_file_image){
                                alert("图片格式不正确！");
                            }else{
                                document.getElementById("login-form").submit().click;
                            }
                        }
                    }
                }
            }
        }

    }
    //同店面注册名称
    function changealike(){
        if(document.getElementsByName("store_registration")[0].checked){
            var mc1=document.getElementById("mc1");
            if(mc1.value == ''){
                store_registration_name = false;
                document.getElementById("mc2").value='';
                document.getElementById("mc1").style.borderColor="#A94442";
                document.getElementById("mc2").style.borderColor="#A94442";
            }else{
                store_registration_name = true;
                document.getElementById("mc2").value=mc1.value;
                document.getElementById("mc1").style.borderColor="#ddd";
                document.getElementById("mc2").style.borderColor="#ddd";
            }
        }else{

        }
    }
    //同店面联系人 电话
    function changeblike(){
        if(document.getElementsByName("common_contacts")[0].checked){
            //联系人
            var lxr1=document.getElementById("lxr1");
            if(lxr1.value == ''){
                contacts = false;
                common_contacts_name = false;
                document.getElementById("lxr2").value='';
                document.getElementById("lxr2").style.borderColor="#A94442";
                document.getElementById("lxr1").style.borderColor="#A94442";
            }else{
                contacts = true;
                common_contacts_name = true;
                document.getElementById("lxr2").value=lxr1.value;
                document.getElementById("lxr2").style.borderColor="#ddd";
                document.getElementById("lxr1").style.borderColor="#ddd";
            }

            //联系电话
            var lxdianhua1=document.getElementById("lxdianhua1");
            if(lxdianhua1.value == ''){
                phonea = false;
                phoneb = false;
                document.getElementById("lxdianhua2").value='';
                document.getElementById("lxdianhua2").style.borderColor="#A94442";
                document.getElementById("lxdianhua1").style.borderColor="#A94442";
            }else{
                phonea = true;
                phoneb = true;
                document.getElementById("lxdianhua2").value=lxdianhua1.value;
                document.getElementById("lxdianhua2").style.borderColor="#ddd";
                document.getElementById("lxdianhua1").style.borderColor="#ddd";
            }
        }
    }
    function c_sub(){
        alert(document.getElementById("gd").value);
        alert(document.getElementById("yj").value);
    }
</script>
<style>
    .mark{ color: red;}
    input{ padding-bottom: 7px; padding-top: 7px; padding-left: 10px;border-radius:5px; border: 1px solid #ddd;}
    table{ width: 900px; height: auto; margin-left: 50px; margin-bottom: 20px;}
    tr{ height: 40px; padding-bottom: 5px; padding-top: 5px;}
    th{ font-size: 16px; colspan: 3;}
    td{ text-align: left; }
</style>

<?php
$form = ActiveForm::begin([
    'id' => "login-form",
    'action' => "showmsg",
    'method'=>'post',
    'layout' => 'horizontal',
    'enableAjaxValidation' => false,
    'options' => ['enctype' => 'multipart/form-data'],
]);
?>
    <div style=" width: 1000px; height: auto; ">
        <h4><a href="/" >首页</a>&gt;<a href="/shop/shopcontract/index">商家合同列表</a><span>&gt;</span>商家合同信息添加</h4>
        <table style="border-bottom: 1px solid #D5692B;">
            <tr>
                <th>合同号</th>
                <td><input type="text" size="30" maxlength="8" name="htnumber" placeholder="合同号格式为：BJ+6位数字" onblur="reghtnumbermsg(this)"/></td>
                <td colspan="2"><label id="htnumberlabel" style=" display: none; color: red;">合同号已存在，请重新填写！</label></td>
            </tr>
            <tr>
                <th colspan="4">基本信息</th>
            </tr>
            <tr>
                <td ><label class="mark">*</label>注册名称</td>
                <td ><input type="text" size="30" maxlength="30" id="mc1" name="shop_contract_name" onchange="changealike(this);" onkeyup="notnull(this);" onfocus="this.value.length?this.style.borderColor='#ddd';" onblur="check_shop_contract_name(this)" /> </td>
                <td colspan="2"><label id="shop_contract_name_label" style=" display: none; color: red;">注册名称已存在，请重新填写！</label></td>
            </tr>
            <tr>
                <td ><label class="mark">*</label>注册地址</td>
                <td ><input type="text" size="30" maxlength="50" name="registered_address" onkeyup="notnull(this)" onfocus="this.value.length?this.style.borderColor='#ddd';" onblur="check_registered_address(this)" /> </td>
            </tr>
            <tr>
                <td ><label class="mark">*</label>注册登记号</td>
                <td ><input type="text" size="30"  name="registered_id" onkeyup="notnull(this)" onfocus="this.value.length?this.style.borderColor='#ddd';" onblur="check_registered_id(this)" /> </td>
                <td >&nbsp;&nbsp;&nbsp;注册资本</td>
                <td ><input type="text" size="30" id="zhuceziben" name="registered_capital" onfocus="this.value.length?this.style.borderColor='#ddd';" onblur="check_registered_capital(this)"/> </td>
            </tr>
            <tr>
                <td ><label class="mark">*</label>法定代表人</td>
                <td ><input type="text" size="30" maxlength="30" name="legal_representative" onkeyup="notnull(this)" onfocus="this.value.length?this.style.borderColor='#ddd';" onblur="check_legal_representative(this)"/> </td>
                <td >&nbsp;&nbsp;&nbsp;邮箱</td>
                <td ><input type="text" size="30" id="youxiang" name="email" onkeyup="notnull(this)" onfocus="this.value.length?this.style.borderColor='#ddd';" onblur="regemail(this)" /> </td>
            </tr>
            <tr>
                <td ><label class="mark">*</label>证件类型</td>
                <td >
                    <select name="document_type">
                        <?php
                        foreach ($document_type_data as $k => $v) {
                            ?>
                            <option value="<?= $k ?>"><?= $v ?></option>
                            <?php
                        }
                        ?>
                    <select>
                </td>
                <td ><label class="mark">*</label>证件号</td>
                <td ><input type="text" size="30" maxlength="30" name="document_number" onkeyup="notnull(this)" onfocus="this.value.length?this.style.borderColor='#ddd';" onblur="regdocument_number(this)"/> </td>
            </tr>
            <tr>
                <td ><label class="mark">*</label>联系人</td>
                <td ><input type="text" size="30" maxlength="30" id="lxr1" name="contacts" onchange="changeblike(this);" onkeyup="notnull(this);" onfocus="this.value.length?this.style.borderColor='#ddd';" onblur="check_contacts(this)"/> </td>
                <td ><label class="mark">*</label>联系电话</td>
                <td ><input type="text" size="30" id="lxdianhua1" name="contacts_umber" placeholder="手机号" onchange="changeblike(this);" onkeyup="notnull(this);" onfocus="this.value.length?this.style.borderColor='#ddd';" onblur="regphonea(this)"/> </td>
            </tr>
            <tr>
                <td ><label class="mark">*</label>公司性质</td>
                <td colspan="3" style="text-align: left;">
                    <input type="radio" id="checkbox1" name="company_nature[]" onclick="noneothersmsg(this)"  value="0" checked>个体商户&nbsp;&nbsp;
                    <input type="radio" id="checkbox2" name="company_nature[]" onclick="noneothersmsg(this)"  value="1">民办非企业&nbsp;&nbsp;
                    <input type="radio" id="checkbox3" name="company_nature[]" onclick="noneothersmsg(this)"  value="2">股份制&nbsp;&nbsp;
                    <input type="radio" id="checkbox4" name="company_nature[]" onclick="noneothersmsg(this)"  value="3">有限责任制&nbsp;&nbsp;
                    <input type="radio" name="company_nature[]"  value="4" onclick="controlothersmsg(this)">其他
                    <input type="text" id="checkbox5" name="company_nature_other" maxlength="30" style="display: none;">
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <th colspan="4">经营信息</th>
            </tr>
            <tr>
                <td colspan="3" style="text-align: left;">
                    <label class="mark">*</label>同店面注册名称&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="store_registration" value="0" onclick="changeDm(this)">是&nbsp;&nbsp;
                    <input type="radio" name="store_registration" checked value="1" onclick="changeDm(this)">否&nbsp;&nbsp;
                </td>
                <td><label class="mark">*</label>店面注册名称</td>
                <td><input type="text" size="30" id="mc2" maxlength="30" name="store_registration_name" onchange="changealike(this);" onkeyup="notnull(this);" onfocus="this.value.length?this.style.borderColor='#ddd';" onblur="check_store_registration_name(this)"/></td>
<!--                <td colspan="2"><label id="store_registration_name_label" style=" display: none; color: red;">注册名称已存在，请重新填写！</label></td>-->
            </tr>
        </table>
        <table style="border-bottom: 1px solid #D5692B;">
            <tr>
                <td><label class="mark">*</label>经营范围</td>
                <td style="text-align: left;">
                    <?php
                    foreach ($business_scope_data as $k => $v) {
                        ?>
                        <input type="checkbox" id="checkbox<?php echo $k*11 ?>" name="business_scope[]" <?php if($k==1){echo 'checked';} ?>  value="<?php echo $k ?>"><?php echo $v ?>&nbsp;&nbsp;
                    <?php
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td><label class="mark">*</label>经营地址</td>
                <td style="text-align: left;">
                    <input type="text" size="90" name="business_address" onkeyup="notnull(this)" onfocus="this.value.length?this.style.borderColor='#ddd';" onblur="check_business_address(this)"/>
                </td>
            </tr>
        </table>
        <table style="border-bottom: 1px solid #D5692B;">
            <tr>
                <th colspan="4">服务信息</th>
            </tr>
            <tr>
                <td style="text-align: left;"><label class="mark">*</label>同店面联系人&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td>
                    <input type="radio" name="common_contacts"   value="0" onclick="changeLxr(this)">是&nbsp;&nbsp;
                    <input type="radio" name="common_contacts" checked value="1" onclick="changeLxr(this)">否&nbsp;&nbsp;
                </td>
                <td><label class="mark">*</label>日常联系人姓名</td>
                <td><input type="text" id="lxr2" maxlength="30" name="common_contacts_name" size="20" onchange="changeblike(this);" onkeyup="notnull(this)" onfocus="this.value.length?this.style.borderColor='#ddd';" onblur="check_common_contacts_name(this)"/></td>
            </tr>
            <tr>
                <td>&nbsp;&nbsp;&nbsp;职务</td>
                <td>
                    <input type="text" size="30" id="zhiwu" maxlength="20" name="common_contacts_job"  onfocus="this.value.length?this.style.borderColor='#ddd';" onblur="check_common_contacts_job(this)"/>
                </td>
                <td><label class="mark">*</label>手机号</td>
                <td>
                    <input type="text" size="30" id="lxdianhua2" name="common_contacts_phone" placeholder="手机号" onchange="changeblike(this);" onkeyup="notnull(this);" onfocus="this.value.length?this.style.borderColor='#ddd';" onblur="regphoneb(this)" />
                </td>
            </tr>
            <tr>
                <td>&nbsp;&nbsp;&nbsp;月均营业额</td>
                <td><input type="text" size="30" id="yuejunyingyee" maxlength="20" name="monthly_turnover" onfocus="this.value.length?this.style.borderColor='#ddd';" onblur="check_monthly_turnover(this)"/></td>
                <td><label class="mark">*</label>营业时间</td>
                <td style="text-align: left;">
                    上午
                    <select name="business_hours_start">
                        <?php for($x=0;$x<=12;$x++){ ?>
                            <option value =<?= $x ?>><?= $x ?>：00</option>
                        <?php } ?>
                    </select>
                    &nbsp;&nbsp;至&nbsp;&nbsp;下午
                    <select name="business_hours_end">
                        <?php for($x=12;$x<=24;$x++){ ?>
                            <option value =<?= $x ?>><?= $x ?>：00</option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>&nbsp;&nbsp;&nbsp;面积</td>
                <td>
                    <input type="text" size="20" id="mianji" name="area" onkeyup="notnull(this)" onfocus="this.value.length?this.style.borderColor='#ddd';" onblur="regarea(this)"/>M<sup>2</sup>
                </td>
                <td><label class="mark">*</label>所在社区名称</td>
                <td style="text-align: left;">
                    <input type="text" size="60"  maxlength="50" name="community_name" onkeyup="notnull(this)" onfocus="this.value.length?this.style.borderColor='#ddd';" onblur="check_community_name(this)"/>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <th colspan="4">清算信息</th>
            </tr>
            <tr>
                <td colspan="2"><label class="mark">*</label>帐户类型</td>
                <td id="zhlx" colspan="2">
                    <input type="radio" id="at1" name="account_type" value="0" checked onclick="changezh(this)" />银行账号&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<!--                    <input type="radio" id="at2" name="account_type" value="1" onclick="changezh(this)" />支付宝账号-->
                </td>
            </tr>
            <tr style=" height: 0px;">
                <table id="zfbtable" style="display: none; width: 900px;">
                    <tr>
                        <td><label class="mark">*</label>支付宝账号&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td>
                            <input type="text" size="40" id="zfb"  maxlength="30" name="alipay_name" onkeyup="notnull(this)" onfocus="this.value.length?this.style.borderColor='#ddd';" onblur="check_alipay_name(this)"/>
                        </td>
                    </tr>
                </table>
                <table id="yhtable" style="border-bottom: 1px solid #D5692B; width: 900px;">
                    <tr><td colspan="4" style=" width: 900px;">&nbsp;&nbsp;银行信息</td></tr>
                    <tr style="width: 900px;">
                        <td style="width=180px;" colspan="1"><label class="mark">*</label>开户银行</td>
                        <td style="text-align: left; width=270px;">
                            <select id="bank"  name="bank_id" onChange="showbcbank()">
                            <option value="0">请选择银行</option>
                                <?php foreach($Bank_result as $k => $v){ ?>
                                    <option value="<?= $v['id']; ?>"><?= $v['name']; ?></option>
                                <?php } ?>
                            </select>
                        </td>
                        <td style="width=180px;"><label class="mark">*</label>开户支行</td>
                        <td>
                            <!--                            <select id="bank_branch" name="bank_branch">-->
                            <!--                                <option value="0">请选择开户支行</option>-->
                            <!--                            </select>-->
                            <input type="text" id="bank_branch"  name="bank_branch" maxlength="40" size="40" onkeyup="notnull(this)" onfocus="this.value.length?this.style.borderColor='#ddd';" onblur="check_bank_branch(this)"   />
                        </td>
                    </tr>
                    <tr>
                        <td><label class="mark">*</label>所在省份</td>
                        <td>
                            <select id="pro"  name="bank_province" onChange="selectprovince(this)">
                                <option value="0">请选择省份</option>
                                <?php foreach($Province_result as $k => $v){ ?>
                                    <option value="<?= $v['id']; ?>"><?= $v['name']; ?></option>
                                <?php } ?>
                            </select>
                        </td>
                        <td><label class="mark">*</label>所在城市</td>
                        <td>
                            <select id="city"  name="bank_city" onChange="showbcbank()">
                                <option value="0">请选择所在城市</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label class="mark">*</label>银行卡号</td>
                        <td>
                            <input type="text" id="yhkh" size="40" name="bank_number" maxlength="30" placeholder="目前只支持16位、19位、21位银行卡号码" onfocus="this.value.length?this.style.borderColor='#ddd';" onkeyup="changebanknum(this)" onblur="checknumlen(this)"/>
                        </td>
                        <td><label class="mark">*</label>开户名称</td>
                        <td>
                            <input type="text" id="khmc" maxlength="30" size="40" name="bankcard_username" onkeyup="notnull(this)" onfocus="this.value.length?this.style.borderColor='#ddd';" onblur="check_bankcard_username(this)"/>
                        </td>
                    </tr>
                </table>
            </tr>
        </table>
        <table style="border-bottom: 1px solid #D5692B;">
            <tr>
                <th colspan="4">结算信息</th>
            </tr>
            <tr>
                <td  style="width: 50px;"><label class="mark">*</label>服务费用方式</td>
                <td  style="width: 200px;">
                    <input type="radio" id="gd" name="service_charge" value="0" checked onclick="changefy(this)" />固定服务费
                </td>
                <td colspan="2">
                    <input type="radio" id="yj" name="service_charge" value="1" onclick="changefy(this)"/>服务佣金
                </td>
            </tr>
            <tr>
                <td style="padding-left: 100px;"></td>
                <td id="gdfwf">
                    <input type="text" id="fixed_service_charge" maxlength="15" name="fixed_service_charge" onkeyup="notnull(this)" onfocus="this.value.length?this.style.borderColor='#ddd';" onblur="regservicea(this)" />
                </td>
                <td id="fwyj" colspan="2" style="display: none;">
                    <input type="text" id="service_commission" maxlength="15" name="service_commission" onkeyup="notnull(this)" onfocus="this.value.length?this.style.borderColor='#ddd';" onblur="regserviceb(this)"/>%
                </td>
            </tr>
            <tr>
                <td><label class="mark">*</label>结算周期</td>
                <td>
                    <select id="settlement_cycle" name="settlement_cycle">
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
        <table>
            <tr>
                <th colspan="4">其他信息</th>
            </tr>
            <tr>
                <td><label class="mark">*</label>合同起止时间</td>
                <td>
                    <label for="start_time" style="float: left;  margin-top: 8px; width: 100px;" >开始时间：</label>
                    <input style="float: left; width: 120px;" id="start_time" type="text"  name="start_time" onkeyup="notnull(this)" onblur="check_start_time(this)" onchange="checktimes()" onFocus="WdatePicker({isShowClear:true,readOnly:false})" value="<?php if(isset($start_time)){echo $start_time; };?>" class="form-control">
                    <label for="end_time" style="float: left; margin-left: 20px; margin-top: 8px; width: 100px;" >结束时间：</label>
                    <input style="float: left; width: 120px;" id="end_time" type="text" name="end_time" onkeyup="notnull(this)" onblur="check_end_time(this)" onchange="checktimes()" onFocus="WdatePicker({isShowClear:true,readOnly:false})" value="<?php if(isset($end_time)){echo $end_time; };?>" class="form-control">
                </td>
            </tr>
            <tr>
                <td><label class="mark">*</label>业务员ID</td>
                <td>
                    <input type="text" id="counterman" name="counterman" maxlength="5" onkeyup="select_countermanid(this)" onfocus="change_counterman(this)" />
                    <label style="padding-left: 10px;">业务员姓名：</label>
                    <label id="countermanmsg" style="color:red;"></label>
                </td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        </table>
        <table style="width;1000px; height: auto;">
            <tr style="width: 100%;">
                <th colspan="4">合同图片</th>
            </tr>
            <tr style="width: 100%; height: 210px;">
                <td colspan="1">
                    <input id="shopcontract-image" type="file" name="file_name" onchange="previewImage(this)" />
<!--                    <img id="user_photo" class="img-circle" style="width: 100px;height: 100px;" src="/images/05_mid.jpg" />-->
                </td>
                <td colspan="3">
                    <div style="width: 200px; height: 200px;" id="preview">
                        <img style="width: 200px;height: 200px;" src="" />
                    </div>
                </td>
            </tr>
            <tr style="width: 100%;">
                <th colspan="4" class="business_licence" onmouseover="this.style.cursor='pointer'">营业执照图片</th>
            </tr>
            <tr class="business_licence_image" style=" display: none; width: 100%; height: 210px;">
                <td colspan="1">
                    <input id="shopcontract-business_licence_image" type="file" name="business_licence_image" onchange="previewImage1(this)" />
                </td>
                <td colspan="3">
                    <div style="width: 200px; height: 200px;" id="preview1">
                        <img style="width: 200px;height: 200px;" src="" />
                    </div>
                </td>
            </tr>
            <tr>
                <th colspan="4" class="bank_number" onmouseover="this.style.cursor='pointer'">银行卡图片</th>
            </tr>
            <tr class="bank_number_image" style=" display: none; width: 100%; height: 210px;">
                <td colspan="1">
                    <input id="shopcontract-bank_number_image" type="file" name="bank_number_image" onchange="previewImage2(this)" />
                </td>
                <td colspan="3">
                    <div style="width: 200px; height: 200px;" id="preview2">
                        <img style="width: 200px;height: 200px;" src="" />
                    </div>
                </td>
            </tr>

            <tr>
                <th colspan="4" class="IDcard" onmouseover="this.style.cursor='pointer'">身份证图片</th>
            </tr>
            <tr class="IDcard_image" style=" display: none; width: 100%; height: 210px;">
                <td colspan="1">
                    <input id="shopcontract-IDcard_image" type="file" name="IDcard_image" onchange="previewImage3(this)" />
                </td>
                <td colspan="3">
                    <div style="width: 200px; height: 200px;" id="preview3">
                        <img style="width: 200px;height: 200px;" src="" />
                    </div>
                </td>
            </tr>
            <tr>
                <th colspan="4" class="remark" onmouseover="this.style.cursor='pointer'">备注</th>
            </tr>
            <tr class="remark_image" style=" display: none; width: 100%; height: 210px;">
                <td colspan="4">
                    <textarea maxlength="255" rows="5" cols="100" name="remark"></textarea>
                </td>
            </tr>
        </table>
    </div>
<div class="form-actions">
    <a class="btn btn-primary" onclick="click_sub()">添加</a>
    <?= Html::resetButton('重置', ['class' => 'btn btn-primary']) ?>
    <a class="btn cancelBtn" href="javascript:history.go(-1);">取消</a>
</div>
<table>
    <tr>
        <th><a href="http://qyxy.baic.gov.cn" target="_blank">商家信息真实性查询</a><label style="margin-left: 20px;">网址：http://qyxy.baic.gov.cn</label></th>
    </tr>
</table>
<script type="text/javascript">
    //图片上传预览    IE是用了滤镜。
    function previewImage(file)
    {
        if(!file_image_reg.test(file.value)){
            alert("图片格式不正确！");
            is_file_image = false;
        }else{
            alert("图片可用！");
            is_file_image = true;
        }
        var MAXWIDTH  = 0;
        var MAXHEIGHT = 0;
        var div = document.getElementById('preview');
        if(div.style.width){
            var MAXWIDTH  = div.offsetWidth;
            var MAXHEIGHT = div.offsetHeight;
        }

        if (file.files && file.files[0])
        {
            div.innerHTML ='<img id=imghead>';
            var img = document.getElementById('imghead');
            img.onload = function(){
                var rect = clacImgZoomParam(MAXWIDTH, MAXHEIGHT, img.offsetWidth, img.offsetHeight);
                img.width  =  rect.width;
                img.height =  rect.height;
//                 img.style.marginLeft = rect.left+'px';
                img.style.marginTop = rect.top+'px';
            }
            var reader = new FileReader();
            reader.onload = function(evt){img.src = evt.target.result;}
            reader.readAsDataURL(file.files[0]);
        }
        else //兼容IE
        {
            var sFilter='filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale,src="';
            file.select();
            var src = document.selection.createRange().text;
            div.innerHTML = '<img id=imghead>';
            var img = document.getElementById('imghead');
            img.filters.item('DXImageTransform.Microsoft.AlphaImageLoader').src = src;
            var rect = clacImgZoomParam(MAXWIDTH, MAXHEIGHT, img.offsetWidth, img.offsetHeight);
            status =('rect:'+rect.top+','+rect.left+','+rect.width+','+rect.height);
            div.innerHTML = "<div id=divhead style='width:"+rect.width+"px;height:"+rect.height+"px;margin-top:"+rect.top+"px;"+sFilter+src+"\"'></div>";
        }
    }
    function previewImage1(file)
    {
        if(!file_image_reg.test(file.value)){
            alert("图片格式不正确！");
        }else{
            alert("图片可用！");
        }
        var MAXWIDTH  = 0;
        var MAXHEIGHT = 0;
        var div = document.getElementById('preview1');
        if(div.style.width){
            var MAXWIDTH  = div.offsetWidth;
            var MAXHEIGHT = div.offsetHeight;
        }

        if (file.files && file.files[0])
        {
            div.innerHTML ='<img id=imghead1>';
            var img = document.getElementById('imghead1');
            img.onload = function(){
                var rect = clacImgZoomParam(MAXWIDTH, MAXHEIGHT, img.offsetWidth, img.offsetHeight);
                img.width  =  rect.width;
                img.height =  rect.height;
//                 img.style.marginLeft = rect.left+'px';
                img.style.marginTop = rect.top+'px';
            }
            var reader = new FileReader();
            reader.onload = function(evt){img.src = evt.target.result;}
            reader.readAsDataURL(file.files[0]);
        }
        else //兼容IE
        {
            var sFilter='filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale,src="';
            file.select();
            var src = document.selection.createRange().text;
            div.innerHTML = '<img id=imghead1>';
            var img = document.getElementById('imghead1');
            img.filters.item('DXImageTransform.Microsoft.AlphaImageLoader').src = src;
            var rect = clacImgZoomParam(MAXWIDTH, MAXHEIGHT, img.offsetWidth, img.offsetHeight);
            status =('rect:'+rect.top+','+rect.left+','+rect.width+','+rect.height);
            div.innerHTML = "<div id=divhead style='width:"+rect.width+"px;height:"+rect.height+"px;margin-top:"+rect.top+"px;"+sFilter+src+"\"'></div>";
        }
    }
    function previewImage2(file)
    {
        if(!file_image_reg.test(file.value)){
            alert("图片格式不正确！");
        }else{
            alert("图片可用！");
        }
        var MAXWIDTH  = 0;
        var MAXHEIGHT = 0;
        var div = document.getElementById('preview2');
        if(div.style.width){
            var MAXWIDTH  = div.offsetWidth;
            var MAXHEIGHT = div.offsetHeight;
        }

        if (file.files && file.files[0])
        {
            div.innerHTML ='<img id=imghead2>';
            var img = document.getElementById('imghead2');
            img.onload = function(){
                var rect = clacImgZoomParam(MAXWIDTH, MAXHEIGHT, img.offsetWidth, img.offsetHeight);
                img.width  =  rect.width;
                img.height =  rect.height;
//                 img.style.marginLeft = rect.left+'px';
                img.style.marginTop = rect.top+'px';
            }
            var reader = new FileReader();
            reader.onload = function(evt){img.src = evt.target.result;}
            reader.readAsDataURL(file.files[0]);
        }
        else //兼容IE
        {
            var sFilter='filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale,src="';
            file.select();
            var src = document.selection.createRange().text;
            div.innerHTML = '<img id=imghead2>';
            var img = document.getElementById('imghead2');
            img.filters.item('DXImageTransform.Microsoft.AlphaImageLoader').src = src;
            var rect = clacImgZoomParam(MAXWIDTH, MAXHEIGHT, img.offsetWidth, img.offsetHeight);
            status =('rect:'+rect.top+','+rect.left+','+rect.width+','+rect.height);
            div.innerHTML = "<div id=divhead style='width:"+rect.width+"px;height:"+rect.height+"px;margin-top:"+rect.top+"px;"+sFilter+src+"\"'></div>";
        }
    }
    function previewImage3(file)
    {
        if(!file_image_reg.test(file.value)){
            alert("图片格式不正确！");
        }else{
            alert("图片可用！");
        }
        var MAXWIDTH  = 0;
        var MAXHEIGHT = 0;
        var div = document.getElementById('preview3');
        if(div.style.width){
            var MAXWIDTH  = div.offsetWidth;
            var MAXHEIGHT = div.offsetHeight;
        }

        if (file.files && file.files[0])
        {
            div.innerHTML ='<img id=imghead3>';
            var img = document.getElementById('imghead3');
            img.onload = function(){
                var rect = clacImgZoomParam(MAXWIDTH, MAXHEIGHT, img.offsetWidth, img.offsetHeight);
                img.width  =  rect.width;
                img.height =  rect.height;
//                 img.style.marginLeft = rect.left+'px';
                img.style.marginTop = rect.top+'px';
            }
            var reader = new FileReader();
            reader.onload = function(evt){img.src = evt.target.result;}
            reader.readAsDataURL(file.files[0]);
        }
        else //兼容IE
        {
            var sFilter='filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale,src="';
            file.select();
            var src = document.selection.createRange().text;
            div.innerHTML = '<img id=imghead3>';
            var img = document.getElementById('imghead3');
            img.filters.item('DXImageTransform.Microsoft.AlphaImageLoader').src = src;
            var rect = clacImgZoomParam(MAXWIDTH, MAXHEIGHT, img.offsetWidth, img.offsetHeight);
            status =('rect:'+rect.top+','+rect.left+','+rect.width+','+rect.height);
            div.innerHTML = "<div id=divhead style='width:"+rect.width+"px;height:"+rect.height+"px;margin-top:"+rect.top+"px;"+sFilter+src+"\"'></div>";
        }
    }
    function clacImgZoomParam( maxWidth, maxHeight, width, height ){
        var param = {top:0, left:0, width:width, height:height};
        if(maxWidth){
            rateWidth = width / maxWidth;
            rateHeight = height / maxHeight;

            if( rateWidth > rateHeight )
            {
                param.width =  maxWidth;
                param.height = Math.round(height / rateWidth);
            }else
            {
                param.width = Math.round(width / rateHeight);
                param.height = maxHeight;
            }
            param.left = Math.round((maxWidth - param.width) / 2);
            param.top = Math.round((maxHeight - param.height) / 2);
        }
        return param;
    }
</script>
<script type="text/javascript">
    $(document).ready(function(){
        $(".business_licence").click(function(){
            $(".business_licence_image").slideToggle("slow");
        });
        $(".bank_number").click(function(){
            $(".bank_number_image").slideToggle("slow");
        });
        $(".IDcard").click(function(){
            $(".IDcard_image").slideToggle("slow");
        });
        $(".remark").click(function(){
            $(".remark_image").slideToggle("slow");
        });
    });
</script>
<?php ActiveForm::end(); ?>
