<?php

namespace App\Controller;

use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class adminController extends AbstractController
{

    #[Route('/voir-utilisateurs', name: 'showUsers', methods: ['GET', 'POST'])]
    public function showUsers(UsersRepository $usersRepository){
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $user = $this->getUser();
        $connect = $this->getUser() == null;
            $users = $usersRepository->findBy([], array('email' => 'ASC'));
            return $this->render('admin/modifyRoles.html.twig', [
                'users' => $users,
                'user' => $user,
                'connect' => $connect
            ]);
    }

    #[Route('/admin-effacer-utilisateur', name: 'adminDeleteUser', methods: ['GET', 'POST'])]
    public function adminDeleteUser(UsersRepository $usersRepository, Request $request){
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if($this->isGranted('ROLE_ADMIN')) {
            $id = $request->request->get('userId');
            $userDeleted = $usersRepository->findOneBy(['id' => $id]);
            $usersRepository->remove($userDeleted);
            return $this->redirectToRoute('showUsers');
        }else{
            return $this->redirectToRoute('homePage');
        }
    }

    #[Route('/admin-modifier-role-user', name: 'adminModifyRoleUser', methods: ['GET', 'POST'])]
    public function adminModifyRoleUser(UsersRepository $usersRepository, Request $request ){
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if($this->isGranted('ROLE_ADMIN')) {
            $id = $request->request->get('userId');
            $userModify = $usersRepository->findOneBy(['id' => $id]);
            if($userModify->getRoles() == ['ROLE_ADMIN']) {
                $userModify->setRoles(['ROLE_USER']);
                $usersRepository->add($userModify);
                return $this->redirectToRoute('showUsers');
            }else{
                $userModify->setRoles(['ROLE_ADMIN']);
                $usersRepository->add($userModify);
                return $this->redirectToRoute('showUsers');
            }
        }else{
            return $this->redirectToRoute('homePage');
        }
    }
}