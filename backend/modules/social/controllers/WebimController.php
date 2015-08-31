<?php
/**
 * 简介1
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Admin
 * @filename  WebimController.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/8/17 下午5:41
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\modules\social\controllers;

use backend\controllers\BaseController;
use common\helpers\CurlHelper;


/**
 * Class WebimController
 * @category  PHP
 * @package   Admin
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class WebimController extends BaseController
{
    /**
     * 简介：在线聊天
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public function actionIndex()
    {
        $this->layout = '/empty';
        $url = "/user/login/index?username=admin";
        $info = CurlHelper::get($url);
        if ($info['code'] ==200) {
            return $this->render('index', $info['data']);
        } else {
            return $this->error('登录失败，重新点击在线客服');
        }

    }
}
