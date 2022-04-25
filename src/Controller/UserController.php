<?php

namespace App\Controller;

use App\Repository\TranscriptionsRepository;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

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

    #[Route('/mon-profil', name: 'updateUser', methods: ['GET', 'POST'])]
    public function modifyUser(UsersRepository $usersRepository, Request $request, SluggerInterface $slugger){
        $user = $this->getUser();
        if($user != null) {
            $userId = $user->getId();
            $connect = $this->getUser() == null;
            $userUpdate = $usersRepository->findOneBy(['id' => $userId]);
            $oldImg = $userUpdate->getImgProfil();
            if (!empty($request->request->all())) {
                $username = $request->request->get('username');
                $userUpdate->setUsername($username);
                $img = $request->files->get('avatar');
                if ($img == null) {
                    $img = $oldImg;
                    $userUpdate->setImgProfil($img);
                    $usersRepository->add($userUpdate);
                    return $this->redirectToRoute('homePage', [
                        'user' => $user,
                        'connect' => $connect,
                    ]);
                }
                $originalFilename = pathinfo($img[0]->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . md5(uniqid()) . '.' . $img[0]->guessExtension();
                $img[0]->move($this->getParameter('uploadDirectory'), $newFilename);
                $userUpdate->setImgProfil($newFilename);
                $usersRepository->add($userUpdate);
                return $this->redirectToRoute('homePage', [
                    'user' => $user,
                    'connect' => $connect,


                ]);
            }
            return $this->render('user/profil.html.twig', [
                'user' => $user,
                'connect' => $connect
            ]);
        }
        return $this->redirectToRoute('homePage');
    }

    #[Route('effacer-son-compte', name: 'deleteUser', methods: ['GET', 'POST'])]
    public function deleteUser(UsersRepository $usersRepository,){
        $userId = $this->getUser()->getId();
        $userDeleted = $usersRepository->findOneBy(['id'=> $userId]);
        $usersRepository->remove($userDeleted);
        return $this->redirectToRoute('homePage');
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