<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\RegistrationFormType;
use App\Repository\UsersRepository;
use App\Security\AppCustomAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, AppCustomAuthenticator $authenticator, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $user = new Users();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        $userActif = $this->getUser();
        $connect = $this->getUser() == null;
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setCreateDate(new \DateTime('now'));
            $img = $form->get('imgProfil')->getData();
            if($img != null) {
                $originalFilename = pathinfo($img->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . md5(uniqid()) . '.' . $img->guessExtension();
                $img->move($this->getParameter('uploadDirectory'), $newFilename);
                $user->setImgProfil($newFilename);
            }
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'user' => $userActif,
            'connect' => $connect
        ]);
    }

    #[Route('effacer-son-compte', name: 'deleteUser', methods: ['GET', 'POST'])]
    public function deleteUser(UsersRepository $usersRepository,){
        $userId = $this->getUser()->getId();
        $userDeleted = $usersRepository->findOneBy(['id'=> $userId]);
        $usersRepository->remove($userDeleted);
        return $this->redirectToRoute('homePage');
    }

    #[Route('/modifier-compte', name: 'updateUser', methods: ['GET', 'POST'])]
    public function updateUser(UsersRepository $usersRepository, Request $request, SluggerInterface $slugger)
    {
        $user = $this->getUser();
        $connect = $this->getUser() == null;
        $id = $this->getUser()->getId();
        $userUpdate = $usersRepository->findOneBy(['id' => $id]);
        $oldImg = $userUpdate->getImgProfil();
        $userForm = $this->createForm(RegistrationFormType::class, $userUpdate);
        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $img = $userForm->get('imgProfil')->getData();
            if ($img == null) {
                $img = $oldImg;
                $userUpdate->setImgProfil($img);
                $usersRepository->add($userUpdate);
                return $this->redirectToRoute('homePage', [
                    'user' => $user,
                    'connect' => $connect,
                ]);
            }
            $originalFilename = pathinfo($img->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . md5(uniqid()) . '.' . $img->guessExtension();
            $img->move($this->getParameter('uploadCategories'), $newFilename);
            $userUpdate->setImgProfil($newFilename);
            $usersRepository->add($userUpdate);
            return $this->redirectToRoute('homePage', [
                'user' => $user,
                'connect' => $connect,


            ]);
        }
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $userForm->createView(),
            'user' => $user,
            'connect' => $connect
        ]);
    }
}
