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
$this->title = "店铺设置列表";

?>
<legends  style="fond-size:12px;">
    <legend>店铺设置列表</legend>
</legends>
<script type="text/javascript" src="/js/My97DatePicker/WdatePicker.js"></script>


<div class="wide form">
    <form id="search-form" class="well form-inline" action="/social/service/setting" method="get">
        <label for="name">店铺名称：</label>
        <input id="name" type="text" name="name" value="<?= $name;?>" class="form-control">

        <label for="status">状态：</label>
        <select id="status" name="status" class="form-control">
            <option  value="999">全部</option>
            <option  value="1">禁用</option>
            <option  value="2">可用</option>


        </select>

        <button id="sub" class="btn btn-primary" name="yt0" type="submit">搜索</button>
        <a href="/social/service/add-set" class="btn btn-primary">添加店铺</a>
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