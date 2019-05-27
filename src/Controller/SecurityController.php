<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\User;

class SecurityController extends BaseController
{
    protected $prefix = 'security';

    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->isGranted('ROLE_USER')) {
            return new RedirectResponse('/');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->view('login', ['last_username' => $lastUsername, 'error' => $error]);
    }

    public function register(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->isGranted('ROLE_USER')) {
            return new RedirectResponse('/');
        }

        return $this->view('register');
    }

    public function store(Request $request, ValidatorInterface $validator)
    {
        $data = $request->request->all();
        unset($data['_csrf_token']);
        $errors = $validator->validate($data, new Assert\Collection([
            'email' => new Assert\Email(),
            'password' => new Assert\Length(['min' => 6]),
            '_terms' => new Assert\NotBlank(),
            ]));
        dd($errors);
    }
}
