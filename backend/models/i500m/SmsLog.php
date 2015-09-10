<?php
/**
 *  发送短信及短信发送记录
 * @category  Crm
 * @package   SmsLog.php
 * @author    youyong <youyong@iyangpin.com>
 * @time      2015/5/25 10:22
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   i500m http://www.i500m.com
 * @link      youyong@iyangpin.com
 */
namespace backend\models\i500m;

class SmsLog extends I500Base
{
    /**
     * 数据库
     *
     * @return string
     */
    public static function tableName()
    {
        return '{{%sms_log}}';
    }

    /**
     * 字段值
     *
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'mobile' => '手机号码',
            'content' => '短信内容',
            'type' =>'短信类型',
            'status' =>'发送状态',
            'reason' =>'发送失败原因',
            'rewire_id' =>'重发短信id',
            'create_time' =>'创建时间'
        );
    }

    /**
     * 规则
     *
     * @return array
     */
    public function rules()
    {
        return [
            [['type'],'required','message' => '短信类型 不能为空.'],
            [['mobile'],'required','message' => '手机号码 不能为空.'],
            ['mobile','match','pattern'=>'/^1[0-9]{10}$/','message'=>'{attribute}格式输入不正确'],
            [['content'],'required','message' => '短信内容 不能为空.'],
        ];
    }
}
