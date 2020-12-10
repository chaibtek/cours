<?php

namespace App\Controller;
use App\Entity\Category;
use App\Form\CategoryFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="category")
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        $listeCategory = $categoryRepository->findAll();

        return $this->render('category/index.html.twig', [
            'listeCategory' => $listeCategory,
        ]);
    }

       /**
     * @Route("/category/add",name="ajoutCategory")
     */
    public function addCategory(Request $request, EntityManagerInterface $em )
    {
        $category = new Category;
        $form = $this->createForm(CategoryFormType::class,$category);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('success');
        }
       
        return $this->render('category/add.html.twig', ['form' => $form->createView()]);
    }

  

}
