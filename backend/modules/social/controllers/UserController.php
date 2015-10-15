<?php
/**
 * 论坛-用户
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Admin
 * @filename  UserController.php
 * @author    liuwei <liuwei@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/8/13 上午10:12
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */

namespace backend\modules\social\controllers;

use backend\controllers\BaseController;
use backend\models\i500m\City;
use backend\models\i500m\District;
use backend\models\i500m\Province;
use backend\models\social\Post;
use backend\models\social\Service;
use backend\models\social\User;
use backend\models\social\UserInfo;
use common\helpers\CurlHelper;
use common\helpers\FastDFSHelper;
use common\helpers\RequestHelper;
use yii\data\Pagination;
use yii\web\UploadedFile;

/**
 * Class UserController
 * @category  PHP
 * @package   Admin
 * @author    liuwei <liuwei@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class UserController extends BaseController
{
    public $size = 20;
    /**
     * 用户列表
     *
     * @return string
     */
    public function actionIndex()
    {
        $user_model = new User();
        $info_model = new UserInfo();
        $page = RequestHelper::get('page', 1);
        $size = $this->size;
        $cond['is_deleted'] = 2;
        $search = RequestHelper::get('Search', '');
        $and_where = [];
        if (!empty($search['mobile'])) {
            $and_where = ['like', 'mobile', $search['mobile']];
        }
        if (!empty($search['last_login_channel'])) {
            $cond['last_login_channel'] = $search['last_login_channel'];
        }
        if (!empty($search['last_login_source'])) {
            $cond['last_login_source'] = $search['last_login_source'];
        }
        if (!empty($search['status'])) {
            $cond['status'] = $search['status'];
        }
        $data = $user_model->getPageList($cond, '*', 'status desc,id desc', $page, $size, $and_where);
        if ($data) {
            foreach ($data as $key=>$value) {
                $info_cond['mobile'] = $value['mobile'];
                $info = $info_model->getInfo($info_cond, 'card_audit_status');
                $data[$key]['card_status'] = empty($info) ? '' : $info['card_audit_status'];
            }
        }
        //商品数量及分页
        $total = $user_model->getCount($cond, $and_where);
        $pages = new Pagination(['totalCount' =>$total, 'pageSize' => $this->size]);
        $param = [
            'total' => $total,
            'pages' => $pages,
            'data' => $data,
            'search' => $search
        ];
        return $this->render('index', $param);
    }

    /**
     * 用户添加
     *
     * @return string
     */
    public function actionAdd()
    {
        $model = new User();
        $user_model = new UserInfo();
        $province_model = new Province();
        $province_list = $province_model->getList(array('status'=>[0,1]));
        $post_user = RequestHelper::post('User');
        if (!empty($post_user)) {
            $data['mobile'] = $post_user['mobile'];
            $data['realname'] = $post_user['realname'];
            $data['province_id'] = $post_user['province_id'];
            $data['city_id'] = $post_user['city_id'];
            $data['district_id'] = $post_user['district_id'];
            $data['community_name'] = $post_user['community_name'];
            $data['birthday'] = $post_user['year'].'-'.$post_user['month'].'-'.$post_user['day'];
            $data['personal_sign'] = $post_user['personal_sign'];
            $data['sex'] = $post_user['sex'];
            $data['nickname'] = $post_user['nickname'];
            $user_data['status'] = $post_user['status'];
            $user_data['password'] = $post_user['password'];
            $user_data['mobile'] = $post_user['mobile'];
            $count = $model->getCount(['mobile'=>$data['mobile']]);
            $search ='/^(((1[34578]{1}))+\d{9})$/';
            if (empty($data['mobile'])) {
                return $this->error('手机号 不能为空');
            } elseif (!preg_match($search, $data['mobile'])) {
                return $this->error('手机号 无效');
            } elseif ($count!=0) {
                return $this->error('手机号 已经注册');
            } elseif (strlen($user_data['password'])<6) {
                return $this->error('密码 不能少于6位');
            } elseif (strlen($user_data['password'])>12) {
                return  $this->error('密码 不能大于12位');
            } elseif (empty($data['province_id'])) {
                return  $this->error('省id 不能为空');
            } elseif (empty($data['city_id'])) {
                return  $this->error('市id 不能为空');
            } elseif (empty($data['district_id'])) {
                return $this->error('县id 不能为空');
            } elseif (empty($data['sex'])) {
                return $this->error('性别 不能为空');
            } elseif (empty($data['community_name'])) {
                return $this->error('小区 不能为空');
            } elseif (empty($post_user['year']) or empty($post_user['month']) or empty($post_user['day'])) {
                return $this->error('生日 不能为空');
            } else {
                $file = UploadedFile::getInstance($model, 'avatar');
                if ($file) {
                    $file_size=$file->size;//大小
                    $file_type=$file->type;//类型
                    $size = 1024*1024*1;
                    if ($file_type!='image/jpeg' and $file->type!='image/png') {
                        return $this->error('头像 仅支持JPG/PNG格式.');
                    } elseif ($file_size > $size) {
                        return $this->error('头像 不能大于1m.');
                    } else {
                        //上传图片
                        $fast = new FastDFSHelper();
                        $ret = $fast->fdfs_upload_name_size($file->tempName, $file->name);
                        $data['avatar'] = '/'.$ret['group_name'] . '/' . $ret['filename'];
                    }
                }
                $randStr = str_shuffle('1234567890');
                $user_data['salt'] =  substr($randStr, 0, 6);
                $user_data['password'] = md5($user_data['salt'].md5($user_data['password']));
                $user_data['create_time'] =  date("Y-m-d H:i:s");
                $result = $model->insertInfo($user_data);
                if ($result==true) {
                    $user_result = $user_model->insertInfo($data);
                    if ($user_result) {
                        $url = "/user/login/register?username=".$data['mobile'];
                        CurlHelper::get($url, 'server');
                        $data['create_time'] = date("Y-m-d H:i:s");
                        return $this->redirect("/social/user");
                    }
                }
            }
        }
        return $this->render('add', ['province_list'=>$province_list]);
    }
    /**
     * 用户编辑
     *
     * @return string
     */
    public function actionEdit()
    {
        $mobile = RequestHelper::get('mobile', 0);
        $model = new User();
        $user_model = new UserInfo();
        if ($mobile==0) {
            return $this->redirect("/social/user");
        }
        $item = $user_model->getInfo(['mobile'=>$mobile]);
        $province_model = new Province();
        $province_list = $province_model->getList(array('status'=>[0,1]));
        $city_model = new City();
        $city_list = $city_model->getList(array('province_id'=>$item['province_id']));
        $district_model = new District();
        $district_list = $district_model->getList(array('city_id'=>$item['city_id']));
        $year_data = [];
        if (!empty($item['birthday'])) {
            $year_data = explode('-', $item['birthday']);
        }
        $post_user = RequestHelper::post('User');
        if (!empty($post_user)) {
            $data['nickname'] = $post_user['nickname'];
            $data['realname'] = $post_user['realname'];
            $data['province_id'] = $post_user['province_id'];
            $data['city_id'] = $post_user['city_id'];
            $data['district_id'] = $post_user['district_id'];
            $data['community_name'] = $post_user['community_name'];
            $data['birthday'] = $post_user['year'].'-'.$post_user['month'].'-'.$post_user['day'];
            $data['personal_sign'] = $post_user['personal_sign'];
            $data['sex'] = $post_user['sex'];
            if (empty($data['province_id'])) {
                return  $this->error('省id 不能为空');
            } elseif (empty($data['city_id'])) {
                return  $this->error('市id 不能为空');
            } elseif (empty($data['district_id'])) {
                return $this->error('县id 不能为空');
            } elseif (empty($data['sex'])) {
                return $this->error('性别 不能为空');
            } elseif (empty($data['community_name'])) {
                return $this->error('小区 不能为空');
            } elseif (empty($post_user['year']) or empty($post_user['month']) or empty($post_user['day'])) {
                return $this->error('生日 不能为空');
            } else {
                $file = UploadedFile::getInstance($model, 'avatar');
                if ($file) {
                    $file_size=$file->size;//大小
                    $file_type=$file->type;//类型
                    $size = 1024*1024*1;
                    if ($file_type!='image/jpeg' and $file->type!='image/png') {
                        return $this->error('头像 仅支持JPG/PNG格式.');
                    } elseif ($file_size > $size) {
                        return $this->error('头像 不能大于1m.');
                    } else {
                        //上传图片
                        $fast = new FastDFSHelper();
                        $ret = $fast->fdfs_upload_name_size($file->tempName, $file->name);
                        $data['avatar'] = '/'.$ret['group_name'] . '/' . $ret['filename'];
                    }
                } else {
                    $data['avatar'] = $item['avatar'];
                }
                $data['update_time'] = date('Y-m-d H:i:s');
                $result = $user_model->updateInfo($data, ['mobile'=>$mobile]);
                if ($result==true) {
                    return $this->redirect("/social/user");
                }
            }
        }
        $param = [
            'province_list'=>$province_list,
            'city_list'=>$city_list,
            'district_list'=>$district_list,
            'year_data'=>$year_data,
            'item'=>$item
        ];
        return $this->render('edit', $param);
    }
    /**
     * 用户详情
     *
     * @return string
     */
    public function actionView()
    {
        $cond['mobile'] = RequestHelper::get('mobile', 0);
        $model = new UserInfo();
        $item = $model->getInfo($cond);
        if (!empty($item)) {
            //省
            $province_model = new Province();
            $province_list = $province_model->getInfo(array('id'=>$item['province_id']));
            $item['province_name'] = empty($province_list) ? '' : $province_list['name'];
            //市
            $city_model = new City();
            $city_list = $city_model->getInfo(array('id'=>$item['city_id']));
            $item['city_name'] = empty($city_list) ? '' : $city_list['name'];
            //县
            $district_model = new District();
            $district_list = $district_model->getInfo(array('id'=>$item['district_id']));
            $item['district_name'] = empty($district_list) ? '' : $district_list['name'];
            if (!preg_match('/http:\/\/[\w.]+[\w\/]*[\w.]*\??[\w=&\+\%]*/is', $item['avatar']) and !empty($item['avatar'])) {
                $item['avatar'] = \Yii::$app->params['imgHost'].$item['avatar'];
            }
        }
        return $this->render("view", ['item'=>$item]);
    }

    /**
     * 修改密码
     *
     * @return array
     */
    public function actionPassword()
    {
        $mobile = RequestHelper::get('mobile', '0');
        $model = new User();
        if ($mobile==0) {
            return $this->redirect("/social/user");
        } else {
            $cond['mobile'] = $mobile;
            $item = $model->getInfo($cond);
        }
        return $this->render("password", ['item'=>$item]);
    }
    /**
     * 获取城市
     *
     * Param int $pid   省市id
     *
     * @return array
     */
    public function actionGetCity()
    {
        $pid   = RequestHelper::get('pid');
        $city_model = new City();
        $city_list = $city_model->getList(array('province_id'=>$pid));
        echo json_encode($city_list);
    }
    /**
     * 获取地区
     *
     * Param int $cid   城市id
     *
     * @return array
     */
    public function actionGetDistrict()
    {
        $cid  = RequestHelper::get('cid');
        $district_model = new District();
        $district_list = $district_model->getList(array('city_id'=>$cid));
        echo json_encode($district_list);
    }

    /**
     * 修改用户状态
     *
     * @return array
     */
    public function actionStatus()
    {
        $model = new User();
        $post_model = new Post();
        $mobile = RequestHelper::get('mobile', 0);
        $status = RequestHelper::get('status', 0);
        $type = RequestHelper::get('type', 0);
        $array = ['code'=>'101','msg'=>'参数错误'];
        if ($mobile>0 and $status>0 and $type>0) {
            $data['status'] = $status==2 ? 1 : 2;
            $cond['mobile'] = $mobile;
            $result = false;
            if ($type==2) {
                $result = $model->updateInfo($data, $cond);
            } else {
                $user_result = $model->updateInfo($data, $cond);
                if ($user_result) {
                    $result =  $post_model->updateInfo($data, $cond);
                }
            }
            if ($result==true) {
                $array = ['code'=>'200','msg'=>'成功'];
            } else {
                $array = ['code'=>'101','msg'=>'系统繁忙'];
            }
        }
        echo json_encode($array);
    }

    /**
     * 修改资料(ajax)
     *
     * @return string
     */
    public function actionUppassword()
    {
        $model = new User();
        $password = RequestHelper::post('password', 0);
        $re_password = RequestHelper::post('re_password', 0);
        $mobile = RequestHelper::post('mobile', 0);
        if (mb_strlen($password, 'utf-8')<6) {
            $array = ['code'=>'101','msg'=>'密码长度不能少于6位'];
        } elseif (mb_strlen($password, 'utf-8')>12) {
            $array = ['code'=>'101','msg'=>'密码长度不能大于12位'];
        } elseif ($password != $re_password) {
            $array = ['code'=>'101','msg'=>'密码和重复密码不一致'];
        } elseif ($mobile==0) {
            $array = ['code'=>'101','msg'=>'手机号不能为空'];
        } else {
            $cond['mobile'] = $mobile;
            $item = $model->getInfo($cond);
            if ($item) {
                $data['password'] = md5($item['salt'].md5($password));
                $result = $model->updateInfo($data, ['mobile'=>$mobile]);
                if ($result) {
                    $array = ['code'=>'200','msg'=>'修改成功'];
                } else {
                    $array = ['code'=>'101','msg'=>'系统繁忙'];
                }
            } else {
                $array = ['code'=>'101','msg'=>'用户不存在'];
            }
        }
        return json_encode($array);
    }
    public function actionExamine()
    {
        $model = new UserInfo();
        $cond['mobile'] = RequestHelper::get('mobile', '');
        $item = $model->getOneRecord($cond);
        return $this->render('examine', ['item'=>$item]);
    }
    /**
     * 身份证审核-页面
     *
     * @return string
     */
    public function actionInfo()
    {
        $model = new UserInfo();
        $cond['mobile'] = RequestHelper::get('mobile', '');
        $item = $model->getOneRecord($cond);
        return $this->render('info', ['item'=>$item]);
    }
    public function actionUpdateInfo()
    {
        $array = ['code'=>'101','msg'=>'信息不完整'];
        $mobile = RequestHelper::get('mobile', 0);
        $real_name = RequestHelper::get('real_name', '');
        $user_card = RequestHelper::get('user_card', 0);
        $server_model = new Service();
        $model = new UserInfo();
        if ($mobile>0 and $real_name!='' and $user_card>0) {
            $real_name_number = mb_strlen($real_name, 'utf8');
            $user_card_number = mb_strlen($user_card, 'utf8');
            if ($real_name_number<2) {
                $array = ['code'=>'101','msg'=>'真实姓名 必须大于等于两位数'];
            } elseif(!($user_card) or $user_card_number<18) {
                $array = ['code'=>'101','msg'=>'身份证号 必须是18位数字'];
            } else {
                $data['realname'] = $real_name;
                $data['user_card'] = $user_card;
                $data['card_audit_status'] = 1;
                $cond['mobile'] = $mobile;
                $result = $model->updateInfo($data, $cond);
                if ($result == true) {
                    $server_result = $server_model->updateInfo(['audit_status' => 1], $cond);
                    $count = $server_model->getCount($cond);
                    if ($count == 0) {
                        $array = ['code' => '200', 'msg' => '修改成功'];
                    } else {
                        if ($server_result == true) {
                            $array = ['code' => '200', 'msg' => '修改成功'];
                        } else {
                            $array = ['code' => '101', 'msg' => '系统繁忙'];
                        }
                    }
                } else {
                    $array = ['code' => '101', 'msg' => '缺少参数'];
                }
            }
        }
        return json_encode($array);
    }

    /**
     * 身份证审核-操作
     *
     * @return string
     */
    public function actionUpdateCardStatus()
    {
        $array = ['code'=>'101','msg'=>'请选择审核状态'];
        $mobile = RequestHelper::get('mobile', 0);
        $status = RequestHelper::get('status', 0);
        $server_model = new Service();
        $model = new UserInfo();
        if ($mobile>0 and $status>0) {
            $data['card_audit_status'] = $status;
            $cond['mobile'] = $mobile;
            $result = $model->updateInfo($data, $cond);
            if ($result==true) {
                $server_result = $server_model->updateInfo(['audit_status'=>$status], $cond);
                $count = $server_model->getCount($cond);
                if ($count==0) {
                    $array = ['code' => '200', 'msg' => '修改成功'];
                } else {
                    if ($server_result == true) {
                        $array = ['code' => '200', 'msg' => '修改成功'];
                    } else {
                        $array = ['code' => '101', 'msg' => '系统繁忙'];
                    }
                }
            } else {
                $array = ['code'=>'101','msg'=>'缺少参数'];
            }
        }
        return json_encode($array);
    }
}
