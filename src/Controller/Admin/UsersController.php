<?php

namespace App\Controller\Admin;

use App\Controller\BaseController;
use App\Entity\User;

class UsersController extends BaseController
{
    protected $prefix = 'admin/users';

    public function index()
    {
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findRoleUser();

        return $this->view('index', ['users' => $users]);
    }
}
