<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;

$this->title = "供应商样品管理";

?>



<div class="wide form">
    <form id="search-form" class="well form-inline" action="/shop/shoporder/index" method="get">


    </form>
</div>
<div class="tab-content">

    <div class="row-fluid">
        <table class="table table-bordered table-hover">

            <tr>
                <!--                <th><input type="checkbox" id="all"/>全选</th>-->
                <th>协议名称</th>
                <th>审核状态</th>
                <th>操作</th>

            </tr>
            <?php if (!empty($list) || $list != null) :?>
                <!--                <form action="/shop/shoporder/alledit" method="post">-->
                <?php foreach ($list as $k => $v) : ?>
                    <tr >
                        <td><?= $v['name']; ?></td>
                        <td id="td_<?= html::encode($v['id']); ?>">
                            <?php if ($v['status'] == 0) : ?>
                                待审核
                            <?php elseif ($v['status'] == 2) :?>
                                未通过
                            <?php elseif ($v['status'] == 3) : ?>
                                通过
                            <?php endif;?>
                        </td>
                        <td>

                            <a href="/supplier/agreement/up-status?id=<?=$v['id'];?>&status=3" >通过</a>
                                <a href="/supplier/agreement/up-status?id=<?=$v['id'];?>&status=2" >拒绝</a>

                        </td>
                    </tr>
                <?php endforeach;?>
            <?php endif; ?>
        </table>
    </div>
    <div class="pages">
        <?= LinkPager::widget(['pagination' => $pages]) ?>
    </div>
</div>
