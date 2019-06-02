<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Form\RegistrationFormType;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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

    public function register(): Response
    {
        if ($this->isGranted('ROLE_USER')) {
            return new RedirectResponse('/');
        }
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);

        return $this->view('register', ['form' => $form->createView()]);
    }

    public function store(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($encoder->encodePassword($user, $form->get('plainPassword')->getData()));
            $user->setRoles($user->getRoles());
            $user->setConfirmToken();
            $user->setTimestamps();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // TODO SEND EMAIL
            $this->addFlash('success', 'Please confirm your account via email link!');
            return $this->view('login');
        }
        return $this->view('register', ['form' => $form->createView()]);
    }
}
