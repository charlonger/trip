<?php
class IndexController extends BaseController {
    public function index()
    {
        return View::make('index');
    }

    public function login()
    {
            $formData = Input::all();
            $modelUser = new User();

            $result = $modelUser->login($formData);
            if($result['code'] == User::LOGIN_STATUS_SUCCESS) {
            print_r($result['data']);
        } else {
            print_r($result);
        }
    }

    public function register() {
        $formData = Input::all();
        $modelUser = new User();


    }
}