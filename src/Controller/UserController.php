<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends Controller
{
    /**
     * @Route("/connexion", name="security_login")
     */
    public function index(AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('user/security_login.html.twig', [
            'page_name' => 'Login',
            'last_username'=>$lastUsername,
            'error'=>$error
        ]);
    }

    /**
     * @Route("/add_user", name="add_user")
     */
    public function addUser(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $em){


        $user = new User();
        $formUser = $this->createForm(UserType::class);

        $formUser->handleRequest($request);

        if($formUser->isSubmitted() && $formUser->isValid()){
            $user = $formUser->getData();


            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $user->addRole('ROLE_USER');
            $em->persist($user);
            $em->flush();
            $this->addFlash('success','User successfully added');
            return $this->redirectToRoute('main');
        }

        return $this->render('user/security_registration.html.twig',[
            'page_name' => 'Registration',
            'formUser'=>$formUser->createView(),
            'date'=>date('Y')
        ]);
    }

    /**
     * @Route("/forgot_password", name="forgot_password")
     */
    public function forgotPassword(Request $request, \Swift_Mailer $mailer){

        $user = new User();
        $formUser = $this->createForm(UserType::class);
        $formUser->remove('name');
        $formUser->remove('password');
        $formUser->handleRequest($request);

        if($formUser->isSubmitted() && $formUser->isValid()){
            $user = $formUser->getData();

            $message = new \Swift_Message();
            $message->setSubject("Changement de mot de passe")
                ->setFrom("send@mail.org")
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView("mail/index.html.twig"
                        , ["email" => $user->getEmail()])
                    , "text/html");

            $mailer->send($message);

            $this->addFlash('success','Mail was send to ' . $user->getEmail());

            return $this->redirectToRoute('main');
        }

        return $this->render('user/security_forget_password.html.twig',[
            'page_name' => 'Forget Password',
            'formUser' => $formUser->createView()
        ]);
    }

    /**
     * @Route("/reset_password/{email}", name="reset_password")
     */
    public function resetPassword(Request $request){

        $user = new User();
        $formUser = $this->createForm(UserType::class);
        $formUser->remove('email');
        $formUser->remove('name');
        $formUser->add('password', PasswordType::class, [
            'label' => 'New password'
        ]);
        $formUser->add('passwordConfirm', PasswordType::class, [
            'label' => 'Confirm new password'
        ]);


        $formUser->handleRequest($request);

        if($formUser->isSubmitted() && $formUser->isValid()){
            $user = $formUser->getData();
            $passwordC = $formUser->get('passwordConfirm');
            dump($passwordC);


        }

        return $this->render('user/security_reset_password.html.twig',[
            'page_name' => 'Reset Password',
            'formUser' => $formUser->createView()
        ]);
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout(){

    }

}
