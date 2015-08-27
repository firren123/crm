<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;
use backend\models\i500m\NewsCategory;
$this->title = "资讯列表";
?>
<?= $this->registerJsFile("@web/js/jquery-1.10.2.min.js");?>
<?= $this->registerJsFile("@web/js/news.js");?>

<div class="tab-content">
        <div class="panel panel-default">
            <div class="panel-heading">资讯列表</div>
            <div class="panel-body">
                <a class="btn btn-primary" href="/admin/news/add">发布资讯</a>
                <div class="col-lg-4" style="float: right;">
                    <div class="input-group">
                          <input type="text" id="title" value="<?php echo (!empty($data['title'])) ? $data['title'] : '' ;?>" class="form-control" placeholder="文章标题">
                          <span class="input-group-btn">
                            <button class="btn btn-default btn_search" type="button">搜索</button>
                          </span>
                    </div>
                </div>
                <div class="col-lg-3" style="float: right;">
                    <div class="input-group">
                        <span class="input-group-addon" id="sizing-addon1">类别</span>
                        <select style="width: 150px;" id="category_id" class="form-control category_option">
                            <option value="0">不限</option>
                        </select>
                    </div>
                </div>
            </div>
        <table class="table table-bordered table-hover" style="font-size: 14px;">
            <?php if(empty($data['list'])){
                ?>
                <thead>
                <tr>
                    <th class="text-center" colspan="6">暂无资讯，马上去<a href="/admin/news/add">发布</a>。</th>
                </tr>
                </thead>
                <?php
            }else{
                ?>
                <thead>
            <tr>
                <th class="text-center">ID</th>
                <th class="text-center">标题</th>
                <th class="text-center">作者</th>
                <th class="text-center">分类</th>
                <th class="text-center">状态</th>
                <th class="text-center">创建时间</th>
                <th class="text-center">操作</th>
            </tr>
            </thead>

            <tbody>
            <?php foreach($data['list'] as $v): ?>
                <tr>
                    <td class="text-center" style="width: 50px;"><?php echo !empty($v['id']) ? $v['id'] : '0' ;?></td>
                    <td style="text-align: left;"><?php echo !empty($v['title']) ? $v['title'] : '--' ;?></td>
                    <td class="text-center" style="width: 100px;"><?php echo !empty($v['author']) ? $v['author'] : '--' ;?></td>
                    <td class="text-center" style="width: 100px;">
                        <?php echo !empty($v['category_name']) ? $v['category_name'] : '--' ;?>
                    </td>
                    <td class="text-center" style="width: 50px;">
                        <?php
                        $status = $v['status'];
                        if (!empty($status)) {
                            if ($status == '1') {
                                echo '可用';
                            } elseif ($status == '2') {
                                echo '禁用';
                            }
                        }
                        ?>
                    </td>
                    <td class="text-center" style="width: 150px;"><?php echo !empty($v['create_time']) ? $v['create_time'] : '--' ;?></td>
                    <td class="text-center" style="width: 100px;">
                        <a href="/admin/news/edit?id=<?php echo !empty($v['id']) ? $v['id'] : '0' ;?>" title="编辑"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>&nbsp;&nbsp; |
                        &nbsp;&nbsp;<a href="javascript:;" title="删除" class="del" data_id="<?php echo !empty($v['id']) ? $v['id'] : '0' ;?>"><span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span></a>
                    </td>
                </tr>
            <?php endforeach ?>
            </tbody>
                <?php
            }?>
        </table>
            <div class="pagination pull-right">
                <?= LinkPager::widget(['pagination' => $pages]); ?>
            </div>
    </div>
</div>
<input type="hidden" id="base_url" value="<?php echo Yii::$app->params['baseUrl']; ?>">
<input type="hidden" id="act" value="news_list">
<input type="hidden" id="info_category_id" value="<?php echo !empty($data['category_id']) ? $data['category_id'] : '0' ;?>">