<?php
/**
 * 简介1
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   Crm
 * @filename  ShopProduct.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/4/29 下午3:16
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\models\shop;

/**
 * Class ShopProduct
 * @category  PHP
 * @package   ShopProduct
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class ShopProduct extends ShopBase
{
    /**
     * 简介：连接表
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public static function tableName()
    {
        return '{{%shop_products}}';
    }

    /**
     * 简介：减少库存
     * @author  lichenjun@iyangpin.com。
     * @param int $shop_id 商家ID
     * @param int $pid     商品ID
     * @param int $num     购买数量
     * @return int
     */
    public function reduceProductStock($shop_id, $pid, $num = 0)
    {
        $flag = 0;
        $list = $this->find()->where(['product_id' => $pid, 'shop_id' => $shop_id])->one();
        if ($list) {
            $list->product_number = $list->product_number - $num;
            $ret = $list->save();
            return $ret;
        }
        return $flag;
    }

    /**
     * 简介：加库存
     * @author  lichenjun@iyangpin.com。
     * @param int $shop_id 商家ID
     * @param int $pid     商品ID
     * @param int $num     购买数量
     * @return int
     */
    public function addProductStock($shop_id, $pid, $num = 0)
    {
        $flag = 0;
        $list = $this->find()->where(['product_id' => $pid, 'shop_id' => $shop_id])->one();
        if ($list) {
            $list->product_number = $list->product_number + $num;
            $ret = $list->save();
            return $ret;
        }
        return $flag;
    }

    /**
     * 简介：添加销量
     * @param int $shop_id 商家ID
     * @param int $pid     产品ID
     * @param int $num     数量
     * @return bool|int
     */
    public function Up_sales_volume($shop_id, $pid, $num = 0)
    {
        $flag = 0;
        $list = $this->find()->where(['product_id' => $pid, 'shop_id' => $shop_id])->one();
        if ($list) {
            $list->sales_volume = $list->sales_volume + $num;
            $ret = $list->save();
            return $ret;
        }
        return $flag;
    }

    /**
     * 简介：
     * @author  lichenjun@iyangpin.com。
     * @param array $_D 数组
     * @return int
     * @throws \yii\db\Exception
     */
    public function addProduct($_D = array())
    {
        //
        $_sql = "INSERT INTO `shop_products` (`product_id`, `product_sn`, `cat_id`,`brand_id`,`shop_id`, `price`, `status`, `product_number`,`type`) VALUES ('" . $_D['product_id'] . "','" . $_D['product_sn'] . "', '" . $_D['cat_id'] . "', '" . $_D['brand_id'] . "', '" . $_D['shop_id'] . "', '" . $_D['price'] . "', '" . $_D['status'] . "', '" . $_D['product_number'] . "','" . $_D['type'] . "') ON DUPLICATE KEY UPDATE product_id=" . $_D['product_id'] . ",shop_id=" . $_D['shop_id'] . ", `type`=" . $_D['type'] . ",  `cat_id`=" . $_D['cat_id'] . ",`brand_id`='" . $_D['brand_id'] . "',`status`=1,product_number= product_number+" . $_D['product_number'];
        $rs = \Yii::$app->db->createCommand($_sql)->execute();
        return $rs;
    }
}
