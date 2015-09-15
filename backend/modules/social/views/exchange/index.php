<?php
use yii\widgets\LinkPager;
use yii\bootstrap\ActiveForm;
$this->title = '退换货列表';
?>
<legends  style="fond-size:12px;">
    <legend>退换货列表</legend>
</legends>
<form action="/social/exchange/index" id="form" method="get">
    <table  class="table table-bordered table-hover">
        <tr>
            <th>手机号:</th>
            <td><input type="text" id="mobile" maxlength="11" size="11" name="mobile" value="<?=$mobile?>"/></td>
            <th>商家名称</th>
            <td>
                <select id="shop_id" name="shop_id">
                    <option value='0'>请选择</option>
                    <?php if($dev == 0){
                        echo "<option value='0'>请选择</option>";
                    }else{
                        foreach ($dev as $val):
                    ?>
                    <option value="<?=$val['shop_id']?>" <?=($val['shop_id']==$shop_id)?'selected':''?>><?=$val['shop_name']?></option>
                    <?php endforeach;
                    }
                    ?>
                </select>
            </td>
            <th>订单号:</th>
            <td>
                <input id="order_sn" name="order_sn" maxlength="20" size="20" value="<?=$order_sn?>" />
            </td>
        </tr>
        <tr>
            <th>退换货:</th>
            <td>
                <select id="type" name="type">
                    <option value="" <?=empty($type)?'selected':''?>>请选择</option>
                    <option value="1" <?=(!empty($type)&&$type==1)?'selected':''?>>退货</option>
                    <option value="2" <?=(!empty($type)&&$type==2)?'selected':''?>>换货</option>
                </select>
            </td>
            <th>订单状态</th>
            <td>
                <select id="status" name="status">
                    <option value="" <?=empty($status) ? 'selected' : ''?>>请选择</option>
                    <option value="0" <?=(!empty($status)&&$status==0)?'selected':''?>>等待审核</option>
                    <option value="1" <?=(!empty($status)&&$status==1)?'selected':''?>>审核通过</option>
                    <option value="2" <?=(!empty($status)&&$status==2)?'selected':''?>>退款中</option>
                    <option value="3" <?=(!empty($status)&&$status==3)?'selected':''?>>已退款</option>
                    <option value="4" <?=(!empty($status)&&$status==4)?'selected':''?>>驳回</option>
                </select>
            </td>
            <td colspan="2">
                <input type="button" onclick="searchlist()" value="搜索" class="btn btn-primary" />
                &nbsp;共&nbsp;<span style="font-size:18px;color:#FF0000;"><?=$count;?></span>&nbsp;条搜索结果
            </td>
        </tr>

    </table>
</form>

<div class="tab-content">

    <div class="row-fluid">
        <table class="table table-bordered table-hover">
            <tbody>
            <tr>
                <th>ID</th>
                <th>手机号</th>
                <th>订单号</th>
                <th>商品名称</th>
                <th>商品个数</th>
                <th>商品单价</th>
                <th>类型</th>
                <th>状态</th>
                <th>申请时间</th>
                <th>操作</th>
            </tr>
            </tbody>
            <tfoot>
            <?php
            if (empty($data)) {
                echo '<tr><td colspan="10">暂无记录</td></tr>';
            } else {
                foreach ($data as $item):
                    ?>
                    <tr>
                        <td class="map"><?=$item['id']?></td>
                        <td><?=$item['mobile']?></td>
                        <td class="order_sn"><?=$item['order_sn']?></td>
                        <td><?=$item['product_name']?></td>
                        <td><?=$item['number']?></td>
                        <td><?=$item['price']?></td>
                        <td>
                            <?php
                            if($item['type'] == 1){
                                echo "退货";
                            }else if($item['type'] == 2){
                                echo "换货";
                            }else {
                                echo "无";
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            if($item['status'] == 0){
                                echo "等待审核";
                            }else if($item['status'] == 1){
                                echo "审核通过";
                            }else if($item['status'] == 2){
                                echo "退款中";
                            }else if($item['status'] == 3){
                                echo "已退款";
                            }else if($item['status'] == 4){
                                echo "驳回";
                            }
                            ?>
                        </td>
                        <td><?=$item['apply_time']?></td>
                        <td>
                            <?php if($item['status']==0){?>
                                <?php if($item['type']==2){?>
                                <a class="checked" href="/social/exchange/check?id=<?=$item['id']?>">审核</a>&nbsp;|&nbsp;
                                <?php }?>
                                <?php if($item['type']==1){?>
                                    <a class="checked" href="/social/exchange/return-goods?id=<?=$item['id']?>&order_sn=<?=$item['order_sn']?>&apply_time=<?=$item['apply_time']?>">审核</a>&nbsp;|&nbsp;
                                <?php }?>
                            <?php } ?>
                            <a class="view" href="javascript:;">详情</a>
                        </td>
                    </tr>
            <?php
                endforeach;
            }
            ?>
            </tfoot>
        </table>
        <div class="pagination pull-right">
            <?= LinkPager::widget(['pagination' => $pages]); ?>
        </div>
    </div>
</div>
<span class="zjs_csrf" style="display:none;"><?php echo \Yii::$app->getRequest()->getCsrfToken(); ?></span>
<script type="text/javascript">
    function searchlist()
    {
        $("#form").submit();
    }
    $(function(){
        $(".view").click(function(){

            var id = $(this).parent().closest("tr").find(".map").text();
            var order_sn = $(this).parent().closest("tr").find(".order_sn").text();
            var k = dialog({title:"提示",
                okValue: '确定',
                ok: function () {}
            });
            if(id == '' || id == 0){
                content = "ID不能为空！！！";
                k.content(content);
                k.showModal();
                return false;
            }
            if(order_sn == ''){
                content = "订单编号不能为空！！！";
                k.content(content);
                k.showModal();
                return false;
            }
            var d = dialog({
                url:'/social/exchange/view?id='+id+'&order_sn='+order_sn,
                title: '退换货订单详情',
                width: '70em',
                okValue: '确定',
                lock: true,
                ok: function () {}
            })
            d.showModal();
        })
    })
</script>