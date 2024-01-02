<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/index')]
    public function index () {
        return new Response("Hello Wolrd");
    }
    #[Route('/name')]
    public function test () {
        return $this->render("test/test.html.twig", ["name" => "Lucas"]);
    } 
}