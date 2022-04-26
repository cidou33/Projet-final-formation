<?php

namespace App\Controller;

use App\Entity\Transcriptions;
use App\Form\TranscriptionsType;
use App\Repository\TrainingsRepository;
use App\Repository\TranscriptionsRepository;
use App\Repository\UsersRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class transcriptionsController extends AbstractController
{
    #[Route('/nouvelle-transcription', name: 'addTranscription', methods: ['GET', 'POST'])]
    public function addTranscription(TranscriptionsRepository $transcriptionsRepository,
                                     Request $request,
                                     SluggerInterface $slugger)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $user = $this->getUser();
        $connect = $this->getUser() == null;
        $transcription = new Transcriptions();
        $transcriptionForm = $this->createForm(TranscriptionsType::class, $transcription);
        $transcriptionForm->handleRequest($request);
        if($transcriptionForm->isSubmitted() && $transcriptionForm->isValid()) {
            $transcription->setActive(true);
            $transcription->setCreateDate(new \DateTime('now'));
            $pdf = $transcriptionForm->get('pdfFile')->getData();
            $mediaLink = $transcriptionForm->get('mediaLink')->getViewData();
            $newMediaLink = str_replace("https://www.youtube.com/watch?v=", "", $mediaLink);
            $transcription->setMediaLink($newMediaLink);
            $originalFilename = pathinfo($pdf->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . md5(uniqid()) . '.' . $pdf->guessExtension();
            $pdf->move($this->getParameter('uploadTranscriptions'), $newFilename);
            $transcription->setPdfFile($newFilename);
            $transcriptionsRepository->add($transcription);
            if ($this->getUser()) {
                return $this->redirectToRoute('showTranscriptions', [
                    'user' => $user,
                    'connect' => $connect,
                    'infos' => 'camesoul',
                    'favori' => $user->getFavoris()->contains($transcription) == true
                ]);
            }
            return $this->redirectToRoute('showTranscriptions', [
                'user' => $user,
                'infos' => 'camesoul',
                'connect' => $connect
            ]);
        }
        return $this->render('transcriptions/addTranscription.html.twig', [
            'transcriptionForm' => $transcriptionForm->createView(),
            'user' => $user,
            'connect' => $connect
        ]);
    }

    #[Route('les-transcriptions/{infos}', name: 'showTranscriptions', methods: ['GET', 'POST'])]
    public function showTranscriptions(Request $request ,
                                       PaginatorInterface $paginator ,
                                       TranscriptionsRepository $transcriptionsRepository,
                                       $infos)
    {
        $alphabet = range("a", "z");
        if(in_array($infos, $alphabet)) {
            $donnees = $transcriptionsRepository->findMySelectionByLetter($infos);
        }elseif ($infos == 'facile' or $infos == 'moyen' or $infos == 'difficile'){
            $donnees = $transcriptionsRepository->findMySelectionByDifficulty($infos);
        }
        else{
            $donnees= $transcriptionsRepository->findAll();
        }
        $transcriptions = $paginator->paginate(
            $donnees,
            $request->query->getInt('page', 1),
            20
        );
        $connect = $this->getUser() == null;
        $user = $this->getUser();
        if ($user != null) {
            $favoris = $user->getFavoris();
            return $this->render('transcriptions/transcriptions.html.twig', [
                'user' => $user,
                'connect' => $connect,
                'transcriptions' => $transcriptions,
                'favori' => $favoris,
                'alphabet' => $alphabet
            ]);
        }
        return $this->render('transcriptions/transcriptions.html.twig', [
            'user' => $user,
            'connect' => $connect,
            'transcriptions' => $transcriptions,
            'alphabet' => $alphabet
            ]);
    }

    #[Route('/recherche-chanson', name: 'bandNameSearch', methods: ['GET', 'POST'])]
    public function bandNameSearch(Request $request ,
                                   PaginatorInterface $paginator ,
                                   TranscriptionsRepository $transcriptionsRepository){
        $valueSearch = $request->request->get('bandNameSearch');
        $donnees = $transcriptionsRepository->bandNameSearch($valueSearch);
        $alphabet = range("a", "z");
        $connect = $this->getUser() == null;
        $user = $this->getUser();
        $transcriptions = $paginator->paginate(
            $donnees,
            $request->query->getInt('page', 1),
            20
        );
        if ($user != null) {
            $favoris = $user->getFavoris();
            return $this->render('transcriptions/transcriptions.html.twig', [
                'user' => $user,
                'connect' => $connect,
                'transcriptions' => $transcriptions,
                'alphabet' => $alphabet,
                'favori' => $favoris
            ]);
        }
        return $this->render('transcriptions/transcriptions.html.twig', [
            'user' => $user,
            'connect' => $connect,
            'alphabet' => $alphabet,
            'transcriptions' => $transcriptions
        ]);
    }

    #[Route('/modifier-transcription/{id}', name: 'updateTranscription', methods: ['GET', 'POST'])]
    public function updateTranscription(TranscriptionsRepository $transcriptionsRepository,
                                     Request $request,
                                     SluggerInterface $slugger,
                                     $id)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $user = $this->getUser();
        $connect = $this->getUser() == null;
        $transcription = $transcriptionsRepository->findOneBy(['id'=>$id]);
        $oldPdf = $transcription->getPdfFile();
        $transcriptionForm = $this->createForm(TranscriptionsType::class, $transcription);
        $transcriptionForm->handleRequest($request);
        if($transcriptionForm->isSubmitted() && $transcriptionForm->isValid()) {
            $transcription->setActive(true);
            $transcription->setCreateDate(new \DateTime('now'));
            $pdf = $transcriptionForm->get('pdfFile')->getData();
            $mediaLink = $transcriptionForm->get('mediaLink')->getViewData();
            $newMediaLink = str_replace("https://www.youtube.com/watch?v=", "", $mediaLink);
            $transcription->setMediaLink($newMediaLink);
            if($pdf == null){
                $pdf =  $oldPdf;
                $transcription->setPdfFile($pdf);
                $transcriptionsRepository->add($transcription);
                if ($this->getUser()) {
                    return $this->redirectToRoute('showTranscriptions', [
                        'user' => $user,
                        'connect' => $connect,
                        'infos' => 'camesoul',
                        'favori' => $user->getFavoris()->contains($transcription) == true
                    ]);
                }
                return $this->redirectToRoute('showTranscriptions', [
                    'user' => $user,
                    'infos' => 'camesoul',
                    'connect' => $connect
                ]);
            }
            $originalFilename = pathinfo($pdf->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . md5(uniqid()) . '.' . $pdf->guessExtension();
            $pdf->move($this->getParameter('uploadTranscriptions'), $newFilename);
            $transcription->setPdfFile($newFilename);
            $transcriptionsRepository->add($transcription);
            if ($this->getUser()) {
                return $this->redirectToRoute('showTranscriptions', [
                    'user' => $user,
                    'connect' => $connect,
                    'infos' => 'camesoul',
                    'favori' => $user->getFavoris()->contains($transcription) == true
                ]);
            }
            return $this->redirectToRoute('showTranscriptions', [
                'user' => $user,
                'infos' => 'camesoul',
                'connect' => $connect
            ]);
        }
        return $this->render('transcriptions/addTranscription.html.twig', [
            'transcriptionForm' => $transcriptionForm->createView(),
            'user' => $user,
            'connect' => $connect
        ]);
    }

    #[Route('/effacer-transcription/{id}', name: 'deleteTranscription', methods: ['GET', 'POST'])]
    public  function deleteTranscription(TranscriptionsRepository $transcriptionsRepository, $id){
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $transcription = $transcriptionsRepository->findOneBy(['id' => $id]);
        $transcriptionsRepository->remove($transcription);
        return $this->redirectToRoute('showTranscriptions', [

            'infos' => 'camesoul'
        ]);
    }
}