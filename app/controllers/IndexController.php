<?php
class IndexController extends BaseController {
    public function index()
    {
        Assets::add('jquery', 'js/jquery.js');
    }

    public function login()
    {
        /**
         * 1. 禁止用户从其他渠道登陆
         */
        $formData = Input::all();
        $modelUser = new User();
        $result = $modelUser->login($formData);
        if($result['code'] == User::LOGIN_STATUS_SUCCESS) {
            // 存储到cookie, 下次不用访问
            print_r($result);
        } else {
            print_r($result);
        }
    }

    public function register() {

        $formData = Input::all();
        $modelUser = new User();
        $result = $modelUser->register($formData);
        if($result['code'] == User::REG_STATUS_SUCCESS) {
            print_r($result['data']);
        } else {
            print_r($result);
        }

    }
}