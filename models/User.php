<?php

namespace models;

use components\base\Model;
use components\base\View;

class User extends Model 
{

    public $id;
    public $name;
    public $surname;
    public $phone;
    public $password;
    public $created_at;
    public $updated_at;
    public $status;

    protected static $isGuest;
 
    public function rules()
    {
        return [
            'name' => ['min' => 1, 'max' => 30, 'message' => 'Имя не должно превышать 30 символов, и не может быть меньше 1 символа'],
            'surname' => ['max' => 30, 'message' => 'Фамилия не должно превышать 30 символов, и не может быть меньше 1 символа'],
            'password' => ['min' => 8, 'max' => 50, 'message' => 'Пароль должен состоять минимум из 8 символов, и максимум из 50'],
            'phone' => ['pattern' => '7[0-9]{10}', 'message' => 'Не корректный номер телефона'],
            'phone' => ['unique' => true, 'message' => 'Этот номер уже занят'],
        ];
    }

    public function getTableName()
    {
        return 'users';
    }

    public function signup($data)
    {
        $this->name = $data['name'];
        $this->surname = $data['surname'];
        $this->phone = str_replace(['+', '-', '(', ')', ' '], '', $data['phone']);
        $this->password = $data['password'];
        $this->created_at = time();

        if (!$this->validate() || !isset($data['do_signup'])) {
            return false;
        }

        if ($this->password != $data['password_2']) {
            $this->addError('Повторный пароль введен не верно');
            return false;
        }

        $this->password = password_hash($this->password, PASSWORD_DEFAULT);

        return $this->save();

    }

    public function login($data = null) 
    {

        $session = $_SESSION['user'];
        if (!empty($session)) {
            $user = $this->findOne('id = :id', [':id' => $session['id']]);
            if ($user->password == $session['password']) {
                return true;
            }
        }

        if ($data !== null) {

            $this->phone = str_replace(['-','+','(',')',' '], '', $data['phone']);

            $user = $this->findOne('phone = :phone', [':phone' => $this->phone]);
            if ($user && password_verify($data['password'], $user->password)) {
                $_SESSION['user'] = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'surname' => $user->surname,
                    'password' => $user->password
                ];
                return true;
            }
            
        }

        $this->addError('Не правельный номер или пароль');

        return false;
    }

    public function signOut()
    {

        session_start();

        if (!empty($_SESSION['user'])) {
            unset($_SESSION['user']);
        }
    }

    public function isGuest()
    {
        return $this->login();
    }

}