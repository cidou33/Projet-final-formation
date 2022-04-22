<?php

namespace App\Controller;

use App\Entity\Trainings;
use App\Form\TrainingsType;
use App\Repository\TrainingsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class TrainingController extends AbstractController
{
    #[Route('/nouveau-cours', name: 'addTraining', methods: ['GET', 'POST'])]
    public function addTraining(TrainingsRepository $trainingsRepository, Request $request, SluggerInterface $slugger){
        $user = $this->getUser();
        if($user == null){
            $connect = 0;
        }else{
            $connect = 1;
        };
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
        if($user == null){
            $connect = 0;
        }else{
            $connect = 1;
        };
        return $this->render('trainings/trainings.html.twig', [
            'trainings' => $trainings,
            'connect' => $connect,
            'user' =>$user
        ]);
    }
}