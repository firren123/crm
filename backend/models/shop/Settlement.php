<?php
/**
 * 简介
 * PHP Version 5
 * @category  Admin
 * @package   Settlement
 * @author    zhoujun <zhoujun@iyangpin.com>
 * @time      2015/5/29 13:34
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   i500m http://www.i500m.com
 * @link      zhoujun@iyangpin.com
 */
namespace backend\models\shop;

/**
 * Class Settlement
 * @category  PHP
 * @package   Settlement
 * @author    zhoujun <zhoujun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class Settlement extends ShopBase
{
    /**
     * 简介：
     * @return string
     */
    public static function tableName()
    {
        return '{{%shop_accounts}}';//开户银行信息表
    }

    /**
     * 规则
     * @return array
     */
    public function rules()
    {
        return [
            //不可为空的字段
        ];
    }

    /**
     * 简介：
     * @param int $id ID
     * @return array|null|\yii\db\ActiveRecord
     */
    public function settle($id)
    {
        $list = $this->find()->where("shop_id = $id")->asArray()->one();
        return $list;
    }

    /**
     * 简介：
     * @param null $where WHERE
     * @return int|string
     */
    public function total($where = null)
    {
        if ($where) {
            $total = $this->find()->where($where)->count();
            return $total;
        } else {
            $total = $this->find()->count();
            return $total;
        }

    }

    /**
     * 简介：
     * @param array $data   x
     * @param null  $offset x
     * @param null  $where  x
     * @return array|\yii\db\ActiveRecord[]
     */
    public function show($data = array(), $offset = null, $where = null)
    {
        if ($where) {
            $list = $this->find()
                ->where($where)
                ->offset($offset)
                ->limit($data['size'])
                ->orderBy('end_time desc')
                ->asArray()
                ->all();
            return $list;
        } else {
            $list = $this->find()
                ->offset($offset)
                ->limit($data['size'])
                ->orderBy('end_time desc')
                ->asArray()
                ->all();
            return $list;
        }
    }

    /**
     * 简介：
     * @param int $shop_id shop_id
     * @return array|null|\yii\db\ActiveRecord
     */
    public function all_shop($shop_id)
    {
        $list = $this->find()->select('shop_id')->where("shop_id = $shop_id")->asArray()->one();
        return $list;
    }

    /**
     * 简介：
     * @param int  $id        ID
     * @param bool $is_freeze X
     * @return bool
     */
    public function freeze($id, $is_freeze = false)
    {
        if ($is_freeze == 1) {
            $list = Settlement::findOne($id);
            $list->status = 1;
            $status = $list->save();
            return $status;
        } elseif ($is_freeze == 0) {
            $list = Settlement::findOne($id);
            $list->status = 2;
            $status = $list->save();
            return $status;
        } else {
            $list = Settlement::findOne($id);
            $list->status = 0;
            $status = $list->save();
            return $status;
        }
    }

    /**
     * 简介：
     * @param int $account_id x
     * @param int $shop_id    x
     * @return array|null|\yii\db\ActiveRecord
     */
    public function ship($account_id, $shop_id)
    {
        $list = $this->find()->select('start_time,end_time,status')->where(['id' => $account_id, 'shop_id' => $shop_id])->asArray()->one();
        return $list;
    }
}
