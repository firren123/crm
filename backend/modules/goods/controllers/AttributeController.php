<?php
/**
 * 商品属性管理
 *
 * PHP Version 5
 *
 * @category  I500M
 * @package   Member
 * @author    renyineng <renyineng@iyangpin.com>
 * @time      15/4/17 上午10:36
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      renyineng@iyangpin.com
 */
namespace backend\modules\goods\controllers;
use backend\controllers\BaseController;
use backend\models\i500m\Attribute;
use backend\models\i500m\AttributeValue;
use backend\models\i500m\Log;
use backend\models\i500m\ProductAttr;
use common\helpers\RequestHelper;
use Yii;

class AttributeController extends BaseController
{
    /**
     * 属性集列表
     * @return string
     */
    public function actionIndex()
    {
        $attr_model = new ProductAttr();
        $model = new Attribute();
        $list = $model->getListAttribute();
        $data = [];
        if ($list) {
            foreach ($list as $k=>$v) {
                $attr_cond['attr_name_id'] = $v['id'];
                $number = $attr_model->getCount($attr_cond);
                $data[] = $v;
                $data[$k]['attr_status'] = $number;
            }
        }
        $params = ['list'=>$data];
        return $this->render('index', $params);
    }

    /**
     * 添加属性集
     * @return string
     */
    public function actionAdd()
    {
        $model = new Attribute();
        $model_value = new AttributeValue();
        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post();
            //var_dump($data);
            if (isset($data['Attribute'])) {
                $re = $model->insertAttribute($data['Attribute']);
                //$re = 1;
                if ($re && isset($data['AttributeValue'])) {
                    echo $res = $model_value->insertValue($re, $data['AttributeValue']);

                }
            }
            if (isset($res)) {
                //日志
                $content = "管理员：".\Yii::$app->user->identity->username.",添加了后台显示名称为:".$data['Attribute']['admin_name'].",添加了前台显示名称为:".$data['Attribute']['attr_name']." 的属性";
                $log_model = new Log();
                $log_model->recordLog($content);
                return $this->success('保存成功', '/goods/attribute');
            } else {
                return $this->error('保存失败');
            }




        } else {

            $model->is_search = 0;
            $params = [
                'model'=>$model,
                'model_value'=>$model_value,
            ];
            return $this->render('add', $params);
        }

    }
    /**
     * 编辑属性集
     * @return string
     */
    public function actionEdit()
    {
        $log_model = new Log();
        $attr_id = RequestHelper::get('id',0,'intval');
        $model = new Attribute();
        $show = $model->getInfo(['id'=>$attr_id], false);
        $list = $model->getInfo(['id'=>$attr_id]);
        $model_value = new AttributeValue();

        $value_list = $model_value->getList(['attr_id'=>$attr_id]);
        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post();
            if (isset($data['Attribute'])) {
                //修改属性
                $re = $model->updateAttribute($show, $data['Attribute']);
                //日志
                $array = array_diff($data['Attribute'], $list);
                if (!empty($array)) {
                    $content = "管理员：" . \Yii::$app->user->identity->username . ",修改了属性id为:" . $attr_id . " 属性的";
                    if (!empty($array['admin_name'])) {
                        $content .= " 后台显示名称,修改为了:" . $array['admin_name'];
                    }
                    if (!empty($array['attr_name'])) {
                        $content .= " 前台显示名称,修改为了:" . $array['attr_name'];
                    }
                    if (!empty($array['weight'])) {
                        $content .= " 权重,修改为了:" . $array['weight'];
                    }
                    if (!empty($array['is_search'])) {
                        $status_name = $array['is_search'] == 1 ? '是' : '否';
                        $content .= " 是否检索属性,修改为了:" . $status_name;
                    }

                    $log_model->recordLog($content);
                }
                //$re = 1;
                if (isset($data['AttributeValue'])) {
                    $res = $model_value->updateValue($data['AttributeValue']);
                    $attr_data = array_merge($data['AttributeValue']);
                    foreach ($attr_data as $k=>$v) {
                        if ($v[0] != $value_list[$k]['attr_value']) {
                            $content = "管理员：" . \Yii::$app->user->identity->username . ",修改了属性id为:" . $attr_id . " 属性的属性值列表，改成为属性值:" . $v[0] . ",属性值权重值:" . $v[1];
                            $log_model->recordLog($content);
                        }
                    }

                }
                if (isset($data['NewValue'])) {
                    $res = $model_value->insertValue($attr_id, $data['NewValue']);
                    foreach ($data['NewValue']['attr_value'] as $key=>$value) {
                        if ($value) {
                            $content = "管理员：" . \Yii::$app->user->identity->username . ",属性id为:" . $attr_id . " 属性的属性值列表，增加了属性值:" . $value . ",属性值权重值:" . $data['NewValue']['weight'][$key] . " 的记录";
                            $log_model->recordLog($content);
                        }
                    }

                }
            }
            return $this->success('保存成功');




        } else {

            $model->is_search = 0;

            $params = [
                'model'=>$show,
                'model_value'=>$model_value,
                'value_list'=>$value_list,
            ];
            return $this->render('edit', $params);
            //return $this->render('add', );
        }

    }
    /**
     * 删除属性集
     * @return string
     */
    public function actionDel()
    {
        $id = RequestHelper::get('id', 0, 'intval');
        $model = new Attribute();
        $list = $model->getInfo(['id'=>$id]);
        $re = $model->delAttribute($id);
        if ($re) {
            //日志
            $content = "管理员：".\Yii::$app->user->identity->username.",删除了id为:".$id.",后台显示名称为:".$list['admin_name'].",前台显示名称为:".$list['attr_name']." 的属性";
            $log_model = new Log();
            $log_model->recordLog($content);
            return $this->success('删除成功');
        } else {
            return $this->error('删除失败');
        }
    }
    /**
     * 删除属性集
     * @return string
     */
    public function actionDelValue()
    {
        $id = RequestHelper::get('id', 0, 'intval');
        //$model = new AttributeValue();
        $attr_value = AttributeValue::findOne($id);
        if (empty($attr_value)) {
            return $this->ajaxReturn(0, '删除失败,此记录不存在');
        } else {
            $re = $attr_value->delete();
            if ($re) {
                //日志
                $content = "管理员：".\Yii::$app->user->identity->username.",删除了属性id为:".$attr_value['attr_id'].",id为:".$id.",属性值为:".$attr_value['attr_value']." 的商品属性值";
                $log_model = new Log();
                $log_model->recordLog($content);
                return $this->ajaxReturn(200, '删除成功');
            } else {
                return $this->ajaxReturn(0, '删除失败');
            }
        }

        //return
    }
}
