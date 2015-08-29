<?php
$this->title = "活动列表";
use yii\widgets\LinkPager;
?>
<legends  style="fond-size:12px;">
    <legend>活动管理</legend>
    <ul class="breadcrumb">
        <li>
            <a href="/">首页</a>
        </li>
        <li class=""><a href="/goods/activity/activity-shop">商家活动列表</a></li>
        <li class="active">商铺商品列表</li>
    </ul>
</legends>
<?= $this->registerJsFile("@web/js/jquery-1.10.2.min.js");?>
<script src="/js/My97DatePicker/WdatePicker.js"></script>

<div class="tab-content">
    <div class="row-fluid">
        <?php if($type != 3){?>
            <table align='center' class="table table-bordered table-hover">
                <tr>
                    <th class="hdTabTit">赠品名称</th>
                    <th>赠品总数</th>
                </tr>
                <?php if(empty($data_z)) {
                    echo '<tr><td colspan="5" style="text-align:center;">暂无记录</td></tr>';
                }else{
                    foreach ($data_z as $item):
                        ?>
                        <tr class="order_list">
                            <td width="30%"><?= $item['product_name'];?></td>
                            <td><?= $item['number'];?></td>
                        </tr>
                    <?php endforeach;
                }
                ?>
            </table>
        <?php }?>
        <table align='center' class="table table-bordered table-hover" <?php if($type != 3){?>style="margin-top: 10px"<?php }?>>
            <tr>
                <th class="hdTabTit">商品名称</th>
                <th>商品总数</th>
                <th>活动价（元）</th>
            </tr>
            <?php if(empty($data)) {
                echo '<tr><td colspan="5" style="text-align:center;">暂无记录</td></tr>';
            }else{
                foreach ($data as $item):
                    ?>
                    <tr class="order_list">
                        <td width="30%"><?= $item['product_name'];?></td>
                        <td><?= $item['day_confine_num'];?></td>
                        <td><span class="numColor"><?=$item['price']?></span></td>
                    </tr>
                <?php endforeach;
            }
            ?>
        </table>
        <div class="pagination pull-left">
            <?= LinkPager::widget(['pagination' => $pages]); ?>
        </div>
    </div>
</div>
