<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
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
    public function test(ProductRepository $productRepository)
    {
        $products = $productRepository->findAll();
   
        return $this->render("test.html.twig", ['products' => $products]);
    }
}