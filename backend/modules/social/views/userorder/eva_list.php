<?php
/**
 * 简介1
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Crm
 * @filename  grade_list.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/10/19 上午11:39
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */

use yii\helpers\Html;
use yii\widgets\LinkPager;
$this->title = "店铺评论列表";

?>
<legends  style="fond-size:12px;">
    <legend>店铺评论列表</legend>
</legends>
<script type="text/javascript" src="/js/My97DatePicker/WdatePicker.js"></script>


<div class="wide form">
    <form id="search-form" class="well form-inline" action="/social/userorder/grade-list" method="get">
        <label for="mobile">手机号：</label>
        <input id="mobile" type="text" name="mobile" value="<?= $mobile;?>" class="form-control">
        <button id="sub" class="btn btn-primary" name="yt0" type="submit">搜索</button>
    </form>
</div>
<legends  style="fond-size:12px;">
    <div class="tab-content">
        <div class="row-fluid">
            <table  class="table table-bordered table-hover">
                <tbody>
                <tr>
                    <th colspan="2">ID</th>
                    <th colspan="2">用户名</th>
                    <th colspan="2">商家名称</th>
                    <th colspan="2">手机号</th>
                    <th colspan="2">用户评分</th>
                    <th colspan="2">订单号</th>
                    <th colspan="2">内容</th>
                    <th colspan="2">创建时间</th>
                    <th colspan="4">操作</th>
                </tr>
                <?php if(empty($list)) {
                    echo '<tr><td colspan="24" style="text-align:center;">暂无记录</td></tr>';
                }else{
                    foreach($list as $item){
                        ?>
                        <tr>
                            <td colspan="2"><?= $item['id'];?></td>
                            <td colspan="2"><?= $item['uid'];?></td>
                            <td colspan="2"><?= $item['shop_id'];?></td>
                            <td colspan="2"><?= $item['mobile'];?></td>
                            <td colspan="2"><?= $item['grade'];?></td>
                            <td colspan="2"><?= $item['order_sn'];?></td>
                            <td colspan="2"><?= mb_substr($item['content'], 0, 2, 'utf-8');?>...</td>
                            <td colspan="2"><?= $item['create_time'];?></td>
                            <td colspan="4">
                                <a href="<?= '/social/userorder/grade-detail?id='.$item['id'];?>">详情</a>
                                <a href="#" nim="<?= $item['id'];?>" class="delete">删除</a>
                            </td>
                        </tr>
                    <?php } }?>

                </tbody>
            </table>
            <?= LinkPager::widget(['pagination' => $pages]); ?>
        </div>
    </div>

</legends>
<script>
    $(function(){
        $(".delete").click(function(){
            var msg = "您真的确定要删除吗？";
            var id = $(this).attr("nim");
            console.log(id);
            if (confirm(msg)==true){
                $.ajax(
                    {
                        type: "GET",
                        url: '/social/userorder/grade-del',
                        data: {'id':id},
                        asynic: false,
                        dataType: "json",
                        success: function (result) {
                            if(result==1){
                                window.location.reload()
                            }else{
                                alert(result);
                            }
                        }
                    }
                );

            }
        })

    })

</script>