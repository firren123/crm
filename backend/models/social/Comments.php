<?php
/**
 * 简介1
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   Admin
 * @filename  Comments.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/8/13 下午2:33
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\models\social;

/**
 * CREATE TABLE `i500_post_comments` (
    `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
    `mobile` char(11) NOT NULL DEFAULT '' COMMENT '手机号(用户唯一标示)',
    `post_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '帖子ID',
    `content` varchar(255) NOT NULL DEFAULT '' COMMENT '评论内容',
    `thumbs` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '点赞数',
    `status` tinyint(1) NOT NULL DEFAULT '2' COMMENT '是否禁用 1=是 2=否',
    `is_deleted` tinyint(1) NOT NULL DEFAULT '2' COMMENT '是否删除 1=是 2=否',
    `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
    PRIMARY KEY (`id`),
    KEY `idx_mobile` (`mobile`),
    KEY `idx_post_id` (`post_id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='帖子评论表'
 */
/**
 * Class Comments
 * @category  PHP
 * @package   Admin
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class Comments extends SocialBase
{
    /**
     * 简介：连接数据库
     * @author  lichenjun@iyangpin.com。
     * @return string
     */
    public static function tableName()
    {
        return "{{%i500_post_comments}}";
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
            'post_id' =>'帖子ID',
            'content'=>'评论内容',
            'thumbs' => '点赞数',
            'status' =>'是否禁用',
            'is_deleted' =>'是否删除',
            'create_time' =>'创建时间',
        );
    }
}
