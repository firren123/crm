<?php
/**
 * 业务员-model
 *
 * PHP Version 5
 *
 * @category  ADMIN
 * @package   MODEL
 * @author    zhengyu <zhengyu@iyangpin.com>
 * @time      15/4/27 13:34
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      zhengyu@iyangpin.com
 */

namespace backend\models\i500m;

/**
 * 业务员-model
 *
 * @category ADMIN
 * @package  MODEL
 * @author   zhengyu <zhengyu@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     zhengyu@iyangpin.com
 */
class Business extends I500Base
{

    /**
     * 表名
     *
     * @return string
     */
    public static function tableName()
    {
        return '{{%crm_business}}';
    }

    /*/**
     * 简介：
     * @author  lichenjun@iyangpin.com。
     * @return array
     */
    /*public function attributeLabels()
    {
        return array(
            'name'  =>'姓名',
            'email' => '邮箱',
            'status'=>'状态',
            'bc_id' => '分公司',
            'deptment_id'    => '部门',
            'day_total'      => '每日拜访目标',
            'openshop_total' => '每月开店数',
            'sales_total'    => '销售目标',
            'duty_id'        => '职务',
            'pwd'   => '密码',
            'imie'  => '手机标示',
            'mobile'=>'手机号'
        );
    }*/
    /**
     *
     * 简介：定义过滤规则
     * @author  lichenjun@iyangpin.com。
     * @return array
     */
    /*public function rules()
    {
        return [
            //不可为空的字段
            [['name','bc_id','mobile','status','deptment_id','duty_id','pwd','imie',
                'day_total',
                'openshop_total',
                'sales_total'
            ],'required'],
            //['email','email'],
            ['mobile','match','pattern'=>'/^1[0-9]{10}$/','message'=>'{attribute}必须为1开头的11位纯数字'],
        ];
    }*/



}