<?php
/**
 * 简介1
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   I500
 * @filename  index.php
 * @author    sunsong <sunsongsong@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/8/21 下午9:32
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */
use yii\helpers\Html;
use yii\widgets\LinkPager;
?>
<script src="/js/storageout/storage.js"></script>
<div class="wide form">
    <form id="search-form" class="well form-inline">
        <label for="bar_code">商品名称：</label>
        <input id="name" type="text" size="31" name="name" value="<?=$name?>" class="form-control"/>
        <button id="sub" class="btn btn-primary" name="yt0" type="submit">搜索</button>
    </form>
</div>

<legends  style="fond-size:12px;">
    <div class="tab-content">
        <div class="row-fluid">
            <table  class="table table-bordered table-hover">
                <tr>
                    <th>ID</th>
                    <th>物品名称</th>
                    <th>规格型号</th>
                    <th>单位</th>
                    <th>单价</th>
                    <th>出库数量</th>
                    <th>金额</th>
                    <th>备注</th>
                    <th>操作</th>
                </tr>
                <?php if(empty($data)) {
                    echo '<tr><td colspan="10" style="text-align:center;">暂无记录</td></tr>';
                }else{
                    foreach($data as $item):?>
                        <tr>
                            <td class="id" style="text-align:center;"><?=$item['id']?></td>
                            <td class="goodid" style="display:none; text-align:center;"><?=$item['good_id']?></td>
                            <td class="goodname" style="text-align:center;"><?=$item['good_name']?></td>
                            <td class="goodattr" style="text-align:center;"><?=$item['attr_value'] == ''?'规格型号为空':$item['attr_value'];?></td>
                            <td style="text-align:center;">件</td>
                            <td style="text-align:center;">
                                <input type="text" maxlength="8" size="5" class="pric" value="">
                            </td>
                            <td style="text-align:center;">
                                <input type="text" maxlength="8" size="5" class="number" value="">
                            </td>
                            <td class="sum" style="text-align:center;">0</td>
                            <td class="bar_code" style="display:none;text-align:center;"><?=$item['bar_code']?></td>
                            <td style="text-align:center;">
                                <textarea type="text" class="remark"></textarea>
                            </td>
                            <td class="tj" style="text-align:center;"><a href="javascript:;" class="add">添加</a></td>
                        </tr>
                    <?php endforeach;
                }?>
            </table>
            <div class="pagination pull-right">
                <?= LinkPager::widget(['pagination' => $pages]); ?>
            </div>
        </div>
    </div>

</legends>
<script type="text/javascript">
    $(function(){

        //价格输入事件
        $(".pric").keyup(function(){
            var price = $.trim($(this).parent().closest("tr").find(".pric").val());
            var num = $.trim($(this).parent().closest("tr").find(".number").val());

            if (isNaN(price)) {
                var price = dialog({
                    title: '提示',
                    content: '价格输入非数字！！！',
                    okValue: '确定',
                    ok: function () {}
                })
                price.show();
                $(this).parent().closest("tr").find(".pric").val("");
                return false;
            }
            //var reg = /^-?\d+(\.\d{1,2})?$/;
            var reg = /^[1-9]\d*|[\.][0-9]{0,2}$/;
            if (!reg.test(price) || price<0) {
                var price = dialog({
                    title: '提示',
                    content: '价格输入数字有误！！！',
                    okValue: '确定',
                    ok: function () {}
                })
                price.show();
                $(this).parent().closest("tr").find(".pric").val("");
                return false;
            }

            var re = /^[\.\d]+$/;
            if(re.test(price))
            {
                var pric_reg = /^\d{0,5}(\.\d{0,2})?$/;
                if(!pric_reg.test(price)){
                    var p = dialog({
                        title: '提示',
                        content: '输入价格格式不正确（保留两位小数）！！！',
                        okValue: '确定',
                        ok: function () {}
                    })
                    p.show();
                    $(this).parent().closest("tr").find(".pric").val("");
                    return false;
                }
            }

            var sum = price*100*num/100;
            $(this).parent().closest("tr").find(".sum").text(sum.toFixed(2));
        })
        $(".number").keyup(function(){
            var price = $.trim($(this).parent().closest("tr").find(".pric").val());
            var num = $.trim($(this).parent().closest("tr").find(".number").val());
            var id = $.trim($(this).parent().closest("tr").find(".goodid").text());

            var num_reg = /^[1-9]\d*$/;
            if (isNaN(num)) {
                var n = dialog({
                    title: '提示',
                    content: '数量输入非数字！！！',
                    okValue: '确定',
                    ok: function () {}
                })
                n.show();
                $(this).parent().closest("tr").find(".number").val("");
                return false;
            }
            if (!num_reg.test(num) || num<0) {
                var n = dialog({
                    title: '提示',
                    content: '数量输入数字有误！！！',
                    okValue: '确定',
                    ok: function () {
                        $(this).parent().closest("tr").find(".number").val("");
                    }
                })
                n.show();
                return false;
            }

            $.getJSON('/storage/storage-out/stock', {'id': id}, function (data) {
                var stock = parseInt(data['info']);
                var conter = "出库数量不能大于库存("+stock+"件)";
                if((num-stock)>0){
                    var n = dialog({
                        title: '提示',
                        content: conter,
                        okValue: '确定',
                        ok: function () {
                        }
                    })
                    n.show();
                    return false;
                }
            });

            var sum = price*100*num/100;
            $(this).parent().closest("tr").find(".sum").text(sum.toFixed(2));
        })

        $(".add").click(function(){
            var d = new Object();
            var id = $.trim($(this).parent().closest("tr").find(".goodid").text());
            var goodname = $.trim($(this).parent().closest("tr").find(".goodname").text());
            var goodattr = $.trim($(this).parent().closest("tr").find(".goodattr").text());
            var num = $.trim($(this).parent().closest("tr").find(".number").val());
            var pric = $.trim($(this).parent().closest("tr").find(".pric").val());
            var remark = $.trim($(this).parent().closest("tr").find(".remark").val());
            var sum = $.trim($(this).parent().closest("tr").find(".sum").text());
            var bar_code = $.trim($(this).parent().closest("tr").find(".bar_code").text());

            if(id == '')
            {
                var d = dialog({
                    title: '提示',
                    content: '商品ID不能为空！！！',
                    okValue: '确定',
                    ok: function () {}
                })
                d.show();
                return false;
            }
            if(goodname == '')
            {
                var d = dialog({
                    title: '提示',
                    content: '商品名称不能为空！！！',
                    okValue: '确定',
                    ok: function () {}
                })
                d.show();
                return false;
            }
            if(pric == '')
            {
                var d = dialog({
                    title: '提示',
                    content: '商品单价不能为空！！！',
                    okValue: '确定',
                    ok: function () {}
                })
                d.show();
                return false;
            }
            if(num == '')
            {
                var d = dialog({
                    title: '提示',
                    content: '商品数量不能为空！！！',
                    okValue: '确定',
                    ok: function () {}
                })
                d.show();
                return false;
            }
            if(remark == '')
            {
                var d = dialog({
                    title: '提示',
                    content: '备注不能为空！！！',
                    okValue: '确定',
                    ok: function () {}
                })
                d.show();
                return false;
            }
            d.id = id;
            d.name = goodname;
            d.stand = goodattr;
            d.num = num;
            d.pric = pric;
            d.remark = remark;
            d.sum = sum;
            d.bar_code = bar_code;
            suppliers.goods(d,this);
            $(this).parent().closest("tr").find(".tj").text("已添加");

        })
    })
</script>
