<?php
use yii\widgets\LinkPager;
$this->title = '待发布商品管理';
?>
<script type="text/javascript" src="/js/goods/product.js"></script>
<legends  style="fond-size:12px;">
    <legend>待发布商品管理</legend>
</legends>
<?php
echo $this->render('_search', ['search'=>$search,'cate_list'=>$cate_list,'cate_second_data'=>$cate_second_data,'city_data'=>$city_data]);
?>
<a id="yw0" class="btn btn-primary" href="/goods/productpre/add" style="margin-bottom:10px;">添加待发布商品</a>

<div class="tab-content">
    <div class="summary pull-right" >共 <span style="color: red"><?= $total?></span> 个商品</div>
    <br>
    <div class="row-fluid">
        <table class="table table-bordered table-hover">
            <tbody>
            <tr>
                <th style="width: 8%">
                    <input type="checkbox" id="check" name="check" class="findtitall chooseAll" />全选
                </th>
                <th style="width: 10%">商品名称</th>
                <th style="width: 10%">商品属性</th>
                <th>条形码</th>
                <th style="width: 8%">进货价</th>
                <th style="width: 8%">建议售价</th>
                <th style="width: 8%">铺货价</th>
                <th style="width: 8%">顶级分类</th>
                <th style="width: 8%">二级分类</th>
                <th style="width: 8%">上下架</th>
                <th style="width: 10%">限定区域</th>
                <th style="width: 15%">操作</th>
            </tr>
            </tbody>
            <tfoot>
            <?php if(empty($list)) {
                echo '<tr><td colspan="15">暂无记录</td></tr>';
            }else{
                foreach ($list as $item):
                    ?>
                    <tr>
                        <td>
                    <?php if($item['bc_id'] == $bc_id or $item['bc_id']!=$branch_id) :?>
                            <input type="checkbox" name="ids[]" value="<?php echo $item['id'] ?>" id="brandid" class="check"/>
                    <?php endif;?>
                        </td>
                        <td><?= $item['name'];?></td>
                        <td><?= empty($item['attr_value']) ? "--" : $item['attr_value'];?></td>
                        <td><?= empty($item['bar_code']) ? '--' :$item['bar_code'];?></td>
                        <td >
                            <span class="zjs_text_1">￥<span class="zjs_text_price_1"><?php if(isset($item['sale_price'])){echo $item['sale_price'];} ?>

                                </span>
                            </span>
                            <p class="txtF zjs_input_1" style="display:none;">
                                <input type="number" class="zjs_input_price_1" size="8" value="<?php if(isset($item['sale_price'])){echo $item['sale_price'];} ?>" />
                                <button class="zjs_btn_1">提交</button>
                            </p>
                        </td>
                        <td>
                            <span class="zjs_text_2">￥<span class="zjs_text_price_2"><?php if(isset($item['origin_price'])){echo $item['origin_price'];} ?></span>
                            </span>
                            <p class="txtF zjs_input_2" style="display:none;">
                                <input type="number" class="zjs_input_price_2" size="8" value="<?php if(isset($item['origin_price'])){echo $item['origin_price'];} ?>" />
                                <button class="zjs_btn_2">提交</button>
                            </p>
                        </td>
                        <td >
                            <span class="zjs_text_3">￥<span class="zjs_text_price_3"><?php if(isset($item['shop_price'])){echo $item['shop_price'];} ?>

                                </span>
                            </span>
                            <p class="txtF zjs_input_3" style="display:none;">
                                <input type="number" class="zjs_input_price_3" size="8" value="<?php if(isset($item['shop_price'])){echo $item['shop_price'];} ?>" />
                                <button class="zjs_btn_3">提交</button>
                            </p>
                        </td>
                        <td><?= empty($item['cate_name']) ? '--' : $item['cate_name'];?></td>
                        <td><?= empty($item['cate_second_name']) ? '--' : $item['cate_second_name'];?></td>
                        <td><?= $item['status']==1 ? '上架' : '下架';?></td>
                        <td><?= $item['area_name'];?></td>
                        <td style="width: 10%">
                            <?php if($item['bc_id'] == $bc_id or $item['bc_id']!=$branch_id) :?>
                            <a href="/goods/productpre/edit?id=<?= $item['id'];?>" style="cursor:pointer">编辑</a>
                            <?php endif;?>
                            <a href="/goods/productpre/details?id=<?= $item['id'];?>" style="cursor:pointer">详情</a><br>
                            <a href="/goods/productpre/list?id=<?= $item['id'];?>">图集</a>
                            <a href="javascript:void(0)" onclick="getUpdateSingle(<?= $item['id'];?>)">发布</a>
                            <a onclick="Delete(<?= $item['id'];?>)" style="cursor:pointer;display: none;">删除</a> <br>

                            <span class="zjs_cur_id" style="display:none;"><?php if(isset($item['id'])){echo $item['id'];} ?></span>

                        </td>

                    </tr>
                <?php endforeach;
            }
            ?>
            <tr>
                <td colspan="15">
                    <div id="egw0" class="pull-right" style="position:relative">
                        <input type="hidden" id="token" value="<?php echo Yii::$app->getRequest()->getCsrfToken(); ?>" />
                        <button id="id_btn_set_pop" class="bulk-actions-btn btn btn-success btn-small active" type="button" name="yt1" onclick="getSingle()">批量发布</button>
                        <div class="bulk-actions-blocker" style="position: absolute; top: 0px; left: 0px; height: 100%; width: 100%; display: none;"></div>
                    </div>

                </td>
            </tr>
            </tfoot>
            </table>
        <div class="pagination pull-right">
            <?= LinkPager::widget(['pagination' => $pages]); ?>
        </div>
        </div>
    </div>
<span class="zjs_csrf" style="display:none;"><?php echo \Yii::$app->getRequest()->getCsrfToken(); ?></span>
<script>
    clickCheckbox();

</script>