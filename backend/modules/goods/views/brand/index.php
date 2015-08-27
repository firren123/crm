<?php
$this->title = "商品品牌列表";
use yii\widgets\LinkPager;
?>
<script type="text/javascript" src="/js/goods/brand.js"></script>
<legends  style="fond-size:12px;">
    <legend>商品品牌管理</legend>
</legends>
<?php
echo $this->render('_search', ['search'=>$search,'cate_list'=>$cate_list]);
?>
<a id="yw0" class="btn btn-primary" href="/goods/brand/add" style="margin-bottom:20px;">添加品牌</a>
<div class="tab-content">
    <div class="row-fluid">
        <table class="table table-bordered table-hover">
            <tbody>
            <tr>
                <th style="width: 8%">
                    <input type="checkbox" id="check" name="check" class="findtitall chooseAll" />全选
                </th>
                <th>品牌ID</th>
                <th>品牌名称</th>
                <th>品牌图片</th>
                <th>是否有效</th>
                <th>排序</th>
                <th>操作</th>
            </tr>
            </tbody>
            <tfoot>
            <?php if(empty($list)) {
                echo '<tr><td colspan="24" style="text-align:center;">暂无记录</td></tr>';
            }else{
                foreach ($list as $item):
                    ?>
                    <tr>
                        <td>
                            <input type="checkbox" name="ids[]" value="<?php echo $item['id'] ?>" id="brandid" class="check"/>
                        </td>
                        <td><?= $item['id'];?></td>
                        <td><?= $item['name'];?></td>
                        <td><img src="<?= empty($item['img']) ? '/images/05_mid.jpg' :\Yii::$app->params['imgHost'].$item['img'];?>" style="max-width: 150px;max-height: 150px;"></td>
                        <td><?= $item['status']==1 ? '无效' : '有效';?></td>
                        <td><?= $item['sort'];?></td>
                        <td style="width: 20%">
                            <?php if($item['brand_status']==0):?>
                                <a style="cursor:pointer" onclick="Delete(<?= $item['id'];?>)">删除</a> |
                            <?php endif?>
                            <a style="cursor:pointer" href="/goods/brand/edit?id=<?= $item['id'];?>">修改</a> | <a style="cursor:pointer" href="/goods/brand/view?id=<?= $item['id'];?>">所属分类</a></td>
                    </tr>
                <?php endforeach;
            }
            ?>

            <tr style="display: none">
                <td colspan="7">
                    <div id="egw0" class="pull-right" style="position:relative">
                        <input type="hidden" id="token" value="<?php echo Yii::$app->getRequest()->getCsrfToken(); ?>" />
                        <button id="delete_all" class="bulk-actions-btn btn btn-danger btn-small active" type="button" name="yt1" onclick="checkSelectd()">删除所选</button>
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
<script>
    clickCheckbox();
</script>