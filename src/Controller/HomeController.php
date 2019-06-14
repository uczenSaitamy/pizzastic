<?php

namespace App\Controller;

class HomeController extends BaseController
{
    protected $prefix = 'home';

    public function index(\Swift_Mailer $mailer)
    {
        $message = (new \Swift_Message('Confirm your account'))
        ->setFrom('tes@tes.com')
        ->setTo('testmail@mail.mail')
        ->setBody(
            $this->renderView('emails/confirmation.html.twig', ['header' => 'Hi, there', 'body' => 'this is body']),
            'text/html'
        );

        $mailer->send($message);

        return $this->view('index', [
            'controller_name' => 'HomeController',
        ]);
    }
}
