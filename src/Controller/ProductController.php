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

class ProductController extends  AbstractController
{
   
    
   /**
     * @Route("/showProduct/{id}", name="showProduct")
     */
    public function index(ProductRepository $productRepository): Response
    {
        $listeProduct = $productRepository->findAll();

        return $this->render('product/index.html.twig', [
            'listeProduct' => $listeProduct,
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
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute("success");
        }
       
        return $this->render('product/edit.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/product/add",name="ajoutProduct")
     */
    public function addProduct(Request $request, EntityManagerInterface $em): Response
    {
        $product = new Product();
        $builder = $this->createFormBuilder();
        $builder->add('name',TextType::class)
        ->add('price', IntegerType::class)
        ->add('slug', TextType::class)
        ->add(
            'category',
            EntityType::class,
            [
            'class' => Category::class,
            'choice_label' => 'name',
            'placeholder' => 'Choisir une catégorie',
            'label' => 'Categorie',
            ]
        )
        ->add(
            'save',
            SubmitType::class,
            ['label' => "Ajouter produit"]
        );

        $form = $builder->getForm();

        $form->handleRequest($request);
        // Soumit et valid
        if($form->isSubmitted() && $form->isValid())
        {
            $data = $form->getData();
            $product = new Product();
            $product->setName($data['name'])
            ->setPrice($data['price'])
            ->setSlug($data['slug'])
            ->setCategory($data['category']);

            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('success');
        }

        return $this->render('product/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}