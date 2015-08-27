<?php
/**
 * 商家关联小区操作表
 *
 * PHP Version 5
 * 商家关联小区操作表
 *
 * @category  I500M
 * @package   Shop
 * @author    renyineng <renyineng@iyangpin.com>
 * @time      15/4/28 下午5:33 
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      renyineng@iyangpin.com
 */
namespace backend\models\i500m;
use common\helpers\CurlHelper;
use yii\helpers\ArrayHelper;

class ShopCommunity extends I500Base
{

    /**
     * 表名
     *
     * @return string
     */
    public static function tableName()
    {
        return '{{%shop_community}}';
    }
    /**
     * 关联小区和商家
     * @return array
     */
    public function actionAdd()
    {
        $shop_id = RequestHelper::post('shop_id', 0, 'intval');
        //var_dump($shop_id);
        $ids = RequestHelper::post('ids', '');

        $model = new ShopCommunity;
        $re = $model->editShopCommunity($shop_id, $ids);

        if ($re === 1) {
            return $this->returnJsonMsg(200, [], '操作成功');
        } elseif ($re == 2) {
            return $this->returnJsonMsg(2, [], '超过最大值');
        } else {
            return $this->returnJsonMsg(1, [], '参数错误');
        }

    }
    public function getCommunity($shop_id)
    {
        if (empty($shop_id)) return false;

        $list = $this->find()
            ->where(['shop_id'=>$shop_id])
            ->asArray()
            ->all();

        return $list;

    }
    /**
     * 获取已经服务的小区
     * @param int $shop_id 商家id
     * @return array
     */
    public function getHaveList($shop_id=0, $city)
    {
        $url = 'i500/shopcommunities/getcommunity?shop_id='.$shop_id.'&city='.$city;
        $rs = CurlHelper::get($url, 'api');
        $data = [];
        if ($rs['code']=='200') {
            $data = $rs['data'];
        }
        return $data;
    }
    public function RelationCommunity($shop_id = 0, $community_ids = '')
    {
        if (!empty($shop_id) && !empty($community_ids)) {
            $info = Shop::findOne($shop_id);
            if (!empty($info)) {
                //var_dump($info);exit();
                $community_ids = explode(',', $community_ids);
                //$count = count($community_ids);
                if (count($community_ids) > 3 ) return 2;//超过最大数量
                //获取已经服务的小区
                $list = $this->getCommunityList($shop_id);
//                var_dump($list);//exit();
//            $count = count($list);
//            if ($count >= 10 ) return 2;//超过最大数量
                $community = ArrayHelper::index($list, 'community_id');

                $add_data = array();

                foreach ($community_ids as $v) {
                    //如果新增的小区 不在 列表里 则 设置为新增
                    if (!array_key_exists($v, $community)) {
                        $add_data[] = [$shop_id,$v,1,$info['city']];
                    }
                }
                foreach ($community as $val) {
                    //判断勾选的是否在 列表里 不在的话 则删除
                    if (!in_array($val['community_id'], $community_ids)) {
                        //echo $val['id'];
                        $this->findOne($val['id'])->delete();
                        //$del_where[] = [$val['id']];
                    }
                }
                if (!empty($add_data)) {
//                echo  \Yii::$app->db_500m->createCommand()
//                    ->batchInsert('shop_community', ['shop_id', 'community_id', 'status'], $add_data)->sql;
//                exit();
                    $re = \Yii::$app->db_500m->createCommand()
                        ->batchInsert('shop_community', ['shop_id', 'community_id', 'status','city_id'], $add_data)
                        ->execute();

                }
            }

            return 1;

        }
        return 0;

    }

    /**
     * 获取商家关联的小区数组
     *
     * @param int $shop_id 商家id
     *
     * @return array
     */
    public function getCommunityList($shop_id)
    {
        if (empty($shop_id)) return [];

        $list = $this->find()
            ->where(['shop_id'=>$shop_id])
            ->asArray()
            ->all();

        return $list;
    }
    public function resetCommunity($shop_id)
    {
        $re = $this->deleteAll(['shop_id'=>$shop_id]);
        return $re;
    }
    /**
     * 获取附近的商家列表
     * @param int $shop_id 商家id
     * @return array
     */
    public function getNearList($city_id, $lng, $lat)
    {
        //1. 通过shop_id 获取 经纬度。
        //2. 通过经纬度 查询数据
        $data = [];
        if (empty($city_id) || $lng == 0 || $lat == 0) return [];
        $community_url = 'location/get-near-community?city_id='.$city_id.'&lng='.$lng.'&lat='.$lat;
        $community_rs = CurlHelper::get($community_url, 'server');
        //var_dump($community_rs);
        if ($community_rs['code']=='200') {
            $data = $community_rs['data'];
        }

        return $data;
    }

    /**
     * 新增小区
     * @param int   $shop_id      商家id
     * @param array $community_id 小区id
     * @return array
     */
    public function addOne($shop_id=0,$community_id)
    {
        if (empty($shop_id) || empty($community_id)) {
            return false;
        }
        $url = 'i500/shopcommunities/addone';

        $data   = ['shop_id'=>$shop_id,'community_id'=>$community_id];
        $rs = CurlHelper::post($url, $data, 'api');

        return $rs;
    }
    /**
     * 删除关联小区
     * @param int   $shop_id      商家id
     * @param array $community_id 小区id
     * @return array
     */
    public function deleteOne($shop_id=0,$community_id)
    {
        if (empty($shop_id) || empty($community_id)) {
            return false;
        }
        $url = 'i500/shopcommunities/delete?shop_id='.$shop_id.'&community_id='.$community_id;

        //$data   = ['shop_id'=>$shop_id,'community_id'=>$community_id];
        $rs = CurlHelper::delete($url);

        return $rs;
    }
    /**
     * Author zhoujun@iyangpin.com
     */
    public function shop_community($id,$city_id)
    {
        $list = $this->find()->where("community_id = $id and city_id = $city_id and status=1")->count();
        return $list;
    }

}