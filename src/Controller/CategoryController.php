<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    /**
     * @Route("/admin/category", name="category")
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        // $this->denyAccessUnlessGranted('IS_AUTENTICATED_FULLY')
        $listeCategory = $categoryRepository->findAll();

        return $this->render('category/index.html.twig', [
            'listeCategory' => $listeCategory,
        ]);
    }

    /**
     * @Route("/admin/category/product/{id}", name="categoryProduct")
     */
    public function productByCategory(ProductRepository $productRepository, $id): Response
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);

        $listeProduct = $productRepository->findBy(['category' => $category]);

        return $this->render('category/categoryProducts.html.twig', [
            'listeProduct' => $listeProduct,
        ]);
    }

    /**
     * @Route("/admin/category/add",name="ajoutCategory")
     */
    public function addCategory(Request $request, EntityManagerInterface $em)
    {
        //$user = $this->getUser();
        //dd($user->getRoles());
        //$this->denyAccessUnlessGranted('ROLE_ADMIN');
        $category = new Category;
        $form = $this->createForm(CategoryFormType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($category);
            $em->flush();

            $this->addFlash('success', 'Catégorie ajoutée avec succès');

            return $this->redirectToRoute("category");
        }

        return $this->render('category/add.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/admin/category/edit/{id}",name="editCategory")
     */
    public function editCategory(Request $request, EntityManagerInterface $em, $id)
    {
        $category = $em->getRepository(Category::class)->find($id);
        $form = $this->createForm(CategoryFormType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute("success");
        }

        return $this->render('category/edit.html.twig', ['form' => $form->createView()]);
    }
}
