<?php
/**
 * 一行的文件介绍
 *
 * PHP Version 5
 * 可写多行的文件相关说明
 *
 * @category  I500M
 * @package   Member
 * @author    renyineng <renyineng@iyangpin.com>
 * @time      15/4/1 下午1:45 
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      renyineng@iyangpin.com
 */
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Button;
use yii\bootstrap\Nav;
$this->title = "关联小区/写字楼";
?>
<style type="text/css">
    .box select{ width:300px;}
    .box select,.box p{float: left;}
    .addBtn,.deleteBtn{display: block; margin: 20px;}
</style>

<legends  style="fond-size:12px;">
    <ul class="breadcrumb">
        <li>
            <a href="/">首页</a>
        </li>

        <li>
            <a href="/shop/shop/index">商家列表</a>
        </li>
        <li>
            <?=$info['shop_name'];?>
        </li>
    </ul>
    <?php //$this->render('_search');?>

    <div class="tab-content">





        <div class="row-fluid">

            <h3>设置服务小区</h3>
            <div class="box">
                <?php if(!empty($community)):?>
                    <select size="20" class="fl" multiple="multiple" id="select1">
                        <?php foreach($community as $k=>$v):?>

                            <?php if(!array_key_exists($v['community_id'], $have)):?>
                                <option value="<?=$v['community_id'];?>"><?=$v['community_name'];?></option>
                            <?php endif;?>

                        <?php endforeach;?>
                    </select>
                <?php endif;?>
                <p class="fl">
                    <a href="javascript:void(0)" class="addBtn" id="add">添加</a>
                    <a href="javascript:void(0)" class="deleteBtn" id="delete">删除</a>
                </p>

                <select size="10" class="fl" multiple="multiple" id="select2"">
                <?php if(!empty($have)):?>
                    <?php foreach($have as $k=>$v):?>

                        <!--                <option value="--><?//=$k;?><!--">--><?//=ArrayHelper::getValue($item,$k.'.community_name','');?><!--</option>-->
                        <option value="<?=$v['id'];?>"><?=$v['name'];?></option>
                    <?php endforeach;?>
                <?php endif;?>

                </select>
            </div>

        </div>
    </div>

</legends>



    <script>
        var community = {
            add:function(){
                var url = $("#base_url").val() +'shop/shopcommunity/do-community';
                var options1 = $("#select1 option:selected");
                var options2 = $("#select2 option");
                var length_1 = options1.length;
                var length_2 = options2.length;
                if (length_2 >=3) {
                    alert('您最多设置3个');
                    return;
                }
                var total = length_1 + length_2;
                if (total > 3) {
                    alert('您最多设置3个');
                    return;
                }
                var shop_id = $("#shop_id").val();
                // alert(options2.length);
                options1.each(function(){
                    //alert($(this).val());
                    var obj = $(this);
                    $.get(url,{community_id:obj.val(),shop_id:shop_id},function(result){
                        //alert(result.code);
                        if (result.code == 'ok') {
                            // alert(2);
                            obj.appendTo('#select2');
                        } else {
                            alert(result.message);
                        }
                    },'json')

                })

                //options1.appendTo('#select2');
            },
            delete_community:function(){
                var url = $("#base_url").val() +'shop/shopcommunity/del-relation';
                var shop_id = $("#shop_id").val();
                var options2 = $("#select2 option:selected");

                // alert(options2.length);
                options2.each(function(){
                    var obj = $(this);
                    //alert($(this).val());
                    $.get(url,{community_id:obj.val(),shop_id:shop_id},function(result){
                        if (result.code == 'ok') {
                            obj.appendTo('#select1');
                        } else {
                            alert(result.message);
                        }
                    },'json')

                })

                //options1.appendTo('#select2');
            }
        }
        $("#add").click(function(){
            community.add();
        })

        $("#delete").click(function(){
//        var options = $("#select2 option:selected");
//        options.appendTo('#select1');
            community.delete_community();
        })
    </script>
    <input type="hidden" id="shop_id" value="<?php echo $info['id']; ?>" />

<?= $this->registerJsFile("@web/js/common.js");?>