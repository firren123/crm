<?php
/**
 * 简介:敏感词管理
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   I500
 * @filename  SensitiveKeywordsController.php
 * @author    lichenjun+zhoujun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/3/23 下午8:21
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */
namespace backend\models\i500m;

use backend\models\i500m\I500Base;
use Yii;
use yii\base\Model;
use common\helpers\CurlHelper;
use yii\db\ActiveRecord;
use linslin\yii2\curl;

/**
 * Class SensitiveKeywords
 * @category  PHP
 * @package   SensitiveKeywords
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class SensitiveKeywords extends I500Base
{

    /**
     * 简介：
     * @return string
     */
    public static function tableName()
    {
        return '{{%sensitive_keywords}}';
    }

    /**
     * 简介：定义过滤规则
     * @author  lichenjun@iyangpin.com。
     * @return array
     */
    public function rules()
    {
        return [
            //不可为空的字段
            [['keyword', 'status'], 'required'],
        ];
    }


    /**
     * 简介：列表
     * @author  zhoujun+lichenjun<lichenjun@iyangpin.com>
     * @param array $data   x
     * @param int   $offset x
     * @return mixed
     */
    public function show($data = array(), $offset =null)
    {
        $list = $this->find()
            ->orderBy('id asc')
            ->offset($offset)
            ->limit($data['size'])
            ->asArray()
            ->all();
        return $list;
    }

    /**
     * 简介：添加
     * @author  zhoujun+lichenjun<lichenjun@iyangpin.com>。
     * @param string $keyword x
     * @param int    $status  x
     * @return bool
     */
    public function add($keyword, $status)
    {
        $this->keyword = $keyword;
        $this->status = $status;
        $infoS = $this->save();
        return $infoS;
    }

    /**
     * 简介：敏感词删除
     * @param int $id x
     * @return false|int
     * @throws \Exception
     */
    public function del($id)
    {
        $data = $this::findOne($id);
        $info = $data->delete();
        return $info;
    }

    /**
     * 简介：修改状态
     * @author  zhoujun+lichenjun<lichenjun@iyangpin.com>
     * @param array $data cc
     * @return mixed
     */
    public function UpdateStatus($data)
    {
        $customer = SensitiveKeywords::findOne($data['id']);
        $customer->status = $customer['status'] == 0 ? 1 : 0;
        $ret = $customer->save();
        return $ret;
    }

    /**
     * 简介：
     * @return int|string
     */
    public function total()
    {
        $total = $this->find()->count();
        return $total;
    }
}
