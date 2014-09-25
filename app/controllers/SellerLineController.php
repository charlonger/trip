<?php

class SellerLineController extends BaseController {
    protected $layout = 'layouts.line';

    public function index() {
        $this->layout->content = View::make('lines');
    }
}