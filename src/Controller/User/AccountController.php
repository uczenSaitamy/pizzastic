<?php

namespace App\Controller\User;

use App\Controller\BaseController;
use App\Form\UserDataType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Config\Definition\Exception\Exception;

class AccountController extends BaseController
{
    protected $prefix = 'account';

    public function index()
    {
        $user = $this->getUser();
        $form = $this->createForm(UserDataType::class, $user);

        return $this->view('index', ['form' => $form->createView(), 'user' => $user]);
    }

    public function store(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(UserDataType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->getConnection()->beginTransaction();
            try {
                $em->persist($user);
                $em->flush();
                $em->getConnection()->commit();
            } catch (Exception $e) {
                $em->getConnection()->rollBack();
                // TODO LOG EXCEPTION $e
                $this->addFlash('error', 'Your data has not been saved.');
            }

            $this->addFlash('success', 'Your data has been saved.');
        }

        return $this->view('index', ['form' => $form->createView(), 'user' => $user]);
    }
}
