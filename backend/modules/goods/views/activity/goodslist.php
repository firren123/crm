<?php
/* @var $this SiteController */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
?>
<?php
/* @var $this OrderController */
/* @var $dataProvider CActiveDataProvider */
?>
<script type="text/javascript" src="/js/My97DatePicker/WdatePicker.js"></script>
<legends  style="fond-size:12px;">
    <legend>添加商品</legend>
    <form action="" method="get">
        商品ID：<input type="text" id="product_id" name="product_id" value="<?php if(isset($_GET['product_id'])){ echo $_GET['product_id'];};?>">
        商品条形码：<input type="text" id="bar_code" name="bar_code" value="<?php if(isset($_GET['bar_code'])){ echo $_GET['bar_code'];};?>">
        <input type="submit"  class="btn btn-primary" value="搜索">
        <input type="hidden"  name="_csrf" value="<?= \Yii::$app->getRequest()->getCsrfToken(); ?>" />
        <input type="hidden" name="type" value="<?php if(isset($_GET['type'])){ echo $_GET['type'];};?>">
    </form>
    <br>
    <div class="tab-content">
        <div class="row-fluid">
            <table class="table table-bordered table-hover">
                <tbody>
                <tr>
                    <th colspan="2">商品名称</th>
                    <th colspan="2">商品规格</th>
                    <th colspan="2">商品类型</th>
                </tr>
                <?php if(empty($pro_info)) {
                    echo '<tr><td colspan="6" style="text-align:center;">暂无记录</td></tr>';
                }else{?>
                        <tr>
                            <td colspan="2" id="goods_name"><?= $pro_info['name'];?></td>
                            <td colspan="2"><?= $pro_info['attr_value'];?></td>
                            <td colspan="2"><?= $pro_info['pro_type'];?></td>
                            <input type="hidden" name="pid" value="<?= $pro_info['id']; ?>"/>
                        </tr>
                <?php } ?>
                <?php if($type == 1){?>


                <tr>
                    <th ><span class="red">*</span>有效期</th>
                    <td colspan="5">
                        <input readonly id="start_time"   name="start_time" value="" onFocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
                        至   <input readonly id="end_time" name="end_time" value="" onFocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
                    </td>
                </tr>
<!--                <tr>-->
<!--                    <th>活动商品数</th>-->
<!--                    <td colspan="5">-->
<!--                        <input type="text" id="total" />-->
<!--                    </td>-->
<!--                </tr>-->
                <tr>
                    <th><span class="red">*</span>活动价格</th>
                    <td colspan="5">
                        <input id="price" type="text"/>
                    </td>
                </tr>

                <tr>
                    <th><span class="red">*</span>每日限购</th>
                    <td colspan="5">
                        <div class="radio">
                            <label>
                                <input type="radio" name="day_num" value="0"> 禁止</label>
                            <label>
                                <input type="radio" name="day_num"  value="1"> 启用</label>
                            <input type="text" id="day_num2" name="day_num2"  />
                        </div>
                    </td>
                </tr>
            <tr>
                <td colspan="6" style="text-align: center;"><input type="button" value="添加" class="btn btn-primary" id="btn1">

                </td>
            </tr>
        <?php } ?>
        <?php if($type == 2){?>
        <tr>
            <th ><span class="red">*</span>有效期</th>
            <td colspan="5">
                <input readonly id="start_time"   name="start_time" value="" onFocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
                至   <input readonly id="end_time" name="end_time" value="" onFocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
            </td>
        </tr>
<!--        <tr>-->
<!--            <th>活动商品数</th>-->
<!--            <td colspan="5">-->
<!--                <input type="text" id="total"/>-->
<!--            </td>-->
<!--        </tr>-->

        <tr>
            <th><span class="red">*</span>每日限购</th>
            <td colspan="5">
                <div class="radio">
                    <label>
                        <input type="radio" name="day_num" value="0"> 禁止</label>
                    <label>
                        <input type="radio" name="day_num"  value="1"> 启用</label>
                    <input type="text" id="day_num2" name="day_num2"  />
                </div>
            </td>
        </tr>

        <tr>
            <td colspan="6" style="text-align: center;"><input type="button" value="添加" class="btn btn-primary" id="btn2">
            </td>
        </tr>
        <?php } ?>
        <?php if($type == 3){?>
        <?php } ?>

                </tbody>
            </table>
        </div>

    </div>
</legends>
<script>
    $(function(){
        $('#end_time').blur(function(){
            if($(this).val()){
                var end_time = $(this).val().substr(0,10);
                end_time += ' 23:59:59 '
                $("#end_time").val(end_time);
            }

        })
    })
</script>
<script type="text/javascript" src="/js/goods/activity.js?_<?= Yii::$app->params['jsVersion'];?>"></script>
<script>
    $(function(){
        $("#btn1").click(function(){
            var d = new Object();

            var goods_name = $("#goods_name").html();
            console.log(goods_name);
            if(goods_name == undefined){
                alert('商品不能为空');
                return false;
            }

            var day_num = $("input:radio[name='day_num']:checked").val();
            if(day_num == null){
                alert('每日限购不能为空');
                return false;
            }
            var day_num2 = $("input[name='day_num2']").val();
            if(day_num2.length == 0 && day_num==1){
                alert('每日限购数量不能为空');return false;
            }
            var day = day_num == 0?0:day_num2;


            d.id = $("#pid").val();
            d.start_time=$("#start_time").val();
            d.end_time=$("#end_time").val();
            d.day_num=day;
            d.price=$("#price").val();
            d.goods_name=goods_name;
            var data =parent.activity.getVal();
            if(data.start_time > d.start_time){
                alert('开始时间不能小于活动开始时间');
                return false
            }
            if(data.end_time < d.end_time){
                alert('结束时间不能大于活动结束时间');
                return false
            }
            if(!activity.check(d)){
                return false;
            }
            activity.childAddGoods1(d);
            return false;
        });
        $("#btn2").click(function(){
            var d = new Object();
            var day_num = $("input:radio[name='day_num']:checked").val();
            if(day_num == null){
                alert('每日限购不能为空');
                return false;
            }
            var goods_name = $("#goods_name").html();
            console.log(goods_name);
            if(goods_name == undefined){
                alert('商品不能为空');
                return false;
            }
            var day_num2 = $("input[name='day_num2']").val();
            if(day_num2.length == 0 && day_num==1){
                alert('每日限购数量不能为空');return false;
            }
            var day = day_num == 0?0:day_num2;

            d.id=$("#pid").val();
            d.day_num=day;
            d.start_time=$("#start_time").val();
            d.end_time=$("#end_time").val();
            d.goods_name=$("#goods_name").html();
            var data =parent.activity.getVal();
            if(data.start_time > d.start_time){
                alert('有效期开始时间不能小于活动开始时间');
                return false
            }
            if(data.end_time < d.end_time){
                alert('有效期结束时间不能大于活动结束时间');
                return false
            }
            if(!activity.check(d)){
                return false;
            }
            activity.childAddGoods2(d);
            return false;
        });


    });

</script>
