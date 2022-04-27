<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class mentionController extends  AbstractController
{

    #[Route('/mentions-legales', name: 'showMentions', methods: ['GET', 'POST'])]
    public function showMentions(){
        $user = $this->getUser();
        $connect = $user == null;
        return $this->render('cgu.html.twig', [
            'user' => $user,
            'connect' => $connect
        ]);
    }


}