<?php
/**
 * 新500m后台-上传类
 *
 * PHP Version 5
 *
 * @category  ADMIN
 * @package   UPLOAD
 * @author    linxinliang <linxinliang@iyangpin.com>
 * @time      2015-04-22 11:09
 * @copyright 2015 灵韬致胜（北京）科技发展有限公司
 * @license   http://www.i500m.com license
 * @link      linxinliang@iyangpin.com
 */

namespace backend\modules\admin\controllers;

use Yii;
use common\helpers\FastDFSHelper;
use yii\web\Controller;

/**
 * NEWS
 *
 * @category ADMIN
 * @package  UPLOAD
 * @author   linxinliang <linxinliang@iyangpin.com>
 * @license  http://www.i500m.com/ license
 * @link     linxinliang@iyangpin.com
 */
class UploadController extends Controller
{
    public $enableCsrfValidation = false;

    /**
     * 上传图片
     * @return array
     */
    public function actionUploadImg()
    {
        $rs = ['state'=>'ERROR'];
        $img_host = Yii::$app->params['imgHost'];
        $fastDfs = new FastDFSHelper();
        $rs_data = $fastDfs->fdfs_upload('Filedata');
        if ($rs_data) {
            $rs['state'] = 'SUCCESS';
            $rs['url'] = $img_host.$rs_data['group_name'].'/'.$rs_data['filename'];
        }
        return json_encode($rs);
    }
}
