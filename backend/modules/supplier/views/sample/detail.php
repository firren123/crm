<?= $this->registerCssFile("@web/css/globalm.css");?>
<?php
$this->title = '商家橱位详情';
?>

<div class="content fr">
    <div class="breadcrumbs">您现在的位置：<a href="/">首页</a><span>&gt;</span><a href="/shop/cupboard/index">样品管理</a><span>&gt;</span><span class="current">样品详情</span></div>
    <div class="currrnttitle">样品详情</div>
    <div class="indentbox">
        <div class="indenttitle">基本信息</div>
        <ul>
            <li>
                <dl>
                    <dt>样品名称：</dt>
                    <dd><?php echo $info['title'];?></dd>
                </dl>

            </li>

        </ul>
    </div>

</div>
</div>
<!--确认收货 去付款-->
</div>