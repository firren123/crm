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
    <legend>小区管理</legend>
    <a id="yw0" class="btn btn-primary" href="<?= '/admin/plot/add?city_id='.$city_id;?>" style="margin-bottom:20px;">添加小区</a>
    <form action="/admin/plot/index" method="get">
        <?php if ($city){ ?>城市名称：<select name="city_name" id="name">
            <?php foreach($city as $k=>$v):?>
                <option class="city" value="<?php echo $v['city'];?>" <?php if($city_id == $v['city']){echo "selected";}?>><?php echo $v['city_name'];?></option>
            <?php endforeach;?>
        </select>
        <?php } ?>
        小区名称：<input type="text" name="area" class="xiaoqu">
        <input type="submit"  class="btn btn-primary" value="搜索" id="sousuo">
        <input type="hidden"  name="_csrf" value="<?= \Yii::$app->getRequest()->getCsrfToken(); ?>" />
    </form>
    <div class="tab-content">
        <div class="row-fluid">
            <table class="table table-bordered table-hover">
                <tbody>
                <tr>
                    <th colspan="2">ID</th>
                    <th colspan="2">小区名称</th>
                    <th colspan="2">区域(板块)</th>
                    <th colspan="2">地址</th>
                    <th colspan="2">店铺数量</th>
                    <th colspan="4">操作</th>    <!--22-->
                </tr>
                <?php if(empty($list)) {
                    echo '<tr><td colspan="14" style="text-align:center;">暂无记录</td></tr>';
                    }else{
                    foreach($list as $item){
                ?>
                        <tr>
                            <td colspan="2"><?= $item['id'];?></td>
                            <td colspan="2"><?= $item['name'];?></td>
                            <td colspan="2"><?= $item['area'];?></td>                       <!--area-->
                            <td colspan="2"><?= $item['address'];?></td>
                            <td colspan="2"><?= $item['shop_community'];?></td>
                                <td colspan="4"> <a href="<?= '/admin/plot/view?id='.$item['id'].'&city_id='.$city_id;?>">编辑小区信息</a> |  <a href="<?= '/admin/plot/delete?id='.$item['id'].'&city_id='.$city_id;?>" onclick="return confirm('确定要删除该分类吗？')">删除小区</a> |  <a href="<?= '/admin/plot/look?id='.$item['id'].'&city_id='.$city_id;?>">查看详情</a></td>

                        </tr>
                <?php } }?>

                </tbody>
            </table>
            <?php echo LinkPager::widget(['pagination' => $pages]); ?>
        </div>
    </div>
</legends>
<?= $this->registerJsFile("@web/js/jquery-1.10.2.min.js");?>
<?= $this->registerJsFile("@web/js/layer/layer.js");?>
<script>
    $(function(){
        $("#sousuo").click(function(){
            var info = $(".xiaoqu").val();
            if(info){
                if(!(/^[\u4e00-\u9fa5]+$/.test(info))){
                    layer.msg('小区名格式不正确，请输入正确小区名！');
                    return false;
                }
            }
        })
    })
</script>

