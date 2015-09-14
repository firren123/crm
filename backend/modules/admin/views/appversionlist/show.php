<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/6/4
 * Time: 17:37
 */
$this->title = 'APP版本信息';
?>
<style>
    table {
        margin-left: 150px;;
    }
    td{
        height: 34px;
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.42857;
        color: #555;
        background-color: #FFF;
        background-image: none;
        border: 1px solid #CCC;
        border-radius: 4px;
        box-shadow: 0px 1px 1px rgba(0, 0, 0, 0.075) inset;
        transition: border-color 0.15s ease-in-out 0s, box-shadow 0.15s ease-in-out 0s;
    }

</style>
<legends  style="fond-size:12px;">
    <ul class="breadcrumb">
        <li>
            <a href="/">首页</a>
        </li>
        <li><a href="/admin/appversionlist/index">app版本列表</a></li>
        <li class="active">app版本信息</li>
    </ul>
    <div class="tab-content">
        <div class="row-fluid">
        </div>
    </div>
</legends>
<table>
    <tr >
        <td >版本名称</td>
        <td ><?= $AppVersionList_result['name'] ?></td>
    </tr>
    <tr>
        <td>所属项目</td>
        <td>
        <?php
            if($AppVersionList_result['subordinate_item']=='0'){
                echo "用户APP";
            }
            if($AppVersionList_result['subordinate_item']=='1'){
                echo "商家APP";
            }
            if($AppVersionList_result['subordinate_item']=='2'){
                echo "供应商APP";
            }
            if($AppVersionList_result['subordinate_item']=='3'){
                echo "店小二APP";
            }
            ?>
        </td>
    </tr>
    <tr>
        <td>主版本号</td>
        <td><?= $AppVersionList_result['major'] ?></td>
    </tr>
    <tr>
        <td>副版本号</td>
        <td><?= $AppVersionList_result['minor'] ?></td>
    </tr>
    <tr>
        <td>操作系统</td>
        <td><?= $AppVersionList_result['type']==0? "安卓":"IOS"; ?></td>
    </tr>
    <tr>
        <td>下载地址</td>
        <td><?= $AppVersionList_result['url'] ?></td>
    </tr>
    <?php
    if(!empty($app_channel_result) && $AppVersionList_result['type']=='0'){
        foreach($app_channel_result as $k=>$v){
            if($v['type']=='0'){
                ?>
                <tr>
                    <td>百度渠道</td>
                    <td><?= $v['url'] ?></td>
                </tr>
                <?php
            }
            if($v['type']=='1'){
                ?>
                <tr>
                    <td>360渠道</td>
                    <td><?= $v['url'] ?></td>
                </tr>
            <?php
            }
        }
        ?>
    <?php
    }
    ?>
    <tr>
        <td>升级提示</td>
        <td><?= $AppVersionList_result['explain'] ?></td>
    </tr>
    <tr>
        <td>有效性</td>
        <td>
            <?php
            if($AppVersionList_result['status']=='0'){echo "删除";}
            if($AppVersionList_result['status']=='1'){echo "有效";}
            if($AppVersionList_result['status']=='2'){echo "禁用";}
            ?>
        </td>
    </tr>
    <tr>
        <td>更新提示时间</td>
        <td><?= $AppVersionList_result['update_prompt'] ?></td>
    </tr>
    <tr>
        <td>是否强制更新</td>
        <td><?php if($AppVersionList_result['is_forced_to_update']==1) {echo "需要强制更新";}else{echo "不需要强制更新";}?></td>
    </tr>
</table>
<div class="form-actions">
    <a class="btn btn-primary" href="edit-one-url?id=<?= $AppVersionList_result['id'] ?>">修改</a>
    <a class="btn cancelBtn" href="javascript:history.go(-1);">取消</a>
</div>