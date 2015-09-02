<?php
use common\helpers\RequestHelper;
$this->title = '商品图片管理';
?>
<script type="text/javascript" src="/js/goods/product.js"></script>
<legends  style="fond-size:12px;">
    <ul class="breadcrumb">
        <li>
            <a href="/">首页</a>
        </li>
        <li><a href="/goods/product">标准库管理</a></li>
        <li class="active">商品图片管理</li>
    </ul>
</legends>
<a id="yw0" class="btn btn-primary" href="/goods/product/save?id=<?= RequestHelper::get('id')?>" style="margin-bottom:20px;">添加图片</a>
<div class="tab-content">
    <div class="row-fluid">
        <table class="table table-bordered table-hover">
            <tbody>
            <tr>
                <th style="width: 5%">id</th>
                <th style="width:40%;">图片</th>
                <th>是否是主图</th>
                <th>是否显示</th>
                <th style="width: 10%">添加时间</th>
                <th>操作</th>
            </tr>
            </tbody>
            <tfoot>
            <?php if(empty($list)) {
                echo '<tr><td colspan="6">暂无记录</td></tr>';
            }else{
                foreach ($list as $item):
                    ?>
                    <tr>
                        <td><?= $item['id'];?></td>
                        <td><img src="<?= \Yii::$app->params['imgHost'].$item['image'];?>"  style="max-width: 200px;max-height: 150px;"> </td>
                        <td><?= $item['type']==1 ? '是' :'否';?></td>
                        <td><?= $item['status']==2 ? '是' :'否';?></td>
                        <td><?= date('Y-m-d H:i:s',$item['create_time']);?></td>
                        <td>
                            <?php if($item['type']==1){?>
                            <?php }else{ ?>
                                <a onclick="UpdateImg(<?= $item['id'];?>,<?= $data['id'];?>)" style="cursor:pointer">设为主图</a> |
                                <a onclick="DeleteImg(<?= $item['id'];?>)" style="cursor:pointer">删除</a>
                            <?php } ?>
                        </td>

                    </tr>
                <?php endforeach;
            }
            ?>
            </tfoot>
            </table>
        </div>
    </div>
