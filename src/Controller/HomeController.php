<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;

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
