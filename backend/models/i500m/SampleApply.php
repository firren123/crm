<?php
/**
 * 一行的文件介绍
 *
 * PHP Version 5
 * 可写多行的文件相关说明
 *
 * @category  I500M
 * @package   User
 * @author    zhaochengqiang <zhaochengqiang@iyangpin.com>
 * @time      15/7/24 上午09:37
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      zhaochengqiang@iyangpin.com
 */
namespace backend\models\i500m;


/**
 * Class SampleApply
 * @category  PHP
 * @package   SampleApply
 * @author    zhaochengqiang <zhaochengqiang@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class SampleApply extends I500Base
{
    /**
     * 简介：
     * @return mixed
     */
    public static function getDB()
    {
        return \Yii::$app->db_500m;
    }

    /**
     * 简介：
     * @return string
     */
    public static function tableName()
    {
        return "{{%sample_apply}}";
    }

    /**
     * 简介：
     * @return array
     */
    public function rules()
    {
        return [
        ];
    }

    /**
     * 简介：
     * @param null $where x
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
                ->orderBy('id desc')
                ->asArray()
                ->all();
            return $list;
        } else {
            $list = $this->find()
                ->offset($offset)
                ->limit($data['size'])
                ->orderBy('id desc')
                ->asArray()
                ->all();
            return $list;
        }
    }
}
