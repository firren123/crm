
<?php
/**
 * 简介1
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   admin
 * @filename  index.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/7/20 下午5:48
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = '退换货订单列表';

?>
<script type="text/javascript" src="/js/financial/userorder.js?<?php echo Yii::$app->params['jsVersion']; ?>"></script>
    <?php
    $form = ActiveForm::begin([
        'id' => "login-form",
        'layout' => 'horizontal',
        'enableAjaxValidation' => false,
        'action'=>'/financial/userorder/add',
        'options' => ['enctype' => 'multipart/form-data'],
    ]);
    ?>
<div class="content fr">
<div class="currrnttitle">退换货商品 </div>
    <table class="waitlist table table-bordered table-hover">
    <tbody>
    <tr class="waittitle">
        <tr>
        <td colspan="6">
            订单状态已完成可以使用部分退，整单退，如果订单没有已完成只能整单退
        </td>
    </tr>
        <th colspan="2">选择退款方式</th>
       <td colspan="4">
           <input type="radio" name="type" checked value="1"/>整单退
           <?php if(isset($order_info['ship_status']) && $order_info['ship_status'] == 5 ) { ?>
           <input type="radio" name="type" value="2"/>部分退
           <?php } ?>
       </td>
    </tr>
    </tbody>
    </table>
<table class="waitlist table table-bordered table-hover">
    <tbody>
    <tr class="waittitle">
        <th>选择</th>
        <th class="merchandisebox">宝贝</th>
        <th>属性</th>
        <th>单价</th>
        <th>数量</th>
        <th>商品总价</th>
    </tr>
    <?php
    if(empty($data)){
        echo '<div class="dataBox">暂无记录</div>';
    } else {
        foreach ($data as $k=>$item):
            ?>
        <tr>
            <td><input class="choice" type="checkbox" value="<?=$item['p_id']?>" name="goods[<?= $k;?>][pid]"></td>
            <td class="tdbox">
                <dl class="commoditybox">
                    <dt><a href=""><img src=""></a></dt>
                    <dd>
                        <a href=""><?=$item['name'];?></a>
                    </dd>
                </dl>
            </td>
            <td><?=$item['attribute_str'];?></td>
            <td class="price"><?=$item['price']; ?></td>
            <td><input class="num" name="goods[<?= $k;?>][num]"   type="text" maxlength="5" size="5" value="<?=$item['num']; ?>"></td>
            <td ><span class="total"><?=$item['total']; ?></span>元</td>

        </tr>
    <?php endforeach;
    }
    ?>
    <tr>
        <td colspan="6">
<!--            --><?php //if(isset($order_info['coupon_money'])){ ?>
<!--                <div id="coupon">使用优惠券: <span id="coupon_price">--><?php //echo $order_info['coupon_money']; ?><!--</span></div>-->
<!--            --><?php //} ?>
            <?php if(isset($order_info['dis_amount']) && $order_info['dis_amount']>0){ ?>
                <div>优惠金额: <span id="dis_amount">-<?php echo $order_info['dis_amount']; ?></span></div>
            <?php } ?>
            <div id="all_price">退款价格: <span id="order-all-price">0</span>元</div>

        </td>
    </tr>

    </tbody>
</table>
    <input type="hidden" name="order_sn" value="<?= $order_info['order_sn']?>"/>

    <input type="submit" value="提交" class="btn waitbtn btn-primary"/>
    <input type="hidden" id="_csrf" name="YII_CSRF_TOKEN" value="<?php echo \Yii::$app->getRequest()->getCsrfToken(); ?>" />
</div>
<?php ActiveForm::end(); ?>
<!--end-->

<script type="text/javascript">
    $(function(){
       //计算一个商品的价格
        $(".num").keyup(function(){
            userOrder.oneGoodsPrice(this);
            userOrder.allTotal();
        });
       //计算总价
        $(".choice").change(function(){
            userOrder.allTotal();
        });


    });

//    $(function(){
        /*$("#ceshi").click(function(){
            var checked = [];
            $('input[name="choice"]:checked').each(function() {
                checked.push($(this).val());
            });
            alert(checked);
            var num = [];
            $('input[name="num"]').each(function() {
                num.push($(this).val());
            });
        })*/
//        $(".num").blur(function(){
//            var number = $(this).parent().closest("tr").find(".num").val();
//            var sum    = $(this).parent().closest("tr").find(".price").html();
//            (isNaN(number) && number>=0) ? number : 0;
//            var sumprice = number*sum;
//                sumprice=sumprice.toFixed(2);
//            $(this).parent().closest("tr").find(".sum").html(sumprice);
//        })
//
//        $(".choice").change(function(){
//            var checked = [];
//            $('input[name="choice"]:checked').each(function() {
//                checked.push($(this).parent().closest("tr").find(".sum").html());
//            });
//
//            for(var kkk=i=0;i<checked.length;i++){
//                 kkk += checked[i];
//                alert(kkk);
//            }

            //alert(checked);

            /*$.each(checked,function(i){
                for(k=0;k<checked.leng;k++) {
                    alert(k);
                }*/
                //alert(checked[i]);
            //});
//
//            var number = $(this).parent().closest("tr").find(".sum").html();
//
//            $("#all_price").html("总价格："+checked);
//           //alert(checked);
//        })
//
//
//    });


</script>