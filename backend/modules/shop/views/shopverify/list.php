<?php
/**
 * 商家信息修改审核列表-view
 *
 * PHP Version 5
 *
 * @category  ADMIN
 * @package   BACKEND
 * @author    zhengyu <zhengyu@iyangpin.com>
 * @time      15/3/26 17:49
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      zhengyu@iyangpin.com
 */


use yii\widgets\LinkPager;
$this->title = "商家信息修改申请审核列表";
?>

<link type="text/css" rel="stylesheet" href="/css/zcommon.css?<?php echo \Yii::$app->params['cssVersion']; ?>" />

<script src="/js/jquery-1.10.2.min.js"></script>
<script src="/js/shopverifylist.js"></script>

<style type="text/css">

</style>



<div>
    <ul class="breadcrumb">
        <li><a href="/">首页</a></li>
        <li class="active"><a href="/shop/shopverify/list" style="color:#286090;">商家资料修改审核</a></li>
        <li class="active">列表</li>
    </ul>
</div>

<div class="zcss_query_aera">
    <span>审核状态：</span>
    <select class="zjs_status">
        <option value="-1"<?php if($arr_select_param['verify_status']===''){echo " selected=\"selected\"";} ?>>不限</option>
        <option value="0"<?php if($arr_select_param['verify_status']===0){echo " selected=\"selected\"";} ?>>待审核</option>
        <option value="1"<?php if($arr_select_param['verify_status']===1){echo " selected=\"selected\"";} ?>>已通过</option>
        <option value="2"<?php if($arr_select_param['verify_status']===2){echo " selected=\"selected\"";} ?>>已驳回</option>
    </select>

    <input type="button" value="查询" class="zjs_btn_query" />
</div>

<div>
    <table class="zcss_table_list"><tbody>
        <tr>
            <th style="width:50px;">商家id</th>
            <th style="width:160px;">商家名称</th>
            <th style="width:160px;">申请时间</th>
            <th style="width:80px;">操作人</th>
            <th style="width:160px;">操作时间</th>
            <th style="width:70px;">状态</th>
            <th style="">驳回原因</th>
            <th style="width:70px;">操作</th>
        </tr>
        <?php foreach($arr_list as $a_apply){ ?>
            <tr>
                <td><?php echo $a_apply['shop_id']; ?></td>
                <td><?php echo $a_apply['shop_name']; ?></td>
                <td><?php echo $a_apply['verify_apply_time']; ?></td>
                <td><?php echo $a_apply['verify_admin_name']; ?></td>
                <td><?php echo $a_apply['verify_operate_time']; ?></td>
                <td><?php echo $map_verify_status[$a_apply['verify_status']]; ?></td>
                <td><?php if($a_apply['verify_status']==2){echo $a_apply['verify_reject_reason'];} ?></td>
                <td>
                    <a href="/shop/shopverify/detail?id=<?php echo $a_apply['id']; ?>" target="_blank">详情</a>
                </td>
            </tr>
        <?php } ?>
        </tbody></table>

    <?php echo LinkPager::widget(['pagination' => $pages]); ?>

</div>





