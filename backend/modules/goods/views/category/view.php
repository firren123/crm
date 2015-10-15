<?php
$this->title = '商品分类详情';
?>
<legends  style="fond-size:12px;">
    <ul class="breadcrumb">
        <li>
            <a href="/">首页</a>
        </li>
        <li class="active"><a href="/goods/category">商品分类管理</a></li>
        <li class="active">商品分类详情</li>
    </ul>
    <div class="tab-content">
        <div class="row-fluid">
        </div>
    </div>
</legends>
<table class="table table-bordered table-hover" style="width: 50%">
    <?php if (!empty($item)) {?>
        <tr>
            <td style="width: 30%">分类名称:</td>
            <td><?= $item['name']?></td>
        </tr>
        <tr>
            <td style="width: 30%">分类图片:</td>
            <td><img src="<?= empty($item['img']) ? '/images/05_mid.jpg' :\Yii::$app->params['imgHost'].$item['img'];?>" style="width: 200px;height: 200px;" ></td>
        </tr>

        <tr>
            <td style="width: 30%">分类等级:</td>
            <td><?= $item['level']==1 ? "顶级分类" : "二级分类"?></td>
        </tr>
        <?php if ($item['level']!=1) :?>
            <tr>
                <td style="width: 30%">所属分类:</td>
                <td><?= $item['parent_name']?></td>
            </tr>
        <?php endif?>
        <tr>
            <td style="width: 30%">状态:</td>
            <td><?= $item['status']==1 ? "禁用" : "正常"?></td>
        </tr>
        <tr>
            <td style="width: 30%">排序:</td>
            <td><?= $item['sort'];?></td>
        </tr>
    <?php }else{ ?>
        <tr>
            <tr><td colspan="2" style="text-align:center;">暂无记录</td></tr>
        </tr>
    <?php }?>
    <tr>
        <td colspan="2" style="text-align:center;"><a class="btn cancelBtn" href="javascript:history.back();">返回</a></td>
    </tr>
</table>