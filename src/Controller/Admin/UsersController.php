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
            ->findAll();

        return $this->view('index', ['users' => $users]);
    }

    public function normal()
    {
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findExceptRole('ROLE_ADMIN');

        return $this->view('index', ['users' => $users]);
    }

    public function admin()
    {
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findByRole('ROLE_ADMIN');

        return $this->view('index', ['users' => $users]);
    }
}
