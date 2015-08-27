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
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\helpers\ArrayHelper;
$this->title = "关联小区/写字楼";
?>
    <script src="/js/jquery-1.10.2.min.js"></script>
    <legends  style="fond-size:12px;">
        <?php //$this->render('_search');?>

        <div class="tab-content">

            <?php
            echo Nav::widget([
                'items' => [
                    [
                        'label' => '设置小区',
                        'url' => ['shopcommunity/relation?id='.$shop_id],
                        'linkOptions' => ['class'=>'active'],
                    ],

                    [
                        'label' => '查看小区',
                        'url'   => ['shopcommunity/show?id='.$shop_id],
                    ],

                ],
                'options' => ['class' =>'nav-tab nav-pills'], // set this to nav-tab to get tab-styled navigation
            ]);
            ?>



            <div class="row-fluid">
                <!--          <form action="admin/relation" method="post" >-->

                <table class="table table-bordered table-hover">
                    <tbody>
                    <tr>

                        <th colspan="2">ID</th>
                        <th colspan="2">商家ID</th>
                        <th colspan="2">商家</th>
                        <th colspan="2">小区</th>

                        <th colspan="4">操作</th>
                    </tr>
                    <?php if (!empty($list)) :?>
                        <?php foreach($list as $v):?>
                            <tr>

                                <td colspan="2"><?=$v['id']; ?></td>
                                <td colspan="2"><?=$v['shop_id']; ?></td>
                                <td colspan="2"><?=ArrayHelper::getValue($info,'shop_name',''); ?></td>
                                <td colspan="2"><?=ArrayHelper::getValue($community,$v['community_id'].'.name','未知'); ?></td>


                                <td colspan="3">
                                    <a href="<?php echo Url::to(['/shop/shopcommunity/del-relation','id'=>$v['id']]);?>" onclick="return confirm('您确定要删除吗？')"  >删除</a>

                                </td>
                            </tr>
                        <?php endforeach;?>
                    <?php endif;?>


                    </tbody>
                </table>





            </div>
        </div>

    </legends>





<?= $this->registerJsFile("@web/js/common.js");?>