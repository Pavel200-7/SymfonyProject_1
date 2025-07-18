<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

final class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(MailerInterface $mailer): Response
    {


        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
}
