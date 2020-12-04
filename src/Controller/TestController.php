<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends  AbstractController
{
    /**
     * @Route("/",name="index")
     * */
    public function index()
    {
        return $this->render('base.html.twig');
    }

    /**
     * @Route("/test",name="test")
     **/
    public function test()
    {
        $tabs = ['chaib', 'remy' , 'bernard'];
        return $this->render("test.html.twig", ['tabs' => $tabs]);
    }
}