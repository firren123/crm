<?php
/**
 * 简介1
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   admin
 * @filename  add.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/6/1 下午1:31
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = $title;
?>
<script type="text/javascript" src="/js/My97DatePicker/WdatePicker.js"></script>

<script type="text/javascript" src="/js/webuploader//webuploader.js"></script>
<style>
    .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td{
        text-align: left;
    }
    .compileForm .imgListForm{padding-top:10px; height:117px;}

    ul{list-style: none;}
    .imgListForm li{width:92px;min-height:117px; border:none; background:#fff;float: left}
    .imgListForm a,.imgListForm span{display:block;}
    .imgListForm a{width:90px; height:90px; text-align:center; border:1px solid #dfdfdf; background:#f5f5f5;}
    .imgListForm span{color:#666; line-height:25px; cursor:pointer;text-align: center;}
</style>
<link rel="stylesheet" type="text/css" href="/js/webuploader/webuploader.css">
<legends  style="fond-size:12px;">
    <ul class="breadcrumb">
        <li>
            <a href="/">首页</a>
        </li>
        <li><a href="/goods/activity/list">活动列表</a></li>
        <li class="active"><?= $title;?></li>
    </ul>
    <div class="tab-content">
        <div class="row-fluid">
        </div>
    </div>
</legends>

<table class="table table-bordered table-hover">
    <tr>
        <th colspan="6" style="text-align: left;">基本设定</th>
    </tr>
    <tr>
        <th width="20%">活动名称</th>
        <td colspan="5" width="80%">
            <?= $activity['name'];?>
        </td>
    </tr>
    <tr>
        <th width="20%">活动类型</th>
        <td colspan="5" width="80%">
            <?= $activity_type[$activity['type']];?>

        </td>
    </tr>
    <tr>
        <th>报名时间</th>
        <td colspan="5" width="80%">
            开始时间：<?= $activity['register_start_time'];?>-
            结束时间：<?= $activity['register_end_time'];?>

        </td>
    </tr>
    <tr>
        <th>活动时间</th>
        <td colspan="5" width="80%">
            开始时间：<?= $activity['start_time'];?>-
            结束时间：<?= $activity['end_time'];?>

        </td>
    </tr>
    <tr>
        <th>活动有效性</th>
        <td colspan="5" width="80%">
            <?php if($activity['status']==1){ echo '启用';}else{ echo "禁用";}?>

        </td>
    </tr>
    <tr>
        <th>支付方式限定</th>
        <td colspan="5" width="80%">

            <?php foreach($pay_site_id_data as $k =>$v ): ?>
                <?php if(isset($activity['pay_type']) && false !==strpos($activity['pay_type'],"$k")){ echo $v.",";} ?>

            <?php  endforeach; ?>
        </td>
    </tr>
    <tr>
        <th>活动平台</th>
        <td colspan="5" width="80%">
                <?php if(isset($activity['platform']) && false !==strpos($activity['platform'],"1")){ echo "PC,";} ?>
            <?php if(isset($activity['platform']) && false !==strpos($activity['platform'],"2")){ echo "WAP,";} ?>
            <?php if(isset($activity['platform']) && false !==strpos($activity['platform'],"3")){ echo "APP,";} ?>

        </td>
    </tr>
    <tr>
        <th>新用户限定</th>
        <td colspan="5" width="80%">
            <div class="radio">
                <?php if($activity['new_user_site']==1){ echo '启用';}else{ echo "禁用";}?>

            </div>
        </td>
    </tr>
    <tr>
        <th>活动页面链接</th>
        <td colspan="5">
            <?php if($activity['display']==0){ echo '不展示';}else{ echo $activity['display_url'];}?>

        </td>
    </tr>
    <tr>
        <th>参与次数限制</th>
        <td colspan="5">
            <?php if($activity['confine_num']==0){ echo '禁止';}else{ echo $activity['confine_num'];}?>

        </td>
    </tr>
    <tr>
        <th>活动排序</th>
        <td colspan="5"><?php echo $activity['sort'];?></td>
    </tr>
    <tr>
        <th>活动图片</th>
        <td colspan="5" width="80%">
            <ul class="imgList imgListForm">
                <?php
                $images = array('','','','','');
                if(isset($activity['images'])){
                    $images = explode('###',$activity['images']);
                }

                ?>
                <!--当上传图片后<span class="txt">上传</span>去掉-->
                <li>
                        <?php if(!empty($images[0])){ ?>
                            <img src="<?= \Yii::$app->params['imgHost'].$images[0];?>" style="width:90px;height:90px;">

                        <?php } ?>
                </li>
                <li>
                        <?php if(!empty($images[1])){ ?>
                            <img src="<?= \Yii::$app->params['imgHost'].$images[1];?>" style="width:90px;height:90px;">

                        <?php } ?>
                </li>
                <li>
                        <?php if(!empty($images[2])){ ?>
                            <img src="<?= \Yii::$app->params['imgHost'].$images[2];?>" style="width:90px;height:90px;">

                        <?php } ?>
                </li>
                <li>
                        <?php if(!empty($images[3])){ ?>
                            <img src="<?= \Yii::$app->params['imgHost'].$images[3];?>" style="width:90px;height:90px;">
                        <?php } ?>
                </li>
                <li>
                        <?php if(!empty($images[4])){ ?>
                            <img src="<?= \Yii::$app->params['imgHost'].$images[4];?>" style="width:90px;height:90px;">
                        <?php } ?>
                </li>
            </ul>
        </td>
    </tr>

    <tr>
        <th>区域限定范围</th>
        <td colspan="5" width="80%">
            <select name="area_status" id="is_address">
                <option value="">请选择类型</option>
                <?php foreach($activity_scope as $k =>$v ): ?>
                    <option value="<?=$k; ?>" <?php if(isset($activity['area_status']) && $activity['area_status'] == $k){ echo " selected "; } ?>><?= $v?></option>
                <?php  endforeach; ?>
            </select>
            <input type="hidden" id="hide_area_status" value="<?php if(isset($activity['area_status'])){ echo $activity['area_status']; } ?>"/>
        </td>
    </tr>

    <tr>
        <th>区域</th>
        <td colspan="5" width="80%" >
            <div id="act_province" style="float: left;">
                <select name="province" id="val_province">
                    <option value="">选择省份</option>
                    <?php foreach($province_list as $k =>$v ): ?>
                        <option value="<?=$k; ?>" <?php if(isset($activity['province']) && $activity['province'] == $k){ echo " selected "; } ?>><?= $v?></option>
                    <?php  endforeach; ?>
                </select>
                <input type="hidden" id="hide_province" value="<?php if(isset($activity['province'])){ echo $activity['province']; } ?>"/>
            </div>
            <div id="act_city" style="float: left;">
                <select name="city" id="val_city">
                    <option value="">选择城市</option>

                </select>
                <input type="hidden" id="hide_city" value="<?php if(isset($activity['city'])){ echo $activity['city']; } ?>"/>
            </div>
            <div id="act_district" style="float: left;">
                <select name="district" id="val_district">
                    <option value="">选择区域</option>
                </select>
                <input type="hidden" id="hide_district" value="<?php if(isset($activity['district'])){ echo $activity['district']; } ?>"/>
            </div>
        </td>
    </tr>
    <tr>
        <th>区域全部商家</th>
        <td colspan="5">
            <?php if($activity['is_all_area']==0){ echo '禁止';}else{ echo '启用';}?>
        </td>
    </tr>

    <tr>
        <th>指定店铺</th>
        <td colspan="5" width="80%">

            <table  class="table table-bordered table-hover" id="add_shop_list">
                <tr>
                    <td colspan="4">
                        <span style="float: right">已添加 <b id="shop_num">0</b> 个商家</span>
                    </td>
                </tr>
                <tr class="after_shop">
                    <th>序号</th>
                    <th>商家名</th>
                    <th>经营种类</th>
                </tr>

                <?php
                if(isset($shop_list)){
                    foreach($shop_list as $k=>$v): ?>
                        <tr class="shop_list">
                            <td class="id"><?= $v['shop_id'];?></td>
                            <td class="shop_name"><?= $v['shop_name'];?></td>
                            <td class="manage_type"><?= $v['manage_name'];?></td>

                        </tr>
                    <?php endforeach; } ?>
                <tr id="after_shop" class="empty" style="display: none;">
                    <td colspan="4" style="text-align: center;">暂无商家</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <th>活动金额设定</th>
        <td colspan="5">
            <?php if($activity['meet_amount']==0){ echo '禁止';}else{ echo $activity['meet_amount'];}?>

        </td>
    </tr>
    <tr>
        <th>活动说明</th>
        <td colspan="5">
        <?php if(isset($activity['describe'])){ echo $activity['describe'];}?>
        </td>
    </tr>


</table>


<!--限购开始-->
<div id="purchasing" style="display: none;">
    <table  class="table table-bordered table-hover">
        <tr>
            <td colspan="10">活动商品设定
                <button class="btn btn-primary" id="add_a_goods">添加活动商品</button>
                <span style="float: right;">共<b>9999</b>件活动商品</span>
            </td>

        </tr>
        <tr>
            <th>商品名称</th>
            <th>总数（件）</th>
            <th>日限（件）</th>
            <th>单用户（件）</th>
            <th>活动价（元）</th>
            <th>活动起始</th>
            <th>活动结束</th>
            <th>每日开始</th>
            <th>每日结束</th>
            <th>操作</th>
        </tr>
        <tr>
            <td class="goods_name">商品名称</td>
            <td class="total">总数（件）</td>
            <td class="day_num">日限（件）</td>
            <td class="one_num">单用户（件）</td>
            <td class="price">活动价（元）</td>
            <td class="start_time">活动起始</td>
            <td class="end_time">活动结束</td>
            <td class="day_start_time">每日开始</td>
            <td class="day_end_time">每日结束</td>
            <td class="del">删除</td>
        </tr>
        <tr id="purchasing_after_goods" style="display: none;">
            <td class="empty_goods" colspan="10" style="text-align: center;">暂无商品</td>
        </tr>
    </table>
</div>
<!--限购结束-->
<!--买赠开始-->
<div id="buy_a_gift">
    <table id="_buy" class="table table-bordered table-hover" >
        <tr>
            <td colspan="10"><span class="red">*</span>活动商品设定
                <div class="radio"><label><input type="radio"  <?php if(isset($activity['goods_type']) && $activity['goods_type'] == 1){ echo " checked ";}?> name="goods_type" value="1"> 活动商品分类</label>
                    <select name="goods_cate" id="goods_cate">
                        <option value="">选择商品分类</option>
                        <?php foreach($goods_cate as $v): ?>
                            <option value="<?= $v['id']; ?>" <?php if(isset($activity['goods_cate']) && $activity['goods_cate'] == $v['id']){ echo " selected ";}?> >
                                <?= $v['name'];?></option>
                        <?php endforeach;  ?>
                    </select>
                </div>
                <label><input type="radio" name="goods_type"  <?php if(isset($activity['goods_type']) && $activity['goods_type'] == 0){ echo " checked ";}?> value="0"> 活动商品</label>
                <button class="btn btn-primary" id="_buy_add_goods">添加活动商品</button>
                <span style="float: right;">共<b class="goods_list1_num">0</b>件活动商品</span>
            </td>

        </tr>

            <tr>
                <th>商品名称</th>
                <th>日限（件）</th>
                <th>活动价（元）</th>
                <th>活动起始</th>
                <th>活动结束</th>
            </tr>
        <?php if(isset($activity['goods_type']) && $activity['goods_type'] == 0) {
            if (isset($goods_list)) {
                foreach ($goods_list as $k => $v): ?>
                    <tr class="goods_list1">
                        <td class="goods_name"><?= $v['product_name']; ?></td>
                        <td class="day_num"><?= $v['day_confine_num']; ?></td>
                        <td class="price"><?= $v['price']; ?></td>
                        <td class="start_time"><?= $v['start_time']; ?></td>
                        <td class="end_time"><?= $v['end_time']; ?></td>

                    </tr>
                <?php endforeach;
            }
        }
        ?>


        <tr id="_buy_after_goods" style="display: none;">
            <td class="empty_goods" colspan="10" style="text-align: center;">暂无商品</td>
        </tr>
    </table>
    <table id="_gift" class="table table-bordered table-hover" >
        <tr>
            <td colspan="10">活动商品设定
                <div class="radio"><label><input type="radio"  <?php if(isset($activity['gift_type']) && $activity['gift_type'] == 1){ echo " checked ";}?> name="gift_type" value="1"> 赠优惠券</label>
                    <select name="gift_coupons_id" id="coupons_id">
                        <option value="">选择优惠券</option>
                        <?php foreach($coupon_list as $v): ?>
                            <option value="<?= $v['type_id']; ?>" <?php if(isset($activity['gift_coupons_id']) && $activity['gift_coupons_id'] == $v['type_id']){ echo " selected ";}?> >
                                <?= $v['type_name'];?></option>
                        <?php endforeach;  ?>
                    </select>
                </div>
                <label><input type="radio" name="gift_type"  <?php if(isset($activity['gift_type']) && $activity['gift_type'] == 0){ echo " checked ";}?> value="0"> 赠商品</label>
                <span style="float: right;">共<b class="goods_list2_num">0</b>件活动商品</span>
            </td>

        </tr>
        <tr>
            <th>商品名称</th>
            <th>日限（件）</th>
            <th>活动起始</th>
            <th>活动结束</th>
        </tr>
        <?php
        if(isset($gift_list)){
            foreach($gift_list as $k=>$v): ?>
                <tr class="goods_list2">
                    <td class="goods_name"><?= $v['product_name'];?></td>
                    <td class="one_num"><?= $v['day_confine_num'];?></td>
                    <td class="start_time"><?= $v['start_time'];?></td>
                    <td class="end_time"><?= $v['end_time'];?></td>

                </tr>
            <?php endforeach; } ?>

        <tr id="_gift_after_goods" style="display: none;">
            <td class="empty_goods" colspan="10" style="text-align: center;">暂无商品</td>
        </tr>
    </table>
</div>
<!--买赠结束-->
<!--买送开始-->
<div id="buy_a_send" style="display: none;">
    <table id="_buy2" class="table table-bordered table-hover" style="">
        <tr>
            <td colspan="7">
                <lable><input type="radio" name="buy2_type_type" value="1"/>按分类设定</lable>
                <select name="buy2_type" id="_buy2_type">
                    <option value="">请选择</option>
                </select>
            </td>

        </tr>
        <tr>
            <td colspan="7">
                <lable><input type="radio" name="buy2_type_type" value="2"/>活动商品设定</lable>
                <button class="btn btn-primary" id="_buy2_add_a_goods">添加活动商品</button>
                <span style="float: right;">共<b>9999</b>件活动商品</span>
            </td>

        </tr>
        <tr>
            <th>商品名称</th>
            <th>总数（件）</th>
            <th>日限（件）</th>
            <th>活动价（元）</th>
            <th>活动起始</th>
            <th>活动结束</th>
            <th>操作</th>
        </tr>
        <tr>
            <td class="goods_name">商品名称</td>
            <td class="total">总数（件）</td>
            <td class="day_num">日限（件）</td>
            <td class="price">活动价（元）</td>
            <td class="start_time">活动起始</td>
            <td class="end_time">活动结束</td>
            <td class="del">删除</td>
        </tr>
        <tr id="after_goods">
            <td class="empty_goods" colspan="10" style="text-align: center;">暂无商品</td>
        </tr>
    </table>
    <table id="_send" class="table table-bordered table-hover" style="">
        <tr>
            <td colspan="10">换购商品设定
                <button class="btn btn-primary" id="add_a_goods">添加活动商品</button>
                <span style="float: right;">共<b>9999</b>件活动商品</span>
            </td>

        </tr>
        <tr>
            <th>商品名称</th>
            <th>总数（件）</th>
            <th>日限（件）</th>
            <th>单用户（件）</th>
            <th>换购价（元）</th>
            <th>活动起始</th>
            <th>活动结束</th>
            <th>操作</th>
        </tr>
        <tr>
            <td class="goods_name">商品名称</td>
            <td class="total">总数（件）</td>
            <td class="day_num">日限（件）</td>
            <td class="one_num">单用户（件）</td>
            <td class="price">换购价（元）</td>
            <td class="start_time">活动起始</td>
            <td class="end_time">活动结束</td>
            <td class="del">删除</td>
        </tr>
        <tr id="after_goods">
            <td class="empty_goods" colspan="10" style="text-align: center;">暂无商品</td>
        </tr>
    </table>
</div>
<!--买送结束-->
<table class="table table-bordered table-hover">
    <tr>

        <td style="text-align: center;">
            <input type="hidden" name="id" value="<?php if(isset($activity['id'])){ echo $activity['id'];}?>"/>
            <a class="btn cancelBtn" href="javascript:history.go(-1);">返回</a>
        </td>

    </tr>
</table>
<script type="text/javascript" src="/js/goods/Upload.js?_<?= Yii::$app->params['jsVersion'];?>"></script>
<script type="text/javascript" src="/js/goods/activity.js?_<?= Yii::$app->params['jsVersion'];?>"></script>
<script>

    $(function(){
        activity.childCate();
        activity.isAddress($("#is_address").val());
        $("#is_address").change(function(){
            var val = $(this).val();
            activity.isAddress(val);
        });
        $("#val_province").change(function(){
            var id = $(this).val();
            activity.selectList(id,2);
        });
        $("#val_city").change(function(){
            var id = $(this).val();
            activity.selectList(id,3);
        });
        $("#add_shop").click(function(){
            activity.getVal();
            var dis = $("#val_district").val();
            var d = dialog({
                url:'/goods/activity/shop-list?district='+dis,
                title: '选择商家',
                width:'40em',
                ok: function () {}
            });
            d.showModal();
            return false;
        });

        $("#_buy_add_goods").click(function(){

            var d = dialog({
                url:'/goods/activity/goods-list?type=1',
                title: '选择商品',
                width:'40em',
                ok: function () {}
            });
            d.showModal();
            return false;

        });
        //_buy_add_gift
        $("#_buy_add_gift").click(function(){

            var d = dialog({
                url:'/goods/activity/goods-list?type=2',
                title: '选择商品',
                width:'40em',
                ok: function () {}
            });
            d.showModal();
            return false;

        });
        activity.allNUm(".shop_list","#shop_num");
        activity.allNUm(".goods_list1",".goods_list1_num");
        activity.allNUm(".goods_list2",".goods_list2_num");
        $(document).on("click",".shop_del",function(){
            activity.goodsDel(this);
            activity.allNUm(".shop_list","#shop_num");
            return false;
        });
        activity.allNUm(".goods_list1",".goods_list1_num");
        $(document).on("click",".goods_del1",function(){
            activity.goodsDel(this);
            activity.allNUm(".goods_list1",".goods_list1_num");
            return false;
        });
        activity.allNUm(".goods_list2",".goods_list2_num");
        $(document).on("click",".goods_del2",function(){
            activity.goodsDel(this);
            activity.allNUm(".goods_list2",".goods_list2_num");
            return false;
        });

        $(".sub_ok").click(function(){
            if(!activity.subOk()){
                return false;
            }

        });

    })


</script>
