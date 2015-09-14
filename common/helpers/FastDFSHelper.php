<?php

/**
 * Class FastDFSHelper
 * 上传图片类
 */
namespace common\helpers;

use Yii;

/**
 * Class FastDFSHelper
 * @category  PHP
 * @package   FastDFSHelper
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @link      http://www.i500m.com/
 */
class FastDFSHelper
{
    protected $server;
    protected $storage;
    protected $config;

    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->storage = fastdfs_tracker_query_storage_store();
        $this->server = fastdfs_connect_server($this->storage['ip_addr'], $this->storage['port']);
        if (!$this->server) {
            echo "<pre>";
            echo fastdfs_get_last_error_no();
            echo fastdfs_get_last_error_info();
            //echo ('连接fastdfs服务失败', fastdfs_get_last_error_no(), "error info: " . fastdfs_get_last_error_info());
            exit(1);
        }
        $this->storage['sock'] = $this->server['sock'];
    }

    /**
     * 简介：通过文件路径上传文件
     * @author  lichenjun@iyangpin.com。
     * @param string $filename 文件名
     * @return bool
     */
    public function fdfs_upload_by_filename($filename)
    {
        $file_info = fastdfs_storage_upload_by_filename($filename);
        if ($file_info) {
            return $file_info;
        }
        return false;
    }

    /**
     * 上传文件
     * @param string $input_name 文件名
     * @return null
     */
    public function fdfs_upload($input_name)
    {
        $file_tmp = $_FILES[$input_name]['tmp_name'];
        $real_name = $_FILES[$input_name]['name'];
        $filename = dirname($file_tmp) . "/" . $real_name;
        @rename($file_tmp, $filename);
        return $this->fdfs_upload_by_filename($filename);
    }

    /**
     * 简介：
     * @author  lichenjun@iyangpin.com。
     * @param string $file_tmp  x
     * @param string $real_name x
     * @return bool
     */
    public function fdfs_upload_name_size($file_tmp, $real_name)
    {
        $filename = dirname($file_tmp) . "/" . $real_name;
        @rename($file_tmp, $filename);
        return $this->fdfs_upload_by_filename($filename);
    }


    /**
     * 下载文件
     * @param string $group_name x
     * @param string $file_id    x
     * @return null
     */
    public function fdfs_down($group_name, $file_id)
    {
        $file_content = fastdfs_storage_download_file_to_buff($group_name, $file_id);
        return $file_content;
    }

    /**
     * 删除文件
     * @param string $group_name x
     * @param string $file_id    x
     * @return null
     */
    public function fdfs_del($group_name, $file_id)
    {
        return fastdfs_storage_delete_file($group_name, $file_id);
    }

    /**
     * 上传水印文件
     * @param string $input_name x
     * @return null
     */
    public function fdfs_upload_water($input_name)
    {
        import("ORG.Util.Image");
        $Image = new Image();
        $base_dir = $GLOBALS['pic_base_dir'];
        $file_tmp = $_FILES[$input_name]['tmp_name'];
        $real_name = $_FILES[$input_name]['name'];
        $filename = dirname($file_tmp) . "/" . $real_name;
        $waterfilename = dirname($file_tmp) . "/water_" . $real_name;
        @rename($file_tmp, $filename);
        $Image->water($filename, $GLOBALS['waterpic'], $waterfilename, 90);
        return $this->fdfs_upload_by_filename($waterfilename);
    }

    /**
     * 简介：
     * @author  lichenjun@iyangpin.com。
     * @return null
     */
    public function upload()
    {
        $fds = new FastDFSHelper();
        $data = $fds->fdfs_upload('Filedata');
        echo json_encode($data);
    }
}
