<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Form\CategoriesType;
use App\Repository\CategoriesRepository;
use App\Repository\TranscriptionsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoriesController extends AbstractController
{
    #[Route('/nouvelle-categorie', name: 'addCategory', methods: ['GET', 'POST'])]
    public function addCategory(CategoriesRepository $categoriesRepository, Request $request, SluggerInterface $slugger){
        $user = $this->getUser();
        $connect = $this->getUser() == null;
        $categories = $categoriesRepository->findAll();
        $category =new Categories();
        $categoryForm = $this->createForm(CategoriesType::class, $category);
        $categoryForm->handleRequest($request);
        if($categoryForm->isSubmitted() && $categoryForm->isValid()){
            $img = $categoryForm->get('img_category')->getData();
            $originalFilename = pathinfo($img->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename= $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.md5(uniqid()).'.'.$img->guessExtension();
            $img->move($this->getParameter('uploadCategories'), $newFilename);
            $category->setImgCategory($newFilename);
            $categoriesRepository->add($category);
            return $this->redirectToRoute('addCategory', [
                'user' => $user,
                'connect' => $connect,
                'categories' => $categories

            ]);
        }
        return $this->render('categories/addCategory.html.twig', [
            'categoryForm' => $categoryForm->createView(),
            'user' => $user,
            'connect' => $connect,
            'categories' => $categories
            ]);
    }

    #[Route('supprimer-categorie/{id}', name: 'deleteCategory', methods: ['GET', 'POST'])]
    public function deleteCategory(CategoriesRepository $categoriesRepository, Request $request, $id){
        $category = $categoriesRepository->findOneBy(['id'=>$id]);
        $categoriesRepository->remove($category);
        return $this->redirectToRoute('addCategory');
    }

    #[Route('/modifier-categorie/{id}', name: 'updateCategory', methods: ['GET', 'POST'])]
    public function updateCategory(CategoriesRepository $categoriesRepository, Request $request, SluggerInterface $slugger, $id){
        $user = $this->getUser();
        $connect = $this->getUser() == null;
        $categories = $categoriesRepository->findAll();
        $category = $categoriesRepository->findOneBy(['id'=>$id]);
        $oldImg = $category->getImgCategory();
        $categoryForm = $this->createForm(CategoriesType::class, $category);
        $categoryForm->handleRequest($request);
        if($categoryForm->isSubmitted() && $categoryForm->isValid()){
            $img = $categoryForm->get('img_category')->getData();
            if($img==null) {
                $img = $oldImg;
                $category->setImgCategory($img);
                $categoriesRepository->add($category);
                return $this->redirectToRoute('addCategory', [
                    'user' => $user,
                    'connect' => $connect
                ]);
            }
            $originalFilename = pathinfo($img->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename= $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.md5(uniqid()).'.'.$img->guessExtension();
            $img->move($this->getParameter('uploadCategories'), $newFilename);
            $category->setImgCategory($newFilename);
            $categoriesRepository->add($category);
            return $this->redirectToRoute('addCategory', [
                'user' => $user,
                'connect' => $connect,


            ]);
        }
        return $this->render('categories/addCategory.html.twig', [
            'categoryForm' => $categoryForm->createView(),
            'user' => $user,
            'connect' => $connect,
            'categories' => $categories,
            'category' => $category
        ]);
    }

}