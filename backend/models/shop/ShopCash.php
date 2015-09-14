<?php
/**
 * 商家提款申请
 *
 * PHP Version 5
 *
 * @category  CRM
 * @package   ShopCash.php
 * @author    liuwei <liuwei@iyangpin.com>
 * @time      2015/5/28 0028 下午 5:10
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      liuwei@iyangpin.com
 */
namespace backend\models\shop;
/**
 * ShopCash
 *
 * @category CRM
 * @package  ShopCash
 * @author   liuwei <liuwei@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     liuwei@iyangpin.com
 */
class ShopCash extends ShopBase
{
    /**
     * 数据表
     *
     * @return string
     */
    public static function tableName()
    {
        return '{{%shop_cash}}';
    }

    /**
     * 已导出
     * @param string $arrId id
     * @return bool
     */
    public function status($arrId = '')
    {
        $listId = $this->findOne($arrId);
        if (!empty($listId)) {
            $listId->export_status = 2;
            $result = $listId->save();
            if ($result != 1) {
                return false;
            }
        }
        return true;
    }
}
