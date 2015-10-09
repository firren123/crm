<?php
$this->title = '商品详情';
?>
<legends  style="fond-size:12px;">
    <ul class="breadcrumb">
        <li>
            <a href="/">首页</a>
        </li>
        <li><a href="/goods/product">标准库管理</a></li>
        <li class="active">商品详情</li>
    </ul>
    <div class="tab-content">
        <div class="row-fluid">
        </div>
    </div>
</legends>
<table class="table table-bordered table-hover">
    <?php if (!empty($list)) {?>
        <tr>
            <td style="width:20%;">商品名称：</td>
            <td><?= empty($list['name']) ? '--' : $list['name'];?></td>
        </tr>
        <tr>
            <td style="width:20%;">商品介绍：</td>
            <td><?= empty($list['title']) ? '--' : $list['title'];?></td>
        </tr>
        <tr>
            <td style="width:20%;">商品条形码：</td>
            <td><?= empty($list['bar_code']) ? '--' : $list['bar_code'];?></td>
        </tr>
        <tr>
            <td style="width:20%;">商品属性：</td>
            <td><?= empty($list['attr_value']) ? '--' : $list['attr_value'];?></td>
        </tr>
        <tr>
            <td style="width:20%;">关键词：</td>
            <td><?= empty($list['keywords']) ? '--' : $list['keywords'];?></td>
        </tr>
        <tr>
            <td style="width:20%;">商品分类：</td>
            <td><?= empty($list['cate_name']) ? '--' : $list['cate_name'];?></td>
        </tr>
        <tr>
            <td style="width:20%;">商品二级分类：</td>
            <td><?= empty($list['cate_second_name']) ? '--' : $list['cate_second_name'];?></td>
        </tr>
        <tr>
            <td style="width:20%;">商品品牌：</td>
            <td><?= empty($list['brand_name']) ? '--' : $list['brand_name'];?></td>
        </tr>
        <tr>
            <td style="width:20%;">建议售价：</td>
            <td><?= empty($list['origin_price']) ? '--' : $list['origin_price'];?></td>
        </tr> <tr>
            <td style="width:20%;">进货价：</td>
            <td><?= empty($list['sale_price']) ? '--' : $list['sale_price'];?></td>
        </tr>
        <tr>
            <td style="width:20%;">毛利率(进货)：</td>
            <td><?= empty($list['sale_profit_margin']) ? '--' : $list['sale_profit_margin'];?></td>
        </tr>
        <tr>
            <td style="width:20%;">毛利率(铺货)：</td>
            <td><?= empty($list['shop_profit_margin']) ? '--' : $list['shop_profit_margin'];?></td>
        </tr>
        <tr>
            <td style="width:20%;">库存：</td>
            <td><?= empty($list['total_num']) ? '0' : $list['total_num'];?></td>
        </tr> <tr>
            <td style="width:20%;">供应商：</td>
            <td><?= empty($list['vendor_id']) ? '--' : $list['vendor_id'];?></td>
        </tr>
        <tr>
            <td style="width:20%;">限定区域：</td>
            <td><?= empty($list['area_name']) ? '--' : $list['area_name'];?></td>
        </tr>
        <tr>
            <td style="width:20%;">是否能自营：</td>
            <td><?= $list['is_self']==1 ? '是' : '否';?></td>
        </tr>
        <tr>
            <td style="width:20%;">是否固定价：</td>
            <td><?= $list['fixed_price']==1 ? '是' : '否';?></td>
        </tr>
        <tr>
            <td style="width:20%;">推荐：</td>
            <td><?= $list['is_hot']==1 ? '推荐' : '不推荐';?></td>
        </tr>
        <tr>
            <td style="width:20%;">添加时间：</td>
            <td><?= empty($list['create_time']) ? '--' : date('Y-m-d H:i:s',$list['create_time']);?></td>
        </tr>
        <tr>
            <td style="width:20%;">产品主图：</td>
            <td><img src="<?= \Yii::$app->params['imgHost'].$list['image'];?>" style="max-height: 200px;max-height: 200px"> </td>
        </tr>
        <?php if(!empty($list['atlas'])) :?>
            <tr >
                <td style="width:20%;">产品图集：</td>
                <td style="text-align: left;">
                    <?php foreach ($list['atlas'] as $data) :?>
                        <img src="<?= \Yii::$app->params['imgHost'].$data['image'];?>" style="max-height: 200px;max-height: 200px;margin-left: 20px; margin-top: 20px;">
                    <?php endforeach;?>
                </td>
            </tr>
        <?php endif;?>
        <tr>
            <td style="width:20%;">商品详情：</td>
            <td><?= empty($list['description']) ? '--' : $list['description'];?></td>
        </tr>
    <?php }else{ ?>
        <tr>
            <tr><td colspan="2" style="text-align:center;">暂无记录</td></tr>
        </tr>
    <?php }?>
    <tr>
        <td colspan="2" style="text-align:center;"><a class="btn cancelBtn" href="/goods/product">返回</a></td>
    </tr>
</table>