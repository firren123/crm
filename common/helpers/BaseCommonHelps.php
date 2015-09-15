<?php
/**
 * 共同函数库
 *
 * PHP Version 5
 * 一些共同函数接口
 *
 * @category  I500M
 * @package   Admin
 * @author    renyineng <renyineng@iyangpin.com>
 * @time      15/4/1 下午3:01
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      renyineng@iyangpin.com
 */
namespace common\helpers;

use backend\models\i500m\Branch;
use backend\models\i500m\City;
use backend\models\i500m\District;
use backend\models\i500m\OpenCity;
use backend\models\i500m\Province;
use yii\helpers\ArrayHelper;

/**
 * Class BaseCommonHelps
 * @category  PHP
 * @package   BaseCommonHelps
 * @author    renyineng <renyineng@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class BaseCommonHelps
{
    /**
     * 快捷缓存
     * @param  string $cache_id key值
     * @param  mixed  $value    缓存内容 为空时,获取缓存,为NULL时 删除缓存
     * @param  int    $expire   缓存时间
     * @return mix
     */
    public static function cache($cache_id, $value = '', $expire = 0)
    {
        static $cache = '';
        if (empty($cache)) {
            $cache = $cache = \Yii::$app->cache;
        }

        if ('' === $value) {
            return json_decode($cache->get($cache_id), true);
        } elseif (is_null($value)) {
            return $cache->delete($cache_id);
        } else {
            return $cache->set($cache_id, json_encode($value), (int)$expire);
        }
    }

    /**
     * 简介：获取所有省 一维数组
     * @param bool $status x
     * @return array|mix  false 全部省 true 是开通省
     */
    public static function province($status = false)
    {
        $add = "";
        if ($status) {
            $add = "_branch";
        }
        $data = self::cache('provinces' . $add);

        if ($data == false) {
            $Province_model = new Province();
            $Province_result = $Province_model->province();
            $data = ArrayHelper::map($Province_result, 'id', 'name');
            if ($status) {
                $Branch_model = new Branch();
                $Branch_list = $Branch_model->getList(['status' => 1], "province_id", "province_id asc");
                $pid = [];
                foreach ($Branch_list as $k => $v) {
                    $pid[$v['province_id']] = $data[$v['province_id']];
                }
                $data = $pid;
            }


            self::cache('provinces' . $add, $data);
        }


        return $data;
    }

    /**
     * 简介：获取所有城市 一维数组
     * @param int  $pid    x
     * @param bool $status x
     * @return array|mix  false 全部市 true 是开通市
     */
    public static function city($pid, $status = false)
    {
        $add = '';
        if ($status) {
            $add = "_branch";
        }
        $name = 'city' . $add . $pid;
        $data = self::cache($name);
        if ($data == false) {
            $City_model = new City();
            $City_result = $City_model->city($pid);
            $data = ArrayHelper::map($City_result, 'id', 'name');
            if ($status) {
                $OpenCity_model = new OpenCity();
                $OpenCity_list = $OpenCity_model->getList(['status' => 1], "city", "city asc");
                $pid = [];
                foreach ($OpenCity_list as $k => $v) {
                    if (isset($data[$v['city']])) {
                        $pid[$v['city']] = $data[$v['city']];
                    }
                }
                $data = $pid;
            }
            self::cache($name);
        }
        return $data;
    }

    /**
     * 获取所有区域 一维数组
     * @param int $cid x
     * @return array|mix
     */
    public static function district($cid)
    {
        $name = 'district' . $cid;
        $data = self::cache($name);
        //var_dump($data);exit();
        if ($data == false) {
            $District_model = new District();
            $District_result = $District_model->district($cid);
            $data = ArrayHelper::map($District_result, 'id', 'name');
            self::cache($name);
        }
        return $data;
    }
}
