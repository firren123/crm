<?php
/**
 * 修改-view
 *
 * PHP Version 5
 *
 * @category  ADMIN
 * @package   BACKEND
 * @author    zhengyu <zhengyu@iyangpin.com>
 * @time      2015-04-01
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      zhengyu@iyangpin.com
 */
$this->title = "小区信息修改";
?>

<script src="/js/jquery-1.10.2.min.js"></script>
<script src="/js/zcommon.js"></script>

<style type="text/css">
    .zcss_table1 th{font-weight:bold;}
    .zcss_table1 th,.zcss_table1 td{border:1px solid #000000;padding:5px;}

    .zcss_td_left{width:120px;}
    .zcss_td_right{width:400px;}

    .zcss_class0401{margin:30px 0;}
    .zcss_class0401 a{padding:0 20px;}
</style>

<div>
    <ul class="breadcrumb">
        <li>
            <a href="/">首页</a>
        </li>
        <li class="active"><a href="/admin/plot/index" style="color: #286090;">小区管理</a></li>
        <li class="active">开通小区</li>
    </ul>
    <form action="/admin/plot/add" method="post" id="commentForm">
        <span style="color:red;">*</span>小区名：&nbsp;&nbsp;&nbsp;<input type="text" style="width:300px;height:30px;" name="plot[name]" value="" required><br/><br/><br/>
        <span style="color:red;">*</span>省名：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select id="province" style="width:300px;" name="plot[province]" required>
            <?php foreach($info as $k=>$v):?>
                <option value="<?php echo $v['id'];?>"><?php echo $v['name'];?></option>
            <?php endforeach;?>
        </select>
        <br/><br/><br/>
        <span style="color:red;">*</span>市名：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select id="city" style="width:300px;" name="plot[city]" required><?php if ($city){ ?>
                <?php foreach($city as $k=>$v):?>
                    <option value="<?php echo $v['id'];?>"><?php echo $v['name'];?></option>
                <?php endforeach;?>
            <?php }else{ ?> <option value="">请选择</option> <?php } ?></select>
        <br/><br/><br/>
        <span style="color:red;">*</span>区域：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" style="width:300px;height:30px;" name="plot[area]" value="" required><br/><br/><br/>
        <span style="color:red;">*</span>地址：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text"  style="width:300px;height:30px;" name="plot[address]" value="" required><br/><br/><br/>
        均价：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text"  style="width:300px;height:30px;" name="plot[average]" value="" ><br/><br/><br/>
        总面积：&nbsp;&nbsp;&nbsp;<input type="text"  style="width:300px;height:30px;" name="plot[total_area]" value="" ><br/><br/><br/>
        总户数：&nbsp;&nbsp;&nbsp;<input type="number"  style="width:300px;height:30px;" name="plot[total_number]" value="" ><br/><br/><br/>
        建筑年代：<input type="text" style="width:300px;height:30px;"  name="plot[buildings]" value="" ><br/><br/><br/>
        开发商：&nbsp;&nbsp;&nbsp;<input type="text" style="width:300px;height:30px;"  name="plot[developer]" value="" ><br/><br/><br/>
        容积率：&nbsp;&nbsp;&nbsp;<input type="text"  style="width:300px;height:30px;" name="plot[volume_rate]" value="" ><br/><br/><br/>
        物业公司：<input type="text"  style="width:300px;height:30px;" name="plot[property]" value="" ><br/><br/><br/>
        出租率：&nbsp;&nbsp;&nbsp;<input type="text" style="width:300px;height:30px;"  name="plot[letting_rate]" value="" ><br/><br/><br/>
        物业类型：<select  style="width:300px;" name="plot[property_type]" >
            <option value="1" selected="selected">暂无数据1</option>
            <option value="2">暂无数据2</option>
        </select>
        <br/><br/><br/>
        停车位：&nbsp;&nbsp;&nbsp;<input type="text"  style="width:300px;height:30px;" name="plot[parking]" value="" ><br/><br/><br/>
        物业费用：<input type="text" style="width:300px;height:30px;"  name="plot[property_fee]" value="" ><br/><br/><br/>
        绿化率：&nbsp;&nbsp;&nbsp;<input type="text" style="width:300px;height:30px;"  name="plot[greening_rate]" value="" ><br/><br/><br/>
        <span style="color:red;">*</span>状态：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="plot[status]" checked="checked" value="1" />启用
        <input type="radio" name="plot[status]" value="0" />禁止
        <br/><br/><br/>
        <dl>
            <dt><span class="required"></span>地图</dt>
            <dd>
                <!--获取坐标 start-->
                <style type="text/css">
                    #l-map{height:400px;width:600px;float:left;border:1px solid #bcbcbc;}
                    #r-result{height:400px;width:230px;float:right;}
                    img{max-width:none;}
                </style>
                <script type="text/javascript" src="http://api.map.baidu.com/api?v=1.4"></script>
                <div class="control-group">
                    <div class="controls">
                        <div class="demo">
                            <p style="height:40px;">
                                <span style="color:red;">*</span>请点击您的位置来获取坐标：<input class="txt boxtxt zjs_position" style="width:300px;height:30px;"   id="ShopForm_position_x" type="text" style="width:260px;" name="plot[coordinate]" value=""/ required>
                                <span style="color:red;">注：（X,Y）坐标用英文逗号隔开，逗号前面是X坐标，后面是Y坐标。</span>
                            </p>
                            <p>若找不到您的位置，可到 <a href="http://api.map.baidu.com/lbsapi/getpoint/index.html" target="_blank">百度拾取</a> 获取坐标，并把坐标复制粘贴到上面的文本框中。</p>

                            <div id="l-map"></div>
                            <div id="r-result"></div>
                        </div>

                        <div class="clearfix"></div>
                    </div>
                </div>
                <script type="text/javascript">
                    var x=<?php if(!isset($arr_shop_info['position_x']) || empty($arr_shop_info['position_x'])){echo '116.451412';}else{echo $arr_shop_info['position_x'];} ?>;
                    var y=<?php if(!isset($arr_shop_info['position_y']) || empty($arr_shop_info['position_y'])){echo '39.907408';}else{echo $arr_shop_info['position_y'];} ?>;

                    // 百度地图API功能
                    var map = new BMap.Map("l-map"); // 创建Map实例
                    var point = new BMap.Point(x, y);
                    map.centerAndZoom(point, 18);
                    var mk = new BMap.Marker(point);
                    map.addOverlay(mk);//加标记
                    mk.enableDragging(); //marker可拖拽
                    mk.addEventListener("dragend", function(e){
                        document.getElementById("ShopForm_position_x").value=e.point.lng + "," + e.point.lat;
                    });
                    map.enableScrollWheelZoom();

                    var local = new BMap.LocalSearch("全国", {
                        renderOptions: {
                            map: map,
                            panel : "r-result",
                            autoViewport: true,
                            selectFirstResult: false
                        }
                    });
                    map.addEventListener("click",function(e){
                        document.getElementById("ShopForm_position_x").value=e.point.lng + "," + e.point.lat;
                    });
                </script>
                <!--获取坐标 end-->

                <div style="clear:both;"></div>
            </dd>
        </dl>
        <input type="hidden" name="city_info_id" id="hidden" value="<?php echo $city_info_id; ?>" />
        <input type="hidden"  name="_csrf" value="<?= \Yii::$app->getRequest()->getCsrfToken(); ?>" />
        <br/><br/><br/>
        <a href="<?= '/admin/plot/index?city_name='.$city_info_id;?>" class="btn btn-primary" style="margin: 0px 20px">返回列表</a>
        <input type="submit" class="btn btn-primary" value="确认添加" />
    </form>

</div>
<input type="hidden" name="city_info_id" id="hidden" value="<?php echo $city_info_id; ?>" />
<?= $this->registerJsFile("@web/js/jquery-1.10.2.min.js");?>
<?= $this->registerJsFile("@web/js/jquery.validate.js");?>
<script type="text/javascript">
    $("#province").change(function(){
        var info = $(this).val();
        $.ajax({
            type: "GET",
            url: "/admin/plot/city",
            data: "pid="+info,
            success: function(data){
                var data2=JSON.parse(data);  //JSON.parse 将json字符串转换为对象
                var len=data2.length;
                $("#city").empty();
                var html_option="";
                for(var i=0;i<len;i++)
                {
                    html_option+='<option value="'+data2[i]['id']+'">'+data2[i]['name']+'</option>';
                    console.log(data2.i);
                }
                $("#city").append(html_option);
            }
        });
    });
    $("#commentForm").validate();
</script>
