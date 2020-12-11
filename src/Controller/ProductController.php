<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use App\Form\ProductFormType;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductController extends  AbstractController
{
   
    
   /**
     * @Route("/showProduct/{id}", name="showProduct")
     */
    public function index(Product $product,$id , EntityManagerInterface $em): Response
    {
        
        return $this->render('product/detailProduit.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/product/edit/{id}", name="editProduct")
     */
    public function editProduct(Request $request , EntityManagerInterface $em , $id) : Response
    {
        $product = $em->getRepository(Product::class)->find($id);
        $form = $this->createForm(ProductFormType::class,$product);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $product= $form->getData();
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute("success");
        }
       
        return $this->render('product/edit.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/product/add",name="ajoutProduct")
     */
    public function addProduct(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $product = new Product;
        $form = $this->createForm(ProductFormType::class,$product);
     
        $form->handleRequest($request);
        
        // Soumit et valid
        if($form->isSubmitted() && $form->isValid())
        {
            $product->setSlug($slugger->slug($product->getName()));


            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('success');
        }

        return $this->render('product/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/product/delete/{id}", name= "deleteProduct")
     */
    public function deleteProduct(ProductRepository $productRepository,EntityManagerInterface $em , $id) : Response
    {
        $product = $productRepository->find($id);
        $em->remove($product);
        $em->flush();
        return $this->redirectToRoute('success');
    }
}