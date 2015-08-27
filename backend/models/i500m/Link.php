<?php
/**
 *
 * @category  CRM
 * @package   友情链接 Link.php
 * @author    youyong <youyong@iyangpin.com>
 * @time      2015/4/17 18:18 
 * @copyright 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com
 * @link      youyong@iyangpin.com
 */
namespace backend\models\i500m;
    use yii\db\ActiveRecord;

class Link extends I500Base
{
    /**
     * 数据库
     *
     * @return string
     */
    public static function tableName()
    {
        return '{{%link}}';
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
            [['title'],'required','message' => '站点名称 不能为空.'],
            [['url'],'required','message' => '站点地址 不能为空.'],
            [['images'],'required','message' => '站点图片 不能为空.'],
            [['sort'],'required','message' => '排序 不能为空.'],
            [['status'],'required','message' => '状态 不能为空.'],
        ];
    }


    /**
     * 添加
     *
     * @param: array $data
     * @return bool
     */
    public function getInsert($data = array())
    {
        $re = false;
        if ($data) {
            foreach ($data as $k=>$v) {
                $this->$k = $v;
            }
            $result = $this->save();
            if ($result==true) {
                $re = $this->attributes['id'];
            }
        }
        return $re;
    }


    /**
     * 友情链接删除
     *
     * @param: $id
     * @return int
     * @throws \Exception
     */
    public function getDelete($id)
    {
        if (empty($id)) {
            return 0;
        } else {
            $list = $this->findOne($id);
            $result = $list->delete();
            if ($result==true) {
                return 200;
            } else {
                return 0;
            }
        }

    }

    /**
     * 站点名称是否存在
     *
     * @param: $name
     * @param: NULL $id
     * @return array|null|ActiveRecord
     */
    public function getDetailsByName($title, $id=NULL)
    {
        $list = array();
        if (!empty($title)) {
            if (empty($id)) {
                $list = $this->find()->where("title="."'".$title."'")->asArray()->one();
            } else {
                $list = $this->find()->where("title="."'".$title."' and id!=".$id)->asArray()->one();
            }
        }
        return $list;
    }

    /**
     * 批量删除
     *
     * @param: string $ids
     * @return int
     */
    public function getBatchDelete($ids)
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
}