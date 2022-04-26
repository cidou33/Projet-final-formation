<?php

namespace App\Controller;

use App\Entity\Trainings;
use App\Form\TrainingsType;
use App\Repository\TrainingsRepository;
use App\Repository\TranscriptionsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class TrainingController extends AbstractController
{
    #[Route('/nouveau-cours', name: 'addTraining', methods: ['GET', 'POST'])]
    public function addTraining(TrainingsRepository $trainingsRepository, Request $request, SluggerInterface $slugger){
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $user = $this->getUser();
        $connect = $this->getUser() == null;
        $training =new Trainings();
        $trainingForm = $this->createForm(TrainingsType::class, $training);
        $trainingForm->handleRequest($request);
        if($trainingForm->isSubmitted() && $trainingForm->isValid()){
            $training->setActive(true);
            $training->setCreateDate(new \DateTime('now'));
            $pdf = $trainingForm->get('pdfFile')->getData();
            $originalFilename = pathinfo($pdf->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename =$slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.md5(uniqid()).'.'.$pdf->guessExtension();
            $pdf->move($this->getParameter('uploadTrainings'), $newFilename);
            $training->setPdfFile($newFilename);
            $trainingsRepository->add($training);
            return $this->redirectToRoute('showTrainings', [
                'user' => $user,
                'connect' => $connect
            ]);
        }
        return $this->render('trainings/addTraining.html.twig', [
            'trainingForm' => $trainingForm->createView(),
            'user' => $user,
            'connect' => $connect
        ]);
    }

    #[Route('/les-cours', name: 'showTrainings', methods: ['GET', 'POST'])]
    public function showTrainings(TrainingsRepository $trainingsRepository){
        $trainings = $trainingsRepository->findMyTrainings();
        $user = $this->getUser();
        $connect = $this->getUser() == null;
        return $this->render('trainings/trainings.html.twig', [
            'trainings' => $trainings,
            'connect' => $connect,
            'user' =>$user
        ]);
    }

    #[Route('supprimer-cours/{id}', name: 'deleteTraining', methods: ['GET', 'POST'])]
    public function deleteTraining(TrainingsRepository $trainingsRepository, Request $request, $id){
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $training = $trainingsRepository->findOneBy(['id'=>$id]);
        $trainingsRepository->remove($training);
        return $this->redirectToRoute('showTrainings');
    }

    #[Route('activer-desactiver-cours/{id}', name: 'modifyStatusTraining', methods: ['GET', 'POST'])]
    public function modifyStatusTraining(TrainingsRepository $trainingsRepository, $id){
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $training = $trainingsRepository->findOneBy(['id'=>$id]);
        if($training->getActive()==true) {
            $training->setActive(false);
        }else{
            $training->setActive(true);
        }
        $trainingsRepository->add($training);
        return $this->redirectToRoute('showTrainings');
    }

    #[Route('modifier-cours/{id}', name: 'updateTraining', methods: ['GET', 'POST'])]
    public function updateTraining(TrainingsRepository $trainingsRepository,
                                   Request $request,
                                   SluggerInterface $slugger,
                                   $id)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $user = $this->getUser();
        $connect = $this->getUser() == null;
        $training = $trainingsRepository->findOneBy(['id' => $id]);
        $oldPdf = $training->getPdfFile();
        $trainingForm = $this->createForm(TrainingsType::class, $training);
        $trainingForm->handleRequest($request);
        if($trainingForm->isSubmitted() && $trainingForm->isValid()){
            $training->setActive(true);
            $training->setUpdateDate(new \DateTime('now'));
            $pdf = $trainingForm->get('pdfFile')->getData();
            if($pdf==null) {
                $pdf = $oldPdf;
                $training->setPdfFile($pdf);
                $trainingsRepository->add($training);
                return $this->redirectToRoute('showTrainings', [
                    'user' => $user,
                    'connect' => $connect
                ]);
            }
            $originalFilename = pathinfo($pdf->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename =$slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.md5(uniqid()).'.'.$pdf->guessExtension();
            $pdf->move($this->getParameter('uploadTrainings'), $newFilename);
            $training->setPdfFile($newFilename);
            $trainingsRepository->add($training);
            return $this->redirectToRoute('showTrainings', [
                'user' => $user,
                'connect' => $connect
            ]);
        }
        return $this->render('trainings/addTraining.html.twig', [
            'trainingForm' => $trainingForm->createView(),
            'user' => $user,
            'connect' => $connect
        ]);
    }
}