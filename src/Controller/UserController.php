<?php

namespace App\Controller;

use App\Repository\TranscriptionsRepository;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/acceuil', name: 'homePage', methods: ['GET', 'POST'])]
    public function redirectHomePage(){

        $user = $this->getUser();
        $connect = $this->getUser() == null;
        return $this->render('accueil.html.twig', [
            'user' => $user,
            'connect' => $connect,
        ]);
    }

    #[Route('/modifier-favori/{id}',name: 'modifyFavori', methods: ['GET', 'POST'])]
    public function modifyFavori($id, TranscriptionsRepository $transcriptionsRepository, Request $request, UsersRepository $usersRepository){
        if($this->getUser()) {
            $user = $usersRepository->findOneBy(['id' => $this->getUser()->getId()]);
            $transcription = $transcriptionsRepository->findOneBy(['id' => $id]);
            if ($user->getFavoris()->contains($transcription) == true) {
                $user->removeFavori($transcription);
                $usersRepository->add($user);
            } else {
                $user->addFavori($transcription);
                $usersRepository->add($user);
            }
            return $this->redirectToRoute('showTranscriptions', [
                'id' => $this->getUser()->getId()
            ]);
        }

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