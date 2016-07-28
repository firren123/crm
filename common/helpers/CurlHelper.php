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
use backend\models\social\User;
use backend\models\shop\ShopPushId;

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
    /**
     * 简介：
     * @author  lichenjun@iyangpin.com。
     * @param int    $uid            用户ID
     * @param int    $user_type      用户ID类型 1商家，2用户 ，3供应商
     * @param string $title          标题：ios 不用
     * @param string $description    内容
     * @param array  $custom_content 其他参数
     * @param int    $type           类型
     * @return array|mixed
     */
    public static function pushPost($uid,$user_type,$title,$description,$custom_content=array(), $type = 10)
    {
        $data = [];
        //初始值
        $channel_type = 4;
        $channel_id = '';

        if ($user_type == 1) {
            $userModel = new User();
            $userInfo = $userModel->getInfo(['id'=>$uid]);
            $channel_id = $userInfo['last_login_channel'];
            $device = $userInfo['last_login_source'];
            if ($device == 2) {  //1=wap2=android3=ios',
                $channel_type = 4;
            } elseif ($device == 3) {
                $channel_type = 5;
            }
        } elseif ($user_type == 2) {
            $shopPushModel = new ShopPushId();
            $shopPushModel->getInfo([]);
        } elseif ($user_type == 3) {

        }
        $data['channel_id'] = $channel_id;
        $data['title'] = $title;
        $data['description'] = $description;
        $data['custom_content'] = $custom_content;
        $data['channel_type'] = $channel_type;
        $url = \Yii::$app->params['channelUrl'].'push/push-msg-to-single-device';
        switch ($type) {
        case 10:   //审核
            $data['custom_content']['type'] = 10;
            break;
        case 20:   //服务
            $data['custom_content']['type'] = 20;
            if (!$data['custom_content']['order_sn']) {
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
        case 50:   //店铺
            $data['custom_content']['type'] = 50;
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