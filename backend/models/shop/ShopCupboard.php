<?php
/**
 * 简介1
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   I500
 * @author    zhaochengqiang <zhaochengqiang@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/7/24 上午10:23
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */

namespace backend\models\shop;

/**
 * Class ShopCupboard
 * @category  PHP
 * @package   ShopCupboard
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class ShopCupboard extends ShopBase
{
    /**
     * 简介：
     * @return string
     */
    public static function tableName()
    {
        return '{{%shop_cupboard}}';
    }

    /**
     * 简介：
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'status' => '状态',
        );
    }

    /**
     * 简介：
     * @return array
     */
    public function rules()
    {
        return [
            //不可为空的字段
            //[['remark'],'required'],

        ];
    }

    /**
     * 简介：
     * @param null $where where
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getlist($where = null)
    {
        if ($where) {
            $list = $this->find()
                ->where($where)
                ->orderBy('id desc')
                ->asArray()
                ->all();
            return $list;
        } else {
            $list = $this->find()
                ->orderBy('id desc')
                ->asArray()
                ->all();
            return $list;
        }
    }
}
