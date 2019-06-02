<?php

namespace App\Controller\Admin;

use App\Controller\BaseController;

class AdminController extends BaseController
{
    protected $prefix = 'admin';

    public function index()
    {
        return $this->view('index');
    }
}
