<?php
$this->title = "活动列表";
use yii\widgets\LinkPager;
?>
<legends  style="fond-size:12px;">
    <legend>活动管理</legend>
</legends>
<a id="yw0" class="btn btn-primary" href="/goods/activity/add">添加活动</a>
<?= $this->registerJsFile("@web/js/jquery-1.10.2.min.js");?>
<script src="/js/My97DatePicker/WdatePicker.js"></script>

<div class="wide form">
    <form id="search-form" class="well form-inline" action="/goods/activity/list" method="get">


        <label for="id">活动ID：</label>
        <input id="id" type="text" name="id" value="<?= $id;?>" class="form-control">
        <label for="name">活动名称：</label>
        <input id="name" type="text" name="name" value="<?= $name;?>" class="form-control">
        <br /><br />
        <label for="type">类型：</label>
        <select id="type_id" name="type_id" class="form-control">
            <option  value="0">全部</option>
            <?php foreach($activity_type as $k => $v){ ?>
                <option <?php if($k == $type_id){echo " selected ";}?> value="<?= $k; ?>"><?= $v; ?></option>
            <?php } ?>

        </select>
        <label for="province">地区：</label>
        <select id="province_id" name="province_id" class="form-control">
            <option  value="0">全部</option>
            <?php foreach($province_list as $k => $v){ ?>
                <option <?php if($k == $province_id){echo " selected ";}?> value="<?= $k; ?>"><?= $v; ?></option>
            <?php } ?>

        </select>
        <button id="sub" class="btn btn-primary" name="yt0" type="submit">搜索</button>
    </form>
</div>

<div class="tab-content">
    <p style="padding-bottom:20px;"><span style="float:right;">共计&nbsp;<span style="color: red;"><?php echo $count;?></span>&nbsp;条记录</span></p>
    <div class="row-fluid">
        <table table align='center' class="table table-bordered table-hover">
            <tr>
                <th>活动id</th><th>活动名称</th><th>活动类型</th><th>排序</th><th>开始日期</th><th>结束日期</th><th>当前状态</th><th>地区</th><th>操作</th>
            </tr>
            <?php
            if(empty($res)){
                echo '<tr><td colspan="9" style="text-align:center;">暂无记录</td></tr>';
            }else{
                foreach($res as $k => $v){?>
                    <tr>
                        <td><?= $v['id'];?></td>
                        <td><?= $v['name'];?></td>
                        <td><?php if(isset($v['type'])){
                                switch($v['type']){
                                    case 1 : echo '限购'; break;
                                    case 2 : echo '买赠'; break;
                                    case 3 : echo '加价购'; break;
                                }
                            }?></td>
                        <td><?= $v['sort'];?></td>
                        <td><?= $v['start_time'];?></td>
                        <td><?= $v['end_time'];?></td>
                        <td><?= $v['status'];?></td>
                        <td><?= $v['province_name'];?></td>
                        <td>
                            <a style="cursor:pointer" href="/goods/activity/view?id=<?= $v['id'];?>">查看</a> |
                            <a style="cursor:pointer" href="/goods/activity/edit?id=<?= $v['id'];?>">编辑</a> |
                            <a class="cargoState" style="cursor: pointer;" onclick="upstatusorder('<?= $v['id']; ?>')">停止</a> |
                            <a href="/goods/activity/activity-view?id=<?= $v['id'];?>" style="cursor: pointer";>参与商铺</a>
                        </td>

                    </tr>
                <?php }?>
            <?php }?>
        </table>
        <div class="pagination pull-left">
            <?= LinkPager::widget(['pagination' => $pages]); ?>
        </div>
    </div>
</div>
<script>
    function upstatusorder(id) {
        var d = dialog({
            title: '请输入活动停止原因:',
            content: "<dl style='margin-top: 10px'>" +
            "<textarea autofocus style='width:500px;' name='stop_remark' value='' />" +
            "</p><dd></dl>",
            okValue: '确定停止',
            ok: function () {
                var v = $("textarea[name=stop_remark]").val();
                $.ajax({
                    type: "GET",
                    url:'/goods/activity/edit-status',
                    data:'id='+id+'&stop_remark='+v,
//                    dataType: "json",
                    success: function(data) {
                        if (data == 1) {
                            var d = dialog({
                                title: '提示',
                                content: '<p style="padding: 0 50px">提交成功</p>',
                                ok: function () {
                                    window.location.href = '/goods/activity/list';
                                }

                            });
                            d.showModal();
                            //gf.alert();
                        } else if(data==2){
                            gf.alert('请填写原因');
                            return false;
                        } else {
                            gf.alert('提交失败');
                            return false;
                        }
                    }
                });
            },
            cancelValue: '取消',
            cancel: function () {}
        });
        d.showModal();
    }
</script>


