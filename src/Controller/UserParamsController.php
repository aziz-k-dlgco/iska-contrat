<?php

namespace App\Controller;

use App\Entity\Abstracts\Document;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserParamsController extends AbstractController
{
    /**
     * @Route("/apps/params", name="params_home", methods={"GET", "POST"})
     */
    public function home(Request $request, UserPasswordHasherInterface $passwordHasher){

        $newPass = $request->get('new_password') ?? null;
        $newPassConfirm = $request->get('new_password_confirm') ?? null;
        if($request->isMethod('POST')){
            if(!($newPass && $newPassConfirm)){
                $this->addFlash('error', 'Veuillez remplir tous les champs');
                return $this->render('apps/parameters/index.html.twig');
            }
            if($newPass != $newPassConfirm){
                $this->addFlash('error', 'Les mots de passe ne correspondent pas');
                return $this->redirectToRoute('params_home');
            }
            //Check if password is 8 characters long minimum
            if(strlen($newPassConfirm) < 8){
                $this->addFlash('error', 'Le mot de passe doit contenir au moins 8 caractères');
                return $this->redirectToRoute('params_home');
            }
            //Check if password contains at least one number and one capital letter
            if(!preg_match('/[A-Z]/', $newPassConfirm) || !preg_match('/[0-9]/', $newPassConfirm)){
                $this->addFlash('error', 'Le mot de passe doit contenir au moins une lettre majuscule et un chiffre');
                return $this->redirectToRoute('params_home');
            }

            /* @var User $user */
            $user = $this->getUser();
            $password= $passwordHasher->hashPassword($user, $newPassConfirm);
            $user->setPassword($password);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Votre mot de passe a bien été modifié');
            return $this->redirectToRoute('params_home');
        }
        return $this->render('apps/parameters/index.html.twig');
    }


}