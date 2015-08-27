<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
$this->title = "资讯分类列表";
?>
<?= $this->registerJsFile("@web/js/jquery-1.10.2.min.js");?>
<?= $this->registerJsFile("@web/js/news.js");?>

<div class="tab-content">
        <div class="panel panel-default">
            <div class="panel-heading">资讯分类列表</div>
            <div class="panel-body">
                <div class="col-lg-4" style="float: left;padding-left:0px;">
                    <div class="input-group">
                        <span class="input-group-addon" id="sizing-addon1">分类名称</span>
                        <input type="text" id="cate_name" class="form-control" placeholder="分类名称">
                    </div>
                </div>
                <div class="col-lg-3" style="float: left;padding-left:0px;">
                    <div class="input-group">
                        <span class="input-group-addon" id="sizing-addon1">类别</span>
                        <select style="width: 150px;" id="parent_id" class="form-control category_option">
                            <option value="0">顶级分类</option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-4" style="float: left;padding-left:0px;">
                    <input type="hidden" id="token" value="<?php echo Yii::$app->getRequest()->getCsrfToken(); ?>" />
                    <input type="hidden" id="base_url" value="<?php echo Yii::$app->params['baseUrl']; ?>">
                    <input type="hidden" id="act" value="add-category">
                    <a class="btn btn-primary submit-news add-category_1" data_pid="0" href="javascript:;">添加分类</a>
                    <a class="btn btn-primary submit-news sub_loading_1" style="display: none;" href="javascript:;">提交中...</a>
                </div>
            </div>
        <table class="table table-bordered table-hover" style="font-size: 14px;">
            <?php if(empty($data['list'])){
                ?>
                <thead>
                <tr>
                    <th class="text-center" colspan="4">暂无资讯分类。</th>
                </tr>
                </thead>
            <?php
            }else{
            ?>
                <thead>
            <tr>
                <th class="text-center">ID</th>
                <th class="text-center">分类名称</th>
                <th class="text-center">创建时间</th>
                <th class="text-center">操作</th>
            </tr>
            </thead>

            <tbody>
                <?php foreach($data['list'] as $v): ?>
                    <tr>
                        <td class="text-center" style="width: 50px;"><?php echo !empty($v['id']) ? $v['id'] : '0' ;?></td>
                        <td style="text-align: left"><?php echo !empty($v['fullname']) ? $v['fullname'] : '--' ;?></td>
                        <td class="text-center" style="width: 150px;"><?php echo !empty($v['create_time']) ? $v['create_time'] : '--' ;?></td>
                        <td class="text-center" style="width: 120px;">
                            <a href="javascript:;" data_id="<?php echo !empty($v['id']) ? $v['id'] : '0' ;?>" title="添加子类" class="add_son"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a>&nbsp;&nbsp; |
                            &nbsp;&nbsp;<a href="/admin/news/edit-category?id=<?php echo !empty($v['id']) ? $v['id'] : '0' ;?>" title="编辑" class="edit"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>&nbsp;&nbsp; |
                            &nbsp;&nbsp;<a href="javascript:;" data_id="<?php echo !empty($v['id']) ? $v['id'] : '0' ;?>" title="删除" class="del_category"><span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></a>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
            <?php
            }?>
        </table>
    </div>
</div>