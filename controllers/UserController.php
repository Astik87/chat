<?php

namespace controllers;

use components\base\Controller;
use components\Session;
use models\User;

class UserController extends Controller
{
    /**
     * Экшен авторизации
     */
    public function actionSignin() 
    {

        $sessions = new Session();
        if (!empty($_POST) or !empty($sessions->get('user'))) {
            $user = new User();
            $user->phone = $_POST['phone'];
            $user->password = $_POST['password'];
            if ($user->login()) {
                $this->redirect('user/signup');
            }
        }

        $this->render('signin', ['message' => 'Hello']);
    }

    /**
     * Экшен регистрации
     */
    public function actionSignup() 
    {
        
        if (!empty($_POST) && isset($_POST['do_signup'])) {

            $user = new User();

            if ($user->signup($_POST)) {
                $this->redirect('user/code');
            } else {
                $user->addError('Ошибка регистрации');
            }
        }

        $this->render('signup');
    }

    public function actionSignout()
    {
        $user = new User();
        $user->signOut();
        $this->redirect('user/signin');
    }

    /**
     * Экшен подтверждения СМС кода
     */
    public function actionCode()
    {

        if (isset($_POST['verify']) && isset($_POST['code'])) {
            $user = new User();
            if($user->verifyCode($_POST['code'])) $this->redirect('user/signin');
        }

        $this->render('code');

    }
}