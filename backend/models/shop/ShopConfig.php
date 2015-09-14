<?php
/**
 * 商家提款申请
 *
 * PHP Version 5
 *
 * @category  CRM
 * @package   ShopCash.php
 * @author    xxx <xxx@iyangpin.com>
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
 * @author   xxx <xxx@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     liuwei@iyangpin.com
 */
class ShopConfig extends ShopBase
{
    /**
     * 简介：
     * @return string
     */
    public static function tableName()
    {
        return '{{%shop_config}}';
    }

    /**
     * 简介：
     * @param array $config x
     * @return string
     */
    public function add($config = array())
    {
        if (isset($config)) {
            $this->free_shipping = $config['free_shipping'];
            $this->freight = $config['freight'];
            $this->send_price = $config['send_price'];
            $this->community_num = $config['community_num'];
            $this->bc_id = $config['bc_id'];
            $this->price_limit = $config['price_limit'];
            $this->type = $config['type'];
            $this->save();
        }
    }

    /**
     * 简介：
     * @param int $bc_id x
     * @param int $id    x
     * @return array|null|\yii\db\ActiveRecord
     */
    public function check($bc_id, $id)
    {
        $list = array();
        if (!empty($bc_id)) {
            if (0 == $id) {
                $list = $this->find()->where("bc_id=" . $bc_id)->asArray()->one();
            } else {
                $list = $this->find()->where("bc_id=" . "'" . $bc_id . "' and id!=" . $id)->asArray()->one();
            }
        }
        return $list;
    }
}
