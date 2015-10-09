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
$this->title = "服务器设置列表";

?>
<script type="text/javascript" src="/js/My97DatePicker/WdatePicker.js"></script>


<div class="wide form">
    <form id="search-form" class="well form-inline" action="/social/service/setting" method="get">
        <label for="name">服务名称：</label>
        <input id="name" type="text" name="name" value="<?= $name;?>" class="form-control">

        <label for="status">状态：</label>
        <select id="status" name="audit_status" class="form-control">
            <option  value="999">全部</option>
            <?php foreach($audit_status_data as $k => $v){ ?>
                <option <?php if($k == $audit_status){echo " selected ";}?> value="<?= $k; ?>"><?= $v; ?></option>
            <?php } ?>

        </select>
<!---->
<!--        <label for="start_time">开始时间：</label>-->
<!--        <input id="start_time" type="text" id="start_time" name="start_time" onFocus="WdatePicker({isShowClear:true,readOnly:false})" value="--><?php //if(isset($start_time)){echo $start_time; };?><!--" class="form-control">-->
<!--        <label for="end_time">结束时间：</label>-->
<!--        <input id="end_time" type="text" name="end_time" onFocus="WdatePicker({isShowClear:true,readOnly:false})" value="--><?php //if(isset($end_time)){echo $end_time; };?><!--" class="form-control">-->
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
                    <th colspan="2">手机号</th>
                    <th colspan="2">店铺名称</th>
                    <th colspan="2">审核状态</th>
                    <th colspan="2">是否禁用</th>
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
                            <td colspan="2"><?= $item['mobile'];?></td>
                            <td colspan="2"><?= $item['name'];?></td>
                            <td colspan="2"><?php echo isset($audit_status_data[$item['audit_status']]) ? $audit_status_data[$item['audit_status']] : ''; ?></td>
                            <td colspan="2"><?= $item['status']==1?'禁用':'可用';?></td>
                            <td colspan="2"><?= $item['create_time'];?></td>



                            <td colspan="4">
                                <a href="<?= '/social/service/setting-detail?id='.$item['id'];?>">详情</a>
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