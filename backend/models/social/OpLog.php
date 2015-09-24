<?php
/**
 * 简介1
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Crm
 * @filename  OpLog.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/9/24 上午9:35
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\models\social;

use backend\models\i500m\Role;


/**
 * Class OpLog
 * @category  PHP
 * @package   Crm
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class OpLog extends SocialBase
{
    /**
     * 简介：
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public static function tableName()
    {
        return "{{%i500_oplog}}";
    }

    public function writeLog($remark)
    {
        $url = \Yii::$app->requestedRoute;
        $url_arr = explode('/', $url);
        $data = [];
        $data['model'] = isset($url_arr[0]) ? $url_arr[0] : 'admin';
        $data['controller'] = isset($url_arr[1]) ? $url_arr[1] : 'site';
        $data['action'] = isset($url_arr[2]) ? $url_arr[2] : 'index';
        $data['admin_id'] = \Yii::$app->user->identity->id;
        $data['admin_name'] = \Yii::$app->user->identity->username;
        $data['role_id'] = \Yii::$app->user->identity->role_id;
        $roleModel = new Role();
        $role = $roleModel->findOne($data['role_id']);
        $data['role_name'] = $role['name'];
        $data['remark'] = $remark;
        $this->insertInfo($data);
    }
}
