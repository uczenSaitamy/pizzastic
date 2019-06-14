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

    public function store(Request $request, UserPasswordEncoderInterface $encoder, \Swift_Mailer $mailer)
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
            $this->sendConfirmationLink($mailer, $user);

            // TODO SEND EMAIL
            $this->addFlash('success', 'Please confirm your account via email link!');
            return $this->redirectToRoute('login');
        }
        return $this->view('register', ['form' => $form->createView()]);
    }

    public function confirm($token)
    {
        if (!$user = $this->getDoctrine()->getRepository(User::class)->findOneByToken($token)) {
            $this->addFlash('errors', 'Unable to confirm account. Bad token provided.');
            return $this->redirectToRoute('login');
        }

        $em = $this->getDoctrine()->getManager();
        $em->getConnection()->beginTransaction();
        try {
            $user->setConfirmToken(false);

            $em->persist($user);
            $em->flush();
            $em->getConnection()->commit();
        } catch (Exception $e) {
            $em->getConnection()->rollBack();
            // TODO LOG EXCEPTION $e
            $this->addFlash('errors', 'Unable to confirm account. Bad token provided.');
        }
        $this->addFlash('success', 'Your account has been successfully confirmed.');
        return $this->redirectToRoute('login');
    }
}
