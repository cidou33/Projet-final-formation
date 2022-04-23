<?php

namespace App\Controller;

use App\Entity\Transcriptions;
use App\Form\TranscriptionsType;
use App\Repository\TrainingsRepository;
use App\Repository\TranscriptionsRepository;
use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class transcriptionsController extends AbstractController
{
    #[Route('/nouvelle-transcription', name: 'addTranscription', methods: ['GET', 'POST'])]
    public function addTranscription(TranscriptionsRepository $transcriptionsRepository, Request $request, SluggerInterface $slugger){
        $user = $this->getUser();
        $connect = $this->getUser() == null;
        $transcription = new Transcriptions();
        $transcriptionForm = $this->createForm(TranscriptionsType::class, $transcription);
        $transcriptionForm->handleRequest($request);
        if($transcriptionForm->isSubmitted() && $transcriptionForm->isValid()){
            $transcription->setActive(true);
            $transcription->setCreateDate(new \DateTime('now'));
            $pdf = $transcriptionForm->get('pdfFile')->getData();
            $mediaLink = $transcriptionForm->get('mediaLink')->getViewData();
            $newMediaLink = str_replace("https://www.youtube.com/watch?v=", "", $mediaLink);
            $transcription->setMediaLink($newMediaLink);
            $originalFilename =pathinfo($pdf->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.md5(uniqid()).'.'.$pdf->guessExtension();
            $pdf->move($this->getParameter('uploadTranscriptions'), $newFilename);
            $transcription->setPdfFile($newFilename);
            $transcriptionsRepository->add($transcription);
            return $this->redirectToRoute('showTranscriptions', [
                'user' => $user,
                'connect' => $connect,
                'favori' => $user->getFavoris()->contains($transcription) == true
            ]);
        }
        return $this->render('transcriptions/addTranscription.html.twig', [
            'transcriptionForm' => $transcriptionForm->createView(),
            'user' => $user,
            'connect' => $connect
        ]);
    }

    #[Route('les-transcriptions/{id}', name: 'showTranscriptions', methods: ['GET', 'POST'])]
    public function showTranscriptions(TranscriptionsRepository $transcriptionsRepository, UsersRepository $usersRepository, $id){
        $transcriptions = $transcriptionsRepository->findAll();
        $connect = $this->getUser() == null;
        $user = $this->getUser();
        if ($user != null) {
            $favoris = $user->getFavoris();
            return $this->render('transcriptions/transcriptions.html.twig', [
                'user' => $user,
                'connect' => $connect,
                'transcriptions' => $transcriptions,
                'favori' => $favoris
            ]);
        }
        return $this->render('transcriptions/transcriptions.html.twig', [
            'user' => $user,
            'connect' => $connect,
            'transcriptions' => $transcriptions]);
    }
}