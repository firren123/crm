<?php
/**
 * 下载页面
 *
 * PHP Version 5
 *
 * @category  CRM
 * @package   ServiceCategory.php
 * @author    liuwei <liuwei@iyangpin.com>
 * @time      2015/9/14 0014 上午 11:57
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      liuwei@iyangpin.com
 */

namespace backend\models\social;

/**
 * ServiceCategory
 *
 * @category Admin
 * @package  ServiceCategory
 * @author   liuwei <liuwei@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     liuwei@iyangpin.com
 */
class ServiceCategory extends SocialBase
{
    /**
     * 连接表
     *
     * @return string
     */
    public static function tableName()
    {
        return "{{%i500_service_category}}";
    }
    /**
     * 规则
     *
     * @return array
     */
    public function rules()
    {
        return [
            //不可为空的字段
            [['name'],'required','message' => '类型名称 不能为空.'],
            [['sort'],'required','message' => '排序 不能为空.'],
            [['description'],'required','message' => '类别描述 不能为空.'],
        ];
    }

    /**
     * 添加
     *
     * @param array $data 数组
     *
     * @return bool
     */
    public function getInsert($data = array())
    {
        $re = false;
        if ($data) {
            foreach ($data as $k=>$v) {
                $this->$k = $v;
            }
            $result = $this->save();
            if ($result==true) {
                $re = $this->attributes['id'];
            }
        }
        return $re;
    }

}
