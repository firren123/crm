<?php
/**
 * 简介1
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Admin
 * @filename  Post.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/8/13 上午11:43
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\models\social;


/**
 * Class Post
 * @category  PHP
 * @package   Admin
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class Post extends SocialBase
{
    public $content;
    public $comment_num;
    /**
     * 简介：连接表
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public static function tableName()
    {
        return "{{%i500_post}}";
    }

    /**
     * 简介：定义过滤规则
     * @author  lichenjun@iyangpin.com。
     * @return array
     */
    public function rules()
    {
        return [
            //不可为空的字段
            [['mobile','forum_id','title','top','status','content','post_img'],'required'],
            ['mobile','match','pattern'=>'/^1\d{10}/']
        ];
    }
    /**
     * 简介：
     * @author  lichenjun@iyangpin.com。
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'mobile' => '手机号',
            'forum_id' =>'版块名称',
            'title'=>'帖子标题',
            'post_img' => '帖子图片',
            'thumbs' =>'点赞数',
            'views' =>'浏览量',
            'top' => '是否置顶',
            'status' =>'是否禁用',
            'is_deleted' =>'是否删除',
            'create_time' =>'创建时间',
            'content' => '内容'
        );
    }
}
