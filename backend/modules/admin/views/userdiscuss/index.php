<?php
/**
 * 简介1
 *
 * PHP Version 5
 * 评论列表模板文件
 *
 * @category  PHP
 * @package   Admin
 * @filename  index.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/3/31 上午10:12
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */
use yii\helpers\Html;
use yii\widgets\LinkPager;
$this->title = '评论审核列表';
?>
<?= $this->registerJsFile("@web/js/jquery-1.10.2.min.js");?>
<?= $this->registerJsFile("@web/js/shoporder.js");?>
<style>
    .xiaoshou{
        cursor:pointer;
    }
</style>
<div class="tab-content">

    <div class="row-fluid">
        <table class="table table-bordered table-hover">
            <tr>

                <td colspan="8"><form action="/admin/userdiscuss" method="get">
                        用户名：<input type="text" id="user_name" name="user_name"
                                   value="<?= $userName;?>"
                                   class="findformtxt" />

                            <input type="submit" id="search" value="搜索" class="findformbtn" />
                        </p>
                    </form></td>
            </tr>
            <tr>
                <th><input type="checkbox" id="all"/>全选</th><th>订单号</th><th>用户名</th><th>商家名</th><th>评论内容</th><th>评论时间</th><th>状态</th><th>操作</th>
            </tr>
            <?php if(!empty($list) || $list != null){ ?>
                <form action="/admin/userdiscuss/edit" method="post">
                    <?php foreach($list as $k => $v){ ?>
                        <tr>
                            <td><input type="checkbox" name="id[]" id="o_id" value="<?= html::encode($v['id'])?>"/></td>
                            <td><?= html::encode($v['order_sn'])?></td>
                            <td><?= html::encode($v['user_name']); ?></td>
                            <td>
                                <?= html::encode($v['shop_name']);?>
                            </td>

                            <td>
                                <?= html::encode($v['content']);?>
                            </td>
                            <td>
                                <?= html::encode($v['add_time']);?>
                            </td>
                            <td>
                                <?php
                                switch ($v['status']){
                                    case 0:
                                        echo "未审核";
                                        break;
                                    case 1:
                                        echo "通过";
                                        break;
                                    case 2:
                                        echo "不通过";
                                        break;
                                    default:
                                        echo "未定义";
                                }
                                ?>
                            </td>
                            <td>
                                [<a class="xiaoshou" onclick="successBut(<?php echo $v['id'];?>,1);">通过</a>]|
                                [<a class="xiaoshou" onclick="successBut(<?php echo $v['id'];?>,2);">不通过</a>]
                            </td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td colspan="3">
                            <input type="radio" value="1" name="type"/>通过
                            <input type="radio" value="2" name="type"/>不通过
                        </td>
                        <td colspan="5">

                            <input type="submit" value="提交"/>
                        </td>
                    </tr>
                </form>
            <?php } ?>
            <?= LinkPager::widget(['pagination' => $pages]); ?>
        </table>
    </div>
</div>
