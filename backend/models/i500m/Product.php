<?php
/**
 * 标准库页面
 *
 * PHP Version 5
 *
 * @category  CRM
 * @package   Brand.php
 * @author    liuwei <liuwei@iyangpin.com>
 * @time      2015/4/18 11:19
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      liuwei@iyangpin.com
 */


namespace backend\models\i500m;
use backend\models\shop\ShopDetailOrder;
use yii\data\Pagination;

/**
 * Product
 *
 * @category CRM
 * @package  Product
 * @author   liuwei <liuwei@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     liuwei@iyangpin.com
 */
class Product extends I500Base
{
    /**
     * 数据库
     *
     * @return string
     */
    public static function tableName()
    {
        return '{{%product}}';
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
            [['name'],'required','message' => '商品名称 不能为空.'],
            [['cate_first_id'],'required','message' => '分类 不能为空.'],
            [['brand_id'],'required','message' => '品牌 不能为空.'],
        ];
    }

    /**
     * 添加
     *
     * @param array $data 数据
     *
     * @return int
     */
    public function getInsert($data = array())
    {
        $re = 0;
        if ($data) {
            $model = clone $this;
            foreach ($data as $k=>$v) {
                $model->$k = $v;
            }
            $result = $model->save();
            if ($result==true) {
                $re = $model->attributes['id'];
            }
        }
        return $re;
    }

    /**
     * 删除
     *
     * @param string $ids id集合
     *
     * @return int
     */
    public function getDelete($ids)
    {
        if (empty($ids)) {
            return 0;
        } else {
            $result = $this->deleteAll(" id in (".$ids.")");
            if ($result==true) {
                return 200;
            } else {
                return 0;
            }
        }
    }

    /**
     * 商品名称是否存在
     *
     * @param string $name 名称
     * @param null   $id   id
     *
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getDetailsByName($name, $id=NULL)
    {
        $list = array();
        $where = "name="."'".$name."' and single=1 and status!=0";
        if (!empty($name)) {
            if (empty($id)) {
                $list = $this->find()->where($where)->asArray()->one();
            } else {
                $where .= " and id != ".$id;
                $list = $this->find()->where($where)->asArray()->one();
            }
        }
        return $list;
    }

    /**
     * 商品条形码是否存在
     *
     * @param string $name 名称
     * @param null   $id   id
     *
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getDetailsByCode($name, $id=NULL)
    {
        $list = array();
        $where = "bar_code="."'".$name."' and single=1 and status!=0";
        if (!empty($name)) {
            if (empty($id)) {
                $list = $this->find()->where($where)->asArray()->one();
            } else {
                $where .= " and id != ".$id;
                $list = $this->find()->where($where)->asArray()->one();
            }
        }
        return $list;
    }

    /**
     * 商家发货减少库存
     *
     * @param int $order_sn 订单号
     *
     * @return bool
     */
    public function reduceInventory($order_sn)
    {

        //商家订单详情
        $order_detail_model = new ShopDetailOrder();
        //查询商品数量
        $product_arr = $order_detail_model->getList(array('order_sn' => $order_sn), 'p_id,num');

        //修改标准库数量
        foreach ($product_arr as $k => $v) {
            //标准库
            $product_model = new Product();
            $product_str = $product_model->findOne($v['p_id']);;
            $product_str->total_num = $product_str->total_num - $v['num'];
            $product_str->save();
        }

        return true;
    }

    /**
     * 商品列表
     *
     * @param array  $map      条件
     * @param array  $andWhere 附加条件
     * @param int    $pageSize 每页显示条数
     * @param string $order    排序
     * @param int    $sort     正倒排序
     *
     * @return array
     */
    public function getProductList($map = [],$andWhere = [], $pageSize = 20, $order = 'id', $sort = SORT_DESC)
    {

        $query = $this->find()->where($map)->andWhere(['!=','status',0])->andWhere($andWhere);
        $count = $query->count();
        $pages = new Pagination(['totalCount' =>$count, 'pageSize' => $pageSize]);
        $list = $query->orderBy([$order=>$sort])->offset($pages->offset)->limit($pages->limit)->asArray()->all();
        return ['list'=>$list,'pages'=>$pages,'count'=>$count,'pageCount'=>$pages->pageCount];

    }

    /**
     * 单行函数说明
     *
     * @param int $product_id 商品id
     *
     * @return array|null|\yii\db\ActiveRecord
     */
    public function pro_all($product_id)
    {
        $list = $this->find()->select('name,attr_value')->where("id=$product_id")->asArray()->one();
        return $list;
    }

    /**
     * 单行函数说明
     *
     * @param int $product_id 商品id
     *
     * @return array|null|\yii\db\ActiveRecord
     */
    public function get_first_id($product_id)
    {
        $list = $this->find()->select('cate_first_id')->where("id=$product_id")->asArray()->one();
        return $list;
    }
}
