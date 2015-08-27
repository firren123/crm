<?php
/**
 * 简介1
 *
 * PHP Version 5
 * 文件介绍2
 *
 * @category  PHP
 * @package   Admin
 * @filename  QueueSms.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/5/7 下午1:21
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\models\i500m;


use common\helpers\CurlHelper;

/**
 * Class QueueSms
 * @category  PHP
 * @package   QueueSms
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class QueueSms extends I500Base
{
    /**
     * 简介：连接表
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public static function tableName()
    {
        return "{{%queue_sms}}";
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
            'send_time' => '定时发送',
            'create_time' => '添加时间',
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
            [['mobile'], 'required', 'message' => '手机号码 不能为空.'],
            ['mobile', 'match', 'pattern' => '/^1[0-9]{10}$/', 'message' => '{attribute}格式输入不正确'],
            [['content'], 'required', 'message' => '短信内容 不能为空.'],
        ];
    }

    /**
     * 简介：
     * @author  lichenjun@iyangpin.com。
     * @param string $mobile  手机号
     * @param string $content 短信内容
     * @return bool
     */
    public function addMsg($mobile, $content)
    {
        $configModel = new CrmConfig();
        $config = $configModel->getInfo(['key' => 'channel']);
        $curl = $config['value'] . 'sms/get-add';
        $data = ['mobile' => $mobile, 'content' => $content];
        $ret = CurlHelper::post($curl, $data);
        return $ret;
    }
}
