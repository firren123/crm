<?php
/**
 * 简介1
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   I500
 * @filename  index.php
 * @author    zhaochengqiang <zhaochengqiang@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/3/22 下午19:32
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */
use yii\helpers\Html;
use yii\widgets\LinkPager;
$this->title = "扫码列表";

?>
<script type="text/javascript" src="/js/My97DatePicker/WdatePicker.js"></script>
<?= $this->registerJsFile("@web/js/shoporder.js");?>

<legends style="fond-size:12px;">
    <legend>扫码列表</legend>
</legends>

<div class="wide form">
    <form id="search-form" class="well form-inline" action="/goods/barcode/index" method="post">

        <label for="bar_code">扫码号：</label>
        <input id="bar_code" type="text" size="31" name="bar_code" value="<?php  if(isset($bar_code)){ echo $bar_code;} ?>" class="form-control"/>
        <label for="start_time">开始时间：</label>
        <input id="start_time" type="text" id="start_time" name="start_time" onFocus="WdatePicker({isShowClear:true,readOnly:false})" value="<?php if(isset($start_time) && !empty($start_time)){echo $start_time; };?>" class="form-control">
        <label for="end_time">结束时间：</label>
        <input id="end_time" type="text" name="end_time" onFocus="WdatePicker({isShowClear:true,readOnly:false})" value="<?php if(isset($end_time) && !empty($end_time)){echo $end_time; };?>" class="form-control">
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
                    <th colspan="2">扫码号</th>
                    <th colspan="2">商家名称</th>
                    <th colspan="2">扫码时间</th>
                </tr>
                <?php if(empty($data)) {
                    echo '<tr><td colspan="24" style="text-align:center;">暂无记录</td></tr>';
                }else{
                    foreach($data as $item){
                        ?>
                        <tr>
                            <td colspan="2"><a href="<?= '/'.$item['id'];?>"><?= $item['id'];?></a></td>
                            <td colspan="2"><?= $item['bar_code'];?></td>
                            <td colspan="2"><?= $item['shop_name'];?></td>
                            <td colspan="2"><?= $item['add_time'];?></td>
                        </tr>
                    <?php } }?>

                </tbody>
            </table>
            <?= LinkPager::widget(['pagination' => $pages]); ?>
        </div>
    </div>

</legends>
