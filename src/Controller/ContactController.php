<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    public function contact(): Response
    {
        $titre = "Page contact";
        return $this->render('contact.html.twig', [
            "titre"=>$titre
        ]);
    }
    public function mail(): Response
    {
        return new Response(
            '<html><h1>Bienvenue dans la page Contact </h1></html>'
        );
    }
}
