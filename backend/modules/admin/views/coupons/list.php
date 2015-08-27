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
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/3/24 下午2:18
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */
use linslin\yii2;
use yii\helpers\Html;
?>
<ul class="breadcrumb">
    <li>
        <a href="/">首页</a>
    </li>
    <li class="active"><a href="/admin/couponstype">优惠券管理</a></li>
    <li class="active">分发给用户</li>
</ul>
<form id="search-form" class="well form-inline" action="/admin/coupons/addbyuser?id=<?= $id;?>" method="post">
    <label for="SearchForm_username">用户名：</label>
    <textarea cols="50" rows="5" name="username"><?= $username;?></textarea>
    用户名之间换行相隔
    <button id="yw1" class="btn btn-primary" name="yt0" type="submit">搜索</button>
</form>
<div class="tab-content">
    <div class="row-fluid">
        <?php if(!empty($list)){?>
        <form id="search-form" class="well form-inline" action="/admin/coupons/addbyuser?id=<?= $id;?>" method="post">
        <table class="table table-bordered table-hover">
            <tbody>

            <tr>
                <th colspan="2">类别ID</th>
                <th colspan="2">用户名称</th>
                <th colspan="2">用户手机号</th>
            </tr>
                <?php foreach($list as $data){?>
                    <tr>
                        <td colspan="2"><input type="checkbox" id="name/<?php echo $data['id']?>" name="ids[]" class="classifyname" value="<?= $data['id']?>"/></td>
                        <td colspan="2"><?php echo $data['username'];?></td>
                        <td colspan="2"><?php echo $data['mobile'];?></td>
                    </tr>
                <?php } ?>

            </tbody>
        </table>
        <nav class="footnav">
            <p class="totalmain fl" style="float: left;margin: 0 0 10px;width: 100px;"><span class="iconBox fl"></span>
     <span class="checkall fl">
         <input type='checkbox' onclick=selAllbyName('ids[]',this.checked) id="checkall" />全选
     </span>
            </p>
            <input type="submit" class="btn btn-primary" value="分发" onclick="return check()" name="submit"/>
        </nav>
                </form>
        <?php }?>
    </div></div>

<script type="text/javascript">
    function selAllbyName(objName,checked){
        var obj=document.getElementsByName(objName);
        for(var i=0;i<obj.length;i++){
            var elm=obj[i];
            elm.checked=checked;
        }

        return true;
    }
    //表单验证
    function check(){
        var ids = document.getElementsByName("ids[]");
        var flag = false ;
        for(var i=0;i<ids.length;i++){
            if(ids[i].checked){
                flag = true ;
                break ;
            }
        }
        if(!flag){
            alert("请最少选择一项！");
            return false ;
        }
    }
</script>