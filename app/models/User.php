<?php

class User {

    // 用户登录错误提示.
    const LOGIN_STATUS_SUCCESS              = 100;
    const LOGIN_STATUS_USERNAME_NOT_EXISTS = 110;
    const LOGIN_STATUS_PASSWORD_WRONG           = 120;
    const LOGIN_STATUS_FREEZE               = 130;

    // 用户注册状态
    const REG_STATUS_SUCCESS = 200;
    const REG_STATUS_USERNAME_EXISTS = 210;
    const REG_STATUS_DIFF_PASSWORD_TWICE = 220;
    const REG_STATUS_INSERT_BAD = 230;
    const REG_STATUS_USERNAME_EMPTY = 240;
    const REG_STATUS_PASSWORD_EMPTY = 250;

    // 登陆后cookie键名
    const LOGIN_AUTH_NAME = 'login_auto';

    private $_statusMessage = [
        'login'=>[
            self::LOGIN_STATUS_SUCCESS                   => '成功登陆！',
            self::LOGIN_STATUS_USERNAME_NOT_EXISTS      => '用户名不存在！',
            self::LOGIN_STATUS_PASSWORD_WRONG           => '登陆密码错误！',
            self::LOGIN_STATUS_FREEZE                   => '用户被冻结！',
        ],
        'reg'=>[
            self::REG_STATUS_SUCCESS                    => '注册成功！',
            self::REG_STATUS_USERNAME_EXISTS            => '用户名已经存在！',
            self::REG_STATUS_DIFF_PASSWORD_TWICE        => '两次输入的密码不同！',
            self::REG_STATUS_INSERT_BAD                 => '添加用户数据失败！',
            self::REG_STATUS_USERNAME_EMPTY            => '用户名为空！',
            self::REG_STATUS_PASSWORD_EMPTY             => '密码为空！'
        ]
    ];

    public function login($params)
    {
        $result = $this->getUser(['username'=>$params['username']]);
        $loginStatus = self::LOGIN_STATUS_SUCCESS;

        if(!$result) {
            $loginStatus = self::LOGIN_STATUS_USERNAME_NOT_EXISTS;
        }

        if($loginStatus == self::LOGIN_STATUS_SUCCESS && $result->loginpwd != $params['password']) {
            $loginStatus = self::LOGIN_STATUS_PASSWORD_WRONG;
        }

        // 成功登陆保存登陆信息.
        if($loginStatus == self::LOGIN_STATUS_SUCCESS) {
            $this->_rememberLogin($result);
        }

        return ['code'=>$loginStatus, 'msg'=>$this->_getStatusMessage('login', $loginStatus), 'data'=>$result];
    }

    public function register($params)
    {

        $regStatus = self::REG_STATUS_SUCCESS;

        if($regStatus == self::REG_STATUS_SUCCESS && empty($params['username'])) {
            $regStatus = self::REG_STATUS_USERNAME_EMPTY;
        }

        if($regStatus == self::REG_STATUS_SUCCESS && (empty($params['password']) || empty($params['password2']))) {
            $regStatus = self::REG_STATUS_PASSWORD_EMPTY;
        }

        if($regStatus == self::REG_STATUS_SUCCESS && $params['password'] != $params['password2']) {
            $regStatus = self::REG_STATUS_DIFF_PASSWORD_TWICE;
        }

        if($regStatus == self::REG_STATUS_SUCCESS) {
            $result = $this->getUser(['username'=>$params['username']]);
            if($result) {
                $regStatus = self::REG_STATUS_USERNAME_EXISTS;
            }
        }


        if($regStatus == self::REG_STATUS_SUCCESS ) {
            $sql = "INSERT INTO com_member(username, loginpwd) VALUES('{$params['username']}', '{$params['password']}')";
            if(!DB::insert($sql)) {
                $regStatus == self::REG_STATUS_INSERT_BAD;
            }

            $this->_rememberLogin($this->getUser(['username'=>$params['username']]));
        }

        return ['code'=>$regStatus, 'msg'=>$this->_getStatusMessage('reg', $regStatus)];
    }

    public function updateUser($id, $params) {
        $updateStatus = self::REG_STATUS_SUCCESS;
        $result = [];

        if($params['password'] != $params['password2']) {
            $updateStatus = self::REG_STATUS_DIFF_PASSWORD_TWICE;
        }

        if($updateStatus == self::REG_STATUS_SUCCESS) {
            unset($params['password2']);
            $result = DB::table('com_member')
                ->where('id', $id)
                ->update($params);
        }

        return $result;
    }

    public function checkLogin() {
        $user = [];
        $loginCookie = Cookie::get(self::LOGIN_AUTH_NAME);

        if(!empty($loginCookie)) {
            $cookieArr = explode(',', Crypt::decrypt(Cookie::get(self::LOGIN_AUTH_NAME)));
            if(sizeof($cookieArr)>0) {
                $user = $this->getUser(['id'=>$cookieArr[0], 'username'=>$cookieArr[1]]);
            }
        }

        return $user;
    }

    private function _rememberLogin($user) {
        $data = implode(',', [$user->id, $user->username]);
        Cookie::make(self::LOGIN_AUTH_NAME, Crypt::encrypt($data), 3600 * 24);
    }

    public function logout(){
        Cookie::make(self::LOGIN_AUTH_NAME, "", 0);
    }

    public function getUser($params) {
        $query = "";
        if(sizeof($params)>0) {
            foreach($params as $k=>$v) {
                switch($k) {
                    case "username":
                        $query .= " AND username='{$v}'";
                        break;

                    case "id":
                        $query .= " AND id={$v}";
                        break;
                }
            }
        }

        $result = DB::select("SELECT id, username, loginpwd FROM com_member WHERE 1 {$query}");
        return sizeof($result == 1) ? current($result) : $result;
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
