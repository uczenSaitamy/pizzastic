<?php

namespace App\Controller\User;

use App\Controller\BaseController;
use App\Form\UserDataType;
use Symfony\Component\HttpFoundation\Request;

class AccountController extends BaseController
{
    protected $prefix = 'account';

    public function index()
    {
        if ($user = $this->getUser()) {
            $form = $this->createForm(UserDataType::class, $user);
        }
        return $this->view('index', ['form' => $form->createView(), 'user' => $user]);
    }

    public function store(Request $request)
    {
        if ($user = $this->getUser()) {
            $form = $this->createForm(UserDataType::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', 'Your data has been saved.');
            }
        }
        return $this->view('index', ['form' => $form->createView(), 'user' => $user]);
    }
}
