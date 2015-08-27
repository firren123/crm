<?= $this->registerCssFile("@web/css/globalm.css");?>
<?php
$this->title = '商家橱位详情';
?>

<div class="content fr">
    <div class="breadcrumbs">您现在的位置：<a href="/">首页</a><span>&gt;</span><a href="/shop/cupboard/index">橱位管理</a><span>&gt;</span><span class="current">橱位详情</span></div>
    <div class="currrnttitle">橱位详情</div>
    <div class="indentbox">
        <div class="indenttitle">基本信息</div>
        <ul>
            <li>
                <dl>
                    <dt>橱位名称：</dt>
                    <dd><?php echo $info['title'];?></dd>
                </dl>
                <dl>
                    <dt>橱位编号：</dt>
                    <dd><?php echo $info['number']; ?></dd>
                </dl>
            </li>
            <li>
                <dl>
                    <dt>橱位样品：</dt>
                    <dd><?php echo $info['sample_name']; ?></dd>
                </dl>
                <dl>
                    <dt>商家名称：</dt>
                    <dd><?php echo $info['shop_id']; ?></dd>
                </dl>
            </li>
        </ul>
    </div>
    <div class="indentbox">
        <div class="indenttitle">规格信息</div>
        <ul>
            <li>
                <dl>
                    <dt>长：</dt>
                    <dd><?php echo '5555'; ?></dd>
                </dl>
                <dl>
                    <dt>宽：</dt>
                    <dd><?php echo '6666'; ?></dd>
                </dl>
            </li>
            <li>
                <dl>
                    <dt>高：</dt>
                    <dd><?php echo '77777'; ?></dd>
                </dl>

                <dl>
                    <dt>承重：</dt>
                    <dd><?php echo '77777'; ?></dd>
                </dl>
            </li>
        </ul>
    </div>
        </div>
    </div>
    <!--确认收货 去付款-->
    <a href="/shop/shoporder/index" class="btn waitbtn btn-primary">取&nbsp;&nbsp;&nbsp;&nbsp;消</a>
</div>