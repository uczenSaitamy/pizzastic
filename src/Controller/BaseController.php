<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;

class BaseController extends AbstractController
{
    protected $prefix = '';

    protected function view(string $view, array $parameters = [], ?Response $response = null)
    {
        $view = sprintf('%s/%s.html.twig', $this->prefix, $view);
        return $this->render($view, $parameters, $response);
    }

    protected function sendConfirmationLink(\Swift_Mailer $mailer, User $user)
    {
        $message = (new \Swift_Message('Confirm your account'))
            ->setFrom($_ENV['MAIL_USERNAME'])
            ->setTo($user->getEmail())
            ->setBody(
                $this->renderView('emails/confirmation.html.twig', [
                    'user' => $user
                ]),
                'text/html'
            );

        return $mailer->send($message);
    }
}
