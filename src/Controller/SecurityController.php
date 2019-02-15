<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\Users;
use App\Repository\UsersRepository;
use App\Security\LoginAuthenticator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login", methods={"GET", "POST"})
     */

    public function login ( AuthenticationUtils $authenticationUtils ) : Response
    {
        $user = new Users();
        // get the login error if there is one
        $error = $authenticationUtils -> getLastAuthenticationError ();
        // last username entered by the user
        $lastUsername = $authenticationUtils -> getLastUsername ();

        return $this -> render ( 'security/login.html.twig' , [
            'last_username' => $lastUsername ,
            'error' => $error
        ]);
        $hasAccess = $this -> isGranted ( 'ROLE_ADMIN' );
        $this -> denyAccessUnlessGranted ( 'ROLE_ADMIN' );
        if ($form->isSubmitted() && $form->isValid())
            {
                return $this->redirectToRoute('books_index');
// var_dump($form);

            }
    }

}
