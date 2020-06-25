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

    public function signup($params)
    {
        $this->name = $params['name'];
        $this->surname = $params['surname'];
        $this->phone = str_replace(['+', '-', '(', ')', ' '], '', $params['phone']);
        $this->password = $params['password'];
        $this->created_at = time();

        if (!$this->validate() || !isset($params['do_signup'])) {
            return false;
        }

        if ($this->password != $params['password_2']) {
            $this->addError('Повторный пароль введен не верно');
            return false;
        }

        $this->password = password_hash($this->password, PASSWORD_DEFAULT);

        return $this->save();

    }

}