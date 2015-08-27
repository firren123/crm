<?php
$this->title = '商家返利明细';
?>
<legends  style="fond-size:12px;">
    <ul class="breadcrumb">
        <li>
            <a href="/">首页</a>
        </li>
        <li><a href="/shop/rebate">商家返利列表</a></li>
        <li class="active">商家返利明细</li>
    </ul>
    <div class="tab-content">
        <div class="row-fluid">
        </div>
    </div>
</legends>
<div class="tab-content">
    <div class="row-fluid">
        <table class="table table-bordered table-hover">
            <tbody>
            <tr>
                <th>商品名称</th>
                <th>购物数量</th>
                <th>分类</th>
                <th>单价</th>
                <th>属性</th>
                <th>商品总价</th>
            </tr>
            </tbody>
            <tfoot>
            <?php if(empty($list)) {
                echo '<tr><td colspan="14">暂无记录</td></tr>';
            }else {
                foreach ($list as $item):
                    ?>
                    <tr>
                        <td><?= $item['name']?></td>
                        <td><?= $item['num']?></td>
                        <td><?= $item['cate_name']?></td>
                        <td><?= $item['price']?></td>
                        <td><?= empty($item['attribute_str']) ? '--' :$item['attribute_str']?></td>
                        <td><?= $item['total']?></td>
                    </tr>
                <?php
                endforeach;
            }
            ?>
            <tr>
                <td></td><td></td><td></td>
                <td>小计:</td>
                <td>珍品</td>
                <td><?= number_format($treasure_total, 2 , '.', '')?></td>
            </tr>
            <tr>
                <td></td><td></td><td></td><td></td>
                <td>其他</td>
                <td><?= number_format($other_total, 2 , '.', '')?></td>
            </tr>
            <tr>
                <td></td><td></td><td></td>
                <td>总计:</td>
                <td></td>
                <td><?= number_format($total, 2 , '.', '')?></td>
            </tr>
            <tr><td colspan="6"><a class="btn cancelBtn" href="/shop/rebate">返回</a></td></tr>
            </tfoot>

        </table>
    </div>
</div>