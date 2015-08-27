<?php
/**
 * Class LoginForm
 * PHP Version 5
 * @category  PHP
 * @package   Admin
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @filename  ${FILE_NAME}
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @datetime  ${DATE} ${TIME}
 * @link      http://www.i500m.com/
 */

namespace common\models;

use Yii;
use yii\base\Model;


/**
 * Class LoginForm
 * @category  PHP
 * @package   Admin
 * @author    lichenjun <lichenjun@iyangpin.com>
 * @filename  ${FILE_NAME}
 * @copyright 2015 www
 * @license   http://www.i500m.com/ i500m license
 * @datetime  ${DATE} ${TIME}
 * @link      http://www.i500m.com/
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user = false;


    /**
     * 简介：@inheritdoc
     * @return array
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * 简介：
     * Validates the password.
     * This method serves as the inline validation for password.
     * @param string $attribute the attribute currently being validated
     * @param array  $params    the additional name-value pairs given in the rule
     * @return null
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, '用户名或密码错误.');
            }
        }
    }

    /**
     * 简介：
     * @author  lichenjun@iyangpin.com。
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'username' => '用户名',
            'name' =>'姓名',
            'password'=>'密码',
            'rememberMe' => '记住我',
            'status' =>'状态',
        );
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {

        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = Admin::findByUsername($this->username);
        }

        return $this->_user;
    }

    /**
     * 简介：
     * @return mixed
     */
    public static function getDB()
    {
        return \Yii::$app->db_500m;
    }
}
