<?php

use yii\widgets\LinkPager;
use yii\helpers\Url;
$this->title = "商品属性管理";
?>
<legends  style="fond-size:12px;">
    <?php //$this->render('_search');?>
    <ul class="breadcrumb">
        <li>
            <a href="/">首页</a>
        </li>

        <li class="active">属性列表</li>
    </ul>
    <a class="btn btn-primary" href="/goods/attribute/add" >添加属性</a>

    <div class="tab-content">
        <div class="row-fluid">
            <table class="table table-bordered table-hover">
                <tbody>
                <tr>
                    <th colspan="2">ID</th>
                    <th colspan="2">后台属性名</th>
                    <th colspan="2">属性名</th>
                    <th colspan="2">权重</th>
                    <th colspan="2">操作</th>
                </tr>
                <?php if (!empty($list)) :?>
                    <?php foreach($list as $v):?>
                        <tr>
                            <input type="hidden" class="data-id" value="<?=$v['id']; ?>">
                            <td colspan="2"><?=$v['id']; ?></td>
                            <td colspan="2"><?=$v['admin_name']; ?></td>
                            <td colspan="2"><?=$v['attr_name']; ?></td>
                            <td colspan="2"><?=$v['weight']; ?></td>

                            <td colspan="2">
                                <a href="<?php echo Url::to(['attribute/edit','id'=>$v['id']]);?>">修改</a>
                                <?php if ($v['attr_status']==0):?>
                                <a href="<?php echo Url::to(['attribute/del','id'=>$v['id']]);?>" onclick="confirm('您确定要删除吗?',"del(<?=$v['id'];?>)")">删除</a>
                                <?php endif?>
                            </td>
                        </tr>
                    <?php endforeach;?>
                <?php endif;?>


                </tbody>
            </table>

        </div>
    </div>

</legends>
<script>
    function del(id) {
        var re = confirm('您确定要删除吗？');
        if (!re) {
            return false;
        }
    }
</script>





