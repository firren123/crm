<?php
use yii\widgets\LinkPager;
$this->title = '商家进货记录管理';
?>
<script type="text/javascript" src="/js/rebate.js"></script>
<script src="/js/My97DatePicker/WdatePicker.js"></script>
<legends  style="fond-size:12px;">
    <legend>商家进货记录管理</legend>
</legends>
<div class="wide form">
    <form id="search-form" class="well form-inline" action="/shop/purchase" method="get">
        <label for="name">商家名称：</label>
        <input type="text" class="form-control" name="Search[shop_name]" style="width: 120px" value="<?php if (isset($search['shop_name']) && $search['shop_name'] !== '') { ?><?php echo $search['shop_name']; ?><?php } ?>">
        <label id="two" for="name">时间：</label>
        <input type="text" class="form-control" style="width: 120px" name="Search[begin_time]"
               value="<?php if (isset($search['begin_time']) && $search['begin_time'] !== '') { ?><?php echo $search['begin_time']; ?><?php } ?>"
               onFocus="WdatePicker({isShowClear:true,readOnly:false})"
            />
        <span> 至 </span>
        <input type="text" class="form-control" style="width: 120px" name="Search[end_time]"
               value="<?php if (isset($search['end_time']) && $search['end_time'] !== '') { ?><?php echo $search['end_time']; ?><?php } ?>"
               onFocus="WdatePicker({isShowClear:true,readOnly:false})"
            />
        <label for="name">计算状态：</label>
        <select id="branch_id" class="SearchForm_type" name="Search[status]" style="width: 120px;">
            <option value="">不限制</option>
            <option value="2" <?php if(!empty($search['status']) and 2==$search['status']):?>selected="selected"<?php endif;?>>未计算</option>
            <option value="1" <?php if(!empty($search['status']) and 1==$search['status']):?>selected="selected"<?php endif;?>>已计算</option>
        </select>
        <button id="yw3" class="btn btn-primary" name="yt0" type="submit">搜索</button>
    </form>
</div>
<div class="tab-content">
    <div class="summary pull-right" >共 <span style="color: red"><?= $total?></span> 条记录</div>
    <br>
    <div class="row-fluid">
        <table class="table table-bordered table-hover">
            <tbody>
            <tr>
                <th>ID</th>
                <th>订单号</th>
                <th>商家名称</th>
                <th>商品名称</th>
                <th>分类名称</th>
                <th>进货价</th>
                <th>购买数量</th>
                <th>结算剩余</th>
                <th>渠道</th>
                <th>进货日期</th>
                <th>计算状态</th>
            </tr>
            </tbody>
            <tfoot>
            <?php if(empty($list)) {
                echo '<tr><td colspan="11">暂无记录</td></tr>';
            }else {
                foreach ($list as $item):
                    ?>
                    <tr>
                        <td><?= $item['id']?></td>
                        <td><a href="/shop/shoporder/detail?order_sn=<?= $item['order_sn']?>"><?= $item['order_sn']?></a></td>
                        <td><?= $item['shop_name']?></td>
                        <td><?= empty($item['product_name']) ? '--' : $item['product_name']?></td>
                        <td><?= empty($item['cate_name']) ? '--' : $item['cate_name']?></td>
                        <td><?= $item['buy_price']?></td>
                        <td><?= $item['buy_number']?></td>
                        <td><?= $item['surplus']?></td>
                        <td><?php
                            switch($item['goods_type']) {
                                case 1;
                                    echo "自营";
                                    break;
                                case 2;
                                    echo "500m特供";
                                    break;
                            }
                            ?></td>
                        <td><?= $item['buy_date']?></td>
                        <td><?php
                            switch($item['status']) {
                                case 0;
                                    echo "未计算";
                                    break;
                                case 1;
                                    echo "已计算";
                                    break;
                            }
                            ?></td>
                    </tr>
                <?php
                endforeach;
            }
            ?>
            </tfoot>
        </table>
        <div class="pagination pull-right">

            <?= LinkPager::widget(['pagination' => $pages,
                'prevPageLabel' => '上一页',
                'nextPageLabel' => '下一页',
                'firstPageCssClass' => '',
                'lastPageCssClass' => '',
                'firstPageLabel' => '首页',
                'lastPageLabel' => '末页',
                'maxButtonCount'=>5,]); ?>
        </div>
    </div>
</div>