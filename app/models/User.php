<?php

class User {

    // 用户登录错误提示.
    const LOGIN_STATUS_SUCCESS              = 100;
    const LOGIN_STATUS_USERNAME_NOT_EXISTS = 110;
    const LOGIN_STATUS_PASS_WRONG           = 120;
    const LOGIN_STATUS_FREEZE               = 130;

    // 用户注册状态
    const REG_STATUS_SUCCESS = 200;

    private $_statusMessage = [
        'login'=>[
            self::LOGIN_STATUS_SUCCESS                   => '成功登陆！',
            self::LOGIN_STATUS_USERNAME_NOT_EXISTS      => '用户名不存在！',
            self::LOGIN_STATUS_PASS_WRONG               => '登陆密码错误！',
            self::LOGIN_STATUS_FREEZE                   => '用户被冻结！',

        ],
        'reg'=>[
            self::REG_STATUS_SUCCESS        => '注册成功！'
        ]
    ];

    public function login($formData)
    {
        $result = DB::select("SELECT id, username, loginpwd FROM com_member WHERE username='{$formData['user']}'");
        $loginStatus = self::LOGIN_STATUS_SUCCESS;

        if(!$result) {
            $loginStatus = self::LOGIN_STATUS_USERNAME_NOT_EXISTS;
        }

        if($loginStatus == self::LOGIN_STATUS_SUCCESS && $result['password'] != $formData['loginpwd']) {
            $loginStatus = self::LOGIN_STATUS_PASS_WRONG;
        }

        return ['code'=>$loginStatus, 'msg'=>$this->_getStatusMessage('login', $loginStatus), 'data'=>$result];
    }

    public function register($formData)
    {
        
    }

    /**
     * 返回状态信息
     * @param $type
     * @param $code
     * @return string
     */
    private function _getStatusMessage($type, $code)
    {
        if(isset($this->_statusMessage[$type][$code])) {
            return $this->_statusMessage[$type][$code];
        } else {
            return '';
        }
    }
}
