<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends  AbstractController
{
     
    /**
     * @Route("/",name="index")
     * */
    public function index(CategoryRepository $categoryRepository) : Response
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository(Product::class)->findAll();
  
        return $this->render('index.html.twig',[ 'products' => $product]);
    }
    
     /**
     * @Route("/produit/{id}-{slug}", name="vueProduit")
     */
    public function detailProduit(Product $product): Response
    {
        return $this->render('product/detailProduit.html.twig', [
            'product' => $product,
        ]);
    }
    
    

    /**
     * @Route("/success", name="success")
     */
    
    public function success()
    {
        return $this->render('success.html.twig');
    }

}