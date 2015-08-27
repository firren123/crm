<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
$this->title = '添加商品详细信息';
?>
<style>
    #attr_name_id >input{margin-left: 10px;}
</style>
<legends  style="fond-size:12px;">
    <ul class="breadcrumb">
        <li>
            <a href="/">首页</a>
        </li>
        <li><a href="/goods/product">标准库管理</a></li>
        <li class="active">修改商品属性信息</li>
    </ul>
    <div class="tab-content">
        <div class="row-fluid" style="height: 30px;line-height: 30px;padding: 8px 15px;margin-bottom: 20px;text-align: center;font-size: 16px;color: #337ab7">
            <a href="/goods/product/edit?id=<?= $_GET['id'];?>">编辑基本信息</a>  >   <span style="color:red;">属性信息</span>  > <a href="/goods/product/list?id=<?= $_GET['id'];?>">编辑图片</a>
        </div>
    </div>
</legends>
<?php if (empty($item['attr_value'])) {?>
<a id="yw0" class="btn btn-primary" href="/goods/product/attribute?id=<?= $item['id']?>" style="margin-bottom:20px;">添加商品属性信息</a>
<?php }else{?>
    <a id="yw0" class="btn btn-primary" href="/goods/product/attribute-save?id=<?= $item['id']?>" style="margin-bottom:20px;">增加属性</a>
<?php }?>
<div class="tab-content">
    <div class="row-fluid">
        <table class="table table-bordered table-hover">
            <tbody>
            <tr>
                <th style="width: 10%">商品id</th>
                <th style="width:20%;">商品名称</th>
                <th>商品属性</th>
                <th>商品条形码</th>
                <th>操作</th>
            </tr>
            </tbody>
            <tfoot>
            <?php if(empty($item)) {
            echo '<tr><td colspan="5">暂无记录</td></tr>';
            }else{
                ?>
                <td><?= $item['id'];?></td>
                <td><?= $item['name'];?></td>
                <td><?= empty($item['attr_value']) ? '无' : $item['attr_value'];?></td>
                <td><?= $item['bar_code'];?></td>
                <td>
                    <?php if (empty($item['attr_value'])) {?>
                        <a href="/goods/product/attribute?id=<?= $item['id']?>">添加商品属性信息</a>
                    <?php }else{?>
                    <a  href="/goods/product/attribute-edit?id=<?= $item['id']?>" style="margin-bottom:20px;">编辑属性</a>
                    <?php }?>
                </td>
            <?php }?>
            </tfoot>
        </table>
    </div>
</div>
