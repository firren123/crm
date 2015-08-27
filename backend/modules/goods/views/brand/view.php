<?php
use yii\helpers\Html;
$this->title = '品牌所属的分类列表';
?>
<legends  style="fond-size:12px;">
    <ul class="breadcrumb">
        <li>
            <a href="/">首页</a>
        </li>
        <li><a href="/goods/brand">商品品牌管理</a></li>
        <li class="active">品牌所属的分类列表</li>
    </ul>
    <div class="tab-content">
        <div class="row-fluid">
        </div>
    </div>
</legends>
<table class="table table-bordered table-hover" style="width: 50%">
    <?php if (!empty($data)) {?>

        <?php foreach($data as $data):?>
            <tr>
                <td style="width:20%;">分类名称：</td>
                <td><?= $data['name'];?></td>
            </tr>
            <?php endforeach;?>

    <?php }else{ ?>
        <tr>
            <tr><td colspan="2" style="text-align:center;">暂无记录</td></tr>
        </tr>
    <?php }?>
    <tr>
        <td colspan="2" style="text-align:center;"><a class="btn cancelBtn" href="/goods/brand">返回</a></td>
    </tr>
</table>