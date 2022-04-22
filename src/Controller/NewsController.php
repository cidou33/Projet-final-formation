<?php

namespace App\Controller;

use App\Entity\News;
use App\Form\NewsType;
use App\Repository\NewsRepository;
use App\services\imgHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class NewsController extends AbstractController
{


    #[Route('/nouvel-article', name: 'addNews', methods: ['GET', 'POST'])]
    public function addNews(NewsRepository $newsRepository, Request $request, SluggerInterface $slugger, imgHelper $imgHelper){
        $user = $this->getUser();
        if($user == null){
            $connect = 0;
        }else{
            $connect = 1;
        };
        $article = new News();
        $articleForm = $this->createForm(NewsType::class, $article);
        $articleForm->handleRequest($request);
        if($articleForm->isSubmitted() && $articleForm->isValid()){
            $article->setActive(true);
            $article->setCreateDate(new \DateTime('now'));
            $img = $articleForm->get('imgNews')->getData();
            //$imgBrut = $articleForm->get('imgNews')->getData();
            //$img = $imgHelper->img($imgBrut);
            $originalFilename = pathinfo($img->getClientOriginalName(), PATHINFO_FILENAME);
            dd($originalFilename);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.md5(uniqid()).'.'.$img->guessExtension();
            $img->move($this->getParameter('uploadNews'), $newFilename);
            $article->setImgNews($newFilename);
            $newsRepository->add($article);
            return $this->redirectToRoute('showNews', [
                'user' => $user,
                'connect' => $connect
            ]);
        }
        return $this->render('news/addNews.html.twig', [
            'newsForm' => $articleForm->createView(),
            'user' => $user,
            'connect' => $connect
        ]);
    }

    #[Route('/les-news', name: 'showNews', methods: ['GET', 'POST'])]
    public function showNews(NewsRepository $newsRepository){
        $news = $newsRepository->findAll();
        $user = $this->getUser();
        if($user == null){
            $connect = 0;
        }else{
            $connect = 1;
        };
        return $this->render('news/news.html.twig', [
            'news' => $news,
            'user' => $user,
            'connect' => $connect
        ]);

    }

    #[Route('supprimer-article/{id}', name: 'deleteNews', methods: ['GET', 'POST'])]
    public function deleteNews(NewsRepository $newsRepository, Request $request, $id){
        $article = $newsRepository->findOneBy(['id'=>$id]);
        $newsRepository->remove($article);
        return $this->redirectToRoute('showNews');
    }
}

