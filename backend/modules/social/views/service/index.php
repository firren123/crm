<?php
/**
 * 简介1
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Crm
 * @filename  setting.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/9/23 上午10:28
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */

use yii\helpers\Html;
use yii\widgets\LinkPager;
$this->title = "服务列表";

?>
<legends  style="fond-size:12px;">
    <legend>服务列表</legend>
</legends>
<script type="text/javascript" src="/js/My97DatePicker/WdatePicker.js"></script>


<div class="wide form">
    <form id="search-form" class="well form-inline" action="/social/service/index" method="get">
        <label for="title">标题：</label>
        <input id="title" type="text" name="title" value="<?= $title;?>" class="form-control">

        <label for="status">状态：</label>
        <select id="status" name="audit_status" class="form-control">
            <option  value="999">全部</option>
            <?php foreach($audit_status_data as $k => $v){ ?>
                <option <?php if($k == $audit_status){echo " selected ";}?> value="<?= $k; ?>"><?= $v; ?></option>
            <?php } ?>

        </select>
        <button id="sub" class="btn btn-primary" name="yt0" type="submit">搜索</button>
        <a href="/social/service/add-service" class="btn btn-primary">添加服务</a>
    </form>
</div>

<legends  style="fond-size:12px;">
    <div class="tab-content">
        <div class="row-fluid">
            <table  class="table table-bordered table-hover">
                <tbody>
                <tr>
                    <th colspan="2">ID</th>
                    <th colspan="2">标题</th>
                    <th colspan="2">手机号</th>
                    <th colspan="2">审核状态</th>
                    <th colspan="2">上/下架</th>
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
                            <td colspan="2"><?= $item['title'];?></td>
                            <td colspan="2"><?= $item['mobile'];?></td>
                            <td colspan="2"><?php echo isset($audit_status_data[$item['audit_status']]) ? $audit_status_data[$item['audit_status']] : ''; ?></td>
                            <td colspan="2"><?= $item['status']==1?'上架':'下架';?></td>
                            <td colspan="2"><?= $item['create_time'];?></td>



                            <td colspan="4">
                                <a href="<?= '/social/service/detail?id='.$item['id'];?>">详情</a>
                                <a href="<?= '/social/service/edit-service?id='.$item['id'];?>">修改</a>
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
        $("#sub").click(function(){
            var sn = $("#order_sn").val();
//            console.log(sn);
//            console.log(sn.length);
            if(sn.length > 0 && sn.length < 6 ){
                alert('订单号搜索不得少于6位末尾订单号');
                return false;
            }
        })

    })
</script>