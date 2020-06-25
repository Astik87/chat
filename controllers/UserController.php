<?php

namespace controllers;

use components\base\Controller;
use models\User;

class UserController extends Controller
{

    public function actionSignin() {
        session_start();
        if (!empty($_POST) or !empty($_SESSION['user'])) {
            $user = new User();
            if ($user->login($_POST)) {
                $this->redirect('user/signup');
            }
        }
        $this->render('signin', ['message' => 'Hello']);
    }

    public function actionSignup() {
        session_start();
        if (!empty($_POST) && isset($_POST['do_signup'])) {
            $user = new User();
            if ($user->signup($_POST)) {
                $this->redirect('user/signin');
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
}