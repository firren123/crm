<?php
/**
 * 简介1
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   I500
 * @filename  detail.php
 * @author    weitonghe <weitonghe@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/8/6 上午13:32
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */
$this->title = '商家合同信息详情';
?>
<style>
    tr{ width: 1000px; height: auto;}
    .td1{ width: 200px;}
    .td2{ width: 300px;}
</style>
<div style=" width: 1000px; height: auto; ">
    <h4><a href="/" >首页</a>&gt;<a href="/shop/shopcontract/index">商家合同列表</a><span>&gt;</span>商家合同信息详情</h4>
    <table style="border-bottom: 1px solid #D5692B; width: 1000px; height: auto;">
        <tr>
            <th colspan="4">基本信息</th>
        </tr>
        <tr>
            <th colspan="1" style="" class="td1">合同号：</th>
            <th colspan="1" class="td2"><?= $list['htnumber'];?></th>
        </tr>
        <tr>
            <td class="td1">注册名称：</td>
            <td ><label><?= $list['shop_contract_name'];?></label></td>
            <td class="td1">注册地址：</td>
            <td ><label><?= $list['registered_address'];?></label></td>
        </tr>
        <tr>
            <td class="td1">注册登记号：</td>
            <td ><label><?= $list['registered_id'];?></label></td>
            <td class="td1">注册资本：</td>
            <td ><label><?= $list['registered_capital'];?></label></td>
        </tr>
        <tr>
            <td class="td1">法定代表人：</td>
            <td ><label><?= $list['legal_representative'];?></label></td>
            <td class="td1">邮箱：</td>
            <td ><label><?= $list['email'];?></label></td>
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
            <td ><label><?= $list['document_number'];?></label></td>
        </tr>
        <tr>
            <td class="td1">联系人：</td>
            <td ><label><?= $list['contacts'];?></label></td>
            <td class="td1">联系电话：</td>
            <td ><label><?= $list['contacts_umber'];?></label></td>
        </tr>
        <tr>
            <td colspan="1"class="td1">公司性质：</td>
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
            <td style="color: red;">
                <b>
                    <?php if (isset($list['status'])) {
                        if ($list['status']==0) {
                            echo "商家提交资料中" ;
                        } elseif ($list['status']==1) {
                            echo "已生效" ;
                        }
                        elseif ($list['status']==2) {
                            echo "驳回" ;
                        }
                        elseif ($list['status']==3) {
                            echo "待完善" ;
                        }
                        elseif ($list['status']==4) {
                            echo "审核中" ;
                        }
                    }?>
                </b>
            </td>
        </tr>
    </table>
    <table style="border-bottom: 1px solid #D5692B; width: 1000px; height: auto;">
        <tr>
            <th colspan="4" style="">经营信息</th>
        </tr>
        <tr>
            <td colspan="1" class="td1">店面注册名称：</td>
            <td colspan="3" class="td2">
                <label><?php if(!empty($shop['business_name'])){echo $shop['business_name'];}?></label>
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
            <td colspan="3"><label><?php if(!empty($shop['business_address'])){echo $shop['business_address'];}?></label></td>
        </tr>
    </table>
    <table style="border-bottom: 1px solid #D5692B; width: 1000px; height: auto;">
        <tr>
            <th colspan="4" style="">服务信息</th>
        </tr>
        <tr >
            <td style="" class="td1">日常联系人姓名：</td>
            <td style="" class="td2"><label><?php if(!empty($list['common_contacts_name'])){echo $list['common_contacts_name'];}?></label></td>
            <td style="" class="td1">电话：</td>
            <td style="" ><label><?php if(!empty($list['common_contacts_phone'])){echo $list['common_contacts_phone'];}?></label></td>
        </tr>
        <tr>
            <td class="td1">职务：</td>
            <td><label><?php if(!empty($list['common_contacts_job'])){echo $list['common_contacts_job'];}?></label></td>
            <td class="td1">月均营业额：</td>
            <td><label><?php if(!empty($list['monthly_turnover'])){echo $list['monthly_turnover'];}?></label></td>
        </tr>
        <tr>
            <td class="td1">面积：</td>
            <td><label><?php if(!empty($list['area'])){echo $list['area'];}?></label>&nbsp;&nbsp;M<sup>2</sup></td>
            <td class="td1">营业时间：</td>
            <td >
                上午&nbsp;
                <label><?php if(!empty($list['business_hours']) && isset($list['business_hours'][0]) && $list['business_hours'][0]!=''){echo $list['business_hours'][0]."：00";}?></label>
                &nbsp;至&nbsp;&nbsp;下午
                <label><?php if(!empty($list['business_hours']) && isset($list['business_hours'][1])){echo $list['business_hours'][1]."：00";}?></label>
            </td>
        </tr>
        <tr>
            <td class="td1">所在社区名称：</td>
            <td><label><?php if(!empty($list['community_name'])){echo $list['community_name'];}?></label></td>
        </tr>
    </table>
    <table style=" width: 1000px; height: auto;">
        <tr><th colspan="4">清算信息</th></tr>
        <tr>
            <td colspan="1" class="td1"><label>帐户类型：</label></td>
            <td colspan="3" class="td2">
                <label>
                    <?php
                    if (!empty($list['account_type']) && $list['account_type']==0) { echo "银行账号";}
                    if (!empty($list['account_type']) && $list['account_type']==1) { echo "支付宝账号";}
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
                            <td><label><?php if(!empty($list['alipay_name'])){echo $list['alipay_name'];}?></label></td>
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
                            <td class="td2"><label><?php if(!empty($list['bank_name'])){echo $list['bank_name'];}?></label></td>
                            <td class="td1">开户支行：</td>
                            <td><label><?php if(!empty($list['bank_branch'])){echo $list['bank_branch'];}?></label></td>
                        </tr>
                        <tr>
                            <td class="td1">所在省份：</td>
                            <td><label><?php if(!empty($province['name'])){echo $province['name'];}?></label></td>
                            <td class="td1">所在城市：</td>
                            <td><label><?php if(!empty($city['name'])){echo $city['name'];}?></label></td>
                        </tr>
                        <tr>
                            <td class="td1">银行卡号：</td>
                            <td><label><?= $list['bank_number'];?></label></td>
                            <td class="td1">开户名称：</td>
                            <td><label><?= $list['bankcard_username'];?></label></td>
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
                <label>
                    <?php
                        if ($list['service_charge']==0) { echo "固定服务费用：";}
                        if ($list['service_charge']==1) { echo "服务佣金：";}
                    ?>
                </label>
            </td>
            <td class="td2">
                <label>
                    <?php
                    if ($list['service_charge']==0) { echo $list['fixed_service_charge'] ;}
                    if ($list['service_charge']==1) { echo $list['service_commission']."%";}
                    ?>
                </label>
            </td>
            <td class="td1"><label>结算周期：</label></td>
            <td>
                <label>
                    <?php
                    if (!$list['settlement_cycle']=='') {
                        if ($list['settlement_cycle']==0) { echo "1天";}
                        if ($list['settlement_cycle']==1) { echo "5天";}
                        if ($list['settlement_cycle']==2) { echo "7天";}
                        if ($list['settlement_cycle']==3) { echo "14天";}
                        if ($list['settlement_cycle']==4) { echo "30天";}
                        if ($list['settlement_cycle']==5) { echo "60天";}
                        if ($list['settlement_cycle']==6) { echo "每月1次";}
                        if ($list['settlement_cycle']==7) { echo "每月2次";}
                    }
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
            <td class="td2"><label>开始时间：<?= $list['start_time'];?></label></td>
            <td><label>结束时间：<?= $list['end_time'];?></label></td>
        </tr>
        <tr>
            <td class="td1">业务员信息</td>
            <td>
                <label>业务员ID：<label style="color:red;"><?php if(!empty($business['id'])){echo $business['id'];} ?></label></label>
            </td>
            <td>
                <label >业务员姓名：<label style="color:red;"><?php if(!empty($business['name'])){echo $business['name'];} ?></label></label>
            </td>
        </tr>
    </table>
    <table>
        <tr>
            <th colspan="1" class="td1">合同图片</th>
            <td colspan="1">
                <a class="a_img">查看</a>&nbsp;&nbsp;&nbsp;&nbsp;
                <a class="a_download">下载</a>
                <input type="hidden" class="a_i" value="<?php if(empty($list['image'])){echo "/images/05_mid.jpg";}else{echo \Yii::$app->params['imgHost'].$list['image'];} ?>">
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <img class="img1" style=" display: none; max-width: 1000px; height: auto;" src="<?php if(empty($list['image'])){echo "/images/05_mid.jpg";}else{echo \Yii::$app->params['imgHost'].$list['image'];} ?>" />
            </td>
        </tr>
        <tr>
            <th colspan="1" class="td2">营业执照图片</th>
            <td colspan="1">
                <a class="b_img">查看</a>&nbsp;&nbsp;&nbsp;&nbsp;
                <a class="b_download">下载</a>
                <input type="hidden" class="b_i" value="<?php if(empty($list['business_licence_image'])){echo "/images/05_mid.jpg";}else{echo \Yii::$app->params['imgHost'].$list['business_licence_image'];} ?>">
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <img class="img2" style=" display: none; max-width: 1000px; height: auto;" src="<?php if(empty($list['business_licence_image'])){echo "/images/05_mid.jpg";}else{echo \Yii::$app->params['imgHost'].$list['business_licence_image'];} ?>" />
            </td>
        </tr>
        <tr>
            <th colspan="1" class="td3">银行卡图片</th>
            <td colspan="1">
                <a class="c_img">查看</a>&nbsp;&nbsp;&nbsp;&nbsp;
                <a class="c_download">下载</a>
                <input type="hidden" class="c_i" value="<?php if(empty($list['bank_number_image'])){echo "/images/05_mid.jpg";}else{echo \Yii::$app->params['imgHost'].$list['bank_number_image'];} ?>">
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <img class="img3" style=" display: none; max-width: 1000px; height: auto;" src="<?php if(empty($list['bank_number_image'])){echo "/images/05_mid.jpg";}else{echo \Yii::$app->params['imgHost'].$list['bank_number_image'];} ?>" />
            </td>
        </tr>
        <tr>
            <th colspan="1" class="td4">身份证图片</th>
            <td colspan="1">
                <a class="d_img">查看</a>&nbsp;&nbsp;&nbsp;&nbsp;
                <a class="d_download">下载</a>
                <input type="hidden" class="d_i" value="<?php if(empty($list['IDcard_image'])){echo "/images/05_mid.jpg";}else{echo \Yii::$app->params['imgHost'].$list['IDcard_image'];} ?>">
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <img class="img4" style=" display: none; max-width: 1000px; height: auto;" src="<?php if(empty($list['IDcard_image'])){echo "/images/05_mid.jpg";}else{echo \Yii::$app->params['imgHost'].$list['IDcard_image'];} ?>" />
            </td>
        </tr>
        <tr>
            <th colspan="1" class="td5">备注</th>
            <td colspan="1">
                <a class="e_img">查看</a>&nbsp;&nbsp;&nbsp;&nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <textarea style=" display: none;" maxlength="255" rows="5" cols="100" class="img5"><?php echo $list['remark'] ?></textarea>
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
        $(".a_img").click(function(){
            if ($(".img1").css("display")=='none') {
                $(".a_img").html("关闭");
            } else {
                $(".a_img").html("查看");
            }
            $(".img1").slideToggle("slow");
        });
        $(".a_download").click(function(){
            if($(".a_i").val()!='/images/05_mid.jpg'){
                window.location.href="/shop/shopcontract/downloadimg?img_src="+$(".a_i").val();
            }else{
                alert('图片不存在！');
            }
        });
        $(".b_img").click(function(){
            if ($(".img2").css("display")=='none') {
                $(".b_img").html("关闭");
            } else {
                $(".b_img").html("查看");
            }
            $(".img2").slideToggle("slow");
        });
        $(".b_download").click(function(){
            if($(".b_i").val()!='/images/05_mid.jpg'){
                window.location.href="/shop/shopcontract/downloadimg?img_src="+$(".b_i").val();
            }else{
                alert('图片不存在！');
            }
        });
        $(".c_img").click(function(){
            if ($(".img3").css("display")=='none') {
                $(".c_img").html("关闭");
            } else {
                $(".c_img").html("查看");
            }
            $(".img3").slideToggle("slow");
        });
        $(".c_download").click(function(){
            if($(".c_i").val()!='/images/05_mid.jpg'){
                window.location.href="/shop/shopcontract/downloadimg?img_src="+$(".c_i").val();
            }else{
                alert('图片不存在！');
            }
        });
        $(".d_img").click(function(){
            if ($(".img4").css("display")=='none') {
                $(".d_img").html("关闭");
            } else {
                $(".d_img").html("查看");
            }
            $(".img4").slideToggle("slow");
        });
        $(".d_download").click(function(){
            if($(".d_i").val()!='/images/05_mid.jpg'){
                window.location.href="/shop/shopcontract/downloadimg?img_src="+$(".d_i").val();
            }else{
                alert('图片不存在！');
            }
        });
        $(".e_img").click(function(){
            if ($(".img5").css("display")=='none') {
                $(".e_img").html("关闭");
            } else {
                $(".e_img").html("查看");
            }
            $(".img5").slideToggle("slow");
        });
    });
</script>