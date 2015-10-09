<?php
/**
 * 一行的文件介绍
 *
 * PHP Version 5
 * 可写多行的文件相关说明
 *
 * @category  Wap
 * @package   Member
 * @author    liubaocheng <liubaocheng@iyangpin.com>
 * @time      15/3/20 上午11:14
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   license http://www.i500m.com
 * @link      liubaocheng@iyangpin.com
 */

namespace common\helpers;

/**
 * Class CurlHelper
 * @category  PHP
 * @package   CurlHelper
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class CurlHelper extends BaseCurlHelps
{
    /**
     * 简介：
     * @author  lichenjun@iyangpin.com。
     * @param string $channel_id     手机唯一ID
     * @param string $title          标题
     * @param string $description    内容
     * @param array  $custom_content 其他参数
     * @param int    $type           10=审核，20=服务，30帖子
     * @return array|mixed
     */
    public static function pushPost($channel_id,$title,$description,$custom_content=array(), $type = 10)
    {
        $data = [];
        $data['channel_id'] = $channel_id;
        $data['title'] = $title;
        $data['description'] = $description;
        $data['custom_content'] = $custom_content;
        $url = \Yii::$app->params['channelUrl'].'push/push-msg-to-single-device';
        switch ($type) {
        case 10:   //审核
            $data['custom_content']['type'] = 10;
            break;
        case 20:   //服务
            $data['custom_content']['type'] = 20;
            if ($data['custom_content']['order_sn']) {
                return array('code'=>101, 'msg'=>'服务参数错误');
            }
            break;
        case 30:   //帖子
            $data['custom_content']['type'] = 30;
            if (false == $data['custom_content']['title'] || false == $data['custom_content']['id']) {
                return array('code'=>102, 'msg'=>'帖子参数错误');
            }
            break;
        case 40:   //消息
            $data['custom_content']['type'] = 40;
            if (false == $data['custom_content']['title'] || false == $data['custom_content']['id']) {
                return array('code'=>102, 'msg'=>'帖子参数错误');
            }
            break;
        default:
            break;
        }
        $response = self::post($url, $data);
        return $response;

    }

}