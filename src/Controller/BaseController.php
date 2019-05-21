<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class BaseController extends AbstractController
{
    protected $prefix = '';

    protected function view(string $view, array $parameters = [], ?Response $response = null)
    {
        $view = sprintf('%s/%s.html.twig', $this->prefix, $view);
        return $this->render($view, $parameters, $response);
    }
}
