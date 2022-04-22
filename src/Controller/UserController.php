<?php

namespace App\Controller;

use App\Repository\TranscriptionsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/acceuil', name: 'homePage', methods: ['GET', 'POST'])]
    public function redirectHomePage(){

        $user = $this->getUser();

        if($user == null){
            $connect = 0;
        }else{
            $connect = 1;
        };
        return $this->render('accueil.html.twig', [
            'user' => $user,
            'connect' => $connect,
        ]);
    }

    #[Route('/toto/{id}', name: 'toto', methods: ['GET', 'POST'])]
    public function toto(TranscriptionsRepository $transcriptionsRepository, $id){
        $transcription = $transcriptionsRepository->findOneBy(['id' => $id]);
        return $this->render('toto.html.twig', [
            'transcription' => $transcription
        ]);
    }

    #[Route('/tutu', name: 'tutu', methods: ['GET', 'POST'])]
    public function tutu(TranscriptionsRepository $transcriptionsRepository){
        $transcriptions = $transcriptionsRepository->findBy([],['songName'=>'DESC']);
        return $this->render('toto.html.twig', [
            'transcriptions' => $transcriptions
        ]);
    }
}