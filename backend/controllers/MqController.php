<?php
/**
 * 简介1
 *
 * PHP Version 5
 *
 * @category  PHP
 * @package   admin
 * @filename  MqController.php
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @copyright 2015 www.i500m.com
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/8/9 上午10:10
 * @version   SVN: 1.0
 * @link      http://www.i500m.com/
 */


namespace backend\controllers;

use yii\web\Controller;
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class MqController
 * @category  PHP
 * @package   admin
 * @filename  MqController.php
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @datetime  15/8/9 上午10:10
 * @link      http://www.i500m.com/
 */
class MqController extends Controller
{

    public $conn = null;
    public $ch = '';
    public $host = '118.186.247.55';
    public $port = '5672';
    public $user = '500m';
    public $pass = 'gbjY51Rpstx';
    public $vhost = '500m';
    public $channel = 'sms';

    public function init()
    {
        parent::init();
        $this->conn = new AMQPConnection($this->host, $this->port, $this->user, $this->pass, $this->vhost);
        $this->ch = $this->conn->channel($this->channel);
    }

    public function actionSendMq()
    {
        $msg_body = 'xxxxxxxxxx';
        $exchange = 'dd';
        $msg = new AMQPMessage($msg_body);
        $this->ch->batch_basic_publish($msg, $exchange);

        $msg2 = new AMQPMessage($msg_body);
        $this->ch->batch_basic_publish($msg2, $exchange);
    }

    public function actionGetMq()
    {

    }
}