<?php
/**
 * 简介1
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Admin
 * @filename  edit_shop.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/8/19 上午11:07
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */

use yii\widgets\LinkPager;

?>
<legends style="fond-size:12px;">
    <div class="tab-content">
        <div class="row-fluid">
            <table class="table table-bordered table-hover">
                <tbody>
                <tr>
                    <th colspan="2">商家ID</th>
                    <th colspan="2">商家名称</th>
                    <th colspan="4">操作</th>
                    <!--22-->
                </tr>
                <?php
                if (empty($shop_list)) {

                } else {
                    foreach ($shop_list as $item) {
                        ?>
                        <tr>
                            <td colspan="2" class="shop_id"><?= $item['id']; ?></td>
                            <td colspan="2"><?= $item['shop_name']; ?></td>
                            <td colspan="4"><a href="#" class="add_shop">选择</a></td>
                        </tr>
                    <?php }
                }
                ?>
                <tr>
                    <td colspan="2" class="shop_id">1</td>
                    <td colspan="2">i500m公司配送</td>
                    <td colspan="4"><a href="#" class="add_shop">选择</a></td>
                </tr>
                </tbody>
            </table>

        </div>

    </div>
</legends>
<input type="hidden" id="_csrf" name="YII_CSRF_TOKEN" value="<?php echo \Yii::$app->getRequest()->getCsrfToken(); ?>" />
<script style="text/javascript" src="/js/userorder/userOrder.js"></script>
<script style="text/javascript">
    $(function(){
       $(".add_shop").click(function(){
           userOrder.editShop(this);
       });
    });
</script>