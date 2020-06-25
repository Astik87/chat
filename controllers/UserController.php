<?php

namespace controllers;

use components\base\Controller;
use models\User;

class UserController extends Controller
{

    public function actionSignin() {
        $this->render('signin', ['message' => 'Hello']);
    }

    public function actionSignup() {

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
}