<?php
/* @var $this SiteController */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
?>
<?php
/* @var $this OrderController */
/* @var $dataProvider CActiveDataProvider */
?>
<legends  style="fond-size:12px;">
    <legend>优惠券列表</legend>

    <div class="tab-content">
        <div class="row-fluid">
            <table class="table table-bordered table-hover">
                <tbody>
                <tr>
                    <th colspan="2">ID</th>
                    <th colspan="2">类别名称</th>
                    <th colspan="2">发送方式</th>
                    <th colspan="2">现金劵面额</th>
                    <th colspan="2">最小订单金额</th>

                    <th colspan="2">是否可用</th>




                </tr>
                <?php if(empty($data)) {
                    echo '<tr><td colspan="24" style="text-align:center;">暂无记录</td></tr>';
                }else{
                    foreach($data as $item){
                        ?>
                        <tr>
                            <td colspan="2"><?= $item['id'];?></td>
                            <td colspan="2"><?= $item['type_name'];?></td>

                            <td colspan="2"><?= $item['par_value'];?></td>
                            <td colspan="2"><?= $item['min_amount'];?></td>
                            <td colspan="2"><?= $item['consumer_points'];?></td>



                            <td colspan="2">

                                <?php if($item['status']==1){
                                    echo '已经使用';
                                }elseif($item['status']==2) {
                                    echo '过期';
                                }elseif($item['status']==0){
                                    echo '未使用';
                                }else{

                                }
                                ;?>
                            </td>


                        </tr>
                    <?php } }?>

                </tbody>
            </table>

        </div>
    </div>

</legends>