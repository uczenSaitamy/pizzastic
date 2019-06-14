<?php

namespace App\Controller;

class HomeController extends BaseController
{
    protected $prefix = 'home';

    public function index()
    {
        return $this->view('index', [
            'controller_name' => 'HomeController',
        ]);
    }
}
