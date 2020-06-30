<?php

namespace models;

use components\base\Model;
use components\base\View;
use components\base\Db;
use components\Session;
use components\SMS;

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
    
    /**
     * Правила валидации
     */
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

    /**
     * Возвращает имя таблицы в базе данных
     */
    public function getTableName()
    {
        return 'users';
    }

    /**
     * Регистрация пользователя
     * @param array $data Данные пользователя
     * @return boolean
     */
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

        if ($this->sendConfirmationCode()) {
            $session = new Session();
            $session->set('phone', $this->phone);
            return $this->save();
        }

        return false;

    }


    /**
     * Отправляет СМС с кодом подтверждения 
     */
    public function sendConfirmationCode()
    {

        $code = rand(10000,99999);
        
        $R = Db::getConnection();
        while($R::findOne('confirmation_codes', 'code = :code', [':code' => $code])) {
            $code = rand(10000,99999);
        }

        $sms = new SMS();

        $sms->to = $this->phone;
        $sms->text = 'Код для подтверждения: '.$code;
        $sms->send();

        if ($sms->getStatus() == 'OK') {
            $record = $R::xdispense('confirmation_codes');
            $record->phone = $this->phone;
            $record->code = $code;
            $R::store($record);
        } else {
            $this->addError("Не удалось отправить SMS код на номер {$this->phone}");
            return false;
        }

        return true;
        
    }

    /**
     * Подтверждение СМС кода
     * @param integer $code СМС код
     */
    public function verifyCode($code)
    {

        $session = new Session();

        $phone = $session->get('phone');

        $R = Db::getConnection();

        $record = $R::findOne('confirmation_codes', 'phone = ?', [$phone]);

        if ($record->code == $code) {

            $R::trash($record);

            $user = $this::findOne('phone = :phone', [':phone' => $phone]);
            $user->status = 1;
            $user->save();

            $session->set('user', [
                'id' => $user->id,
                'name' => $user->name,
                'surname' => $user->surname,
                'phone' => $user->phone,
                'password' => $user->password
            ]);           

            $session->unset('phone');

            return true;

        }

        $this->addError('Код введен неверно');

        return false;

    }

    /**
     * Авторизация пользователя
     * @return boolean
     */
    public function login() 
    {

        $sessions = new Session();

        $session = $sessions->get('user');
        if (!empty($session)) {

            $user = $this->findOne('id = :id', [':id' => $session['id']]);
            
            if ($user->password == $session['password']) {
                return true;
            }

        }

        $this->phone = str_replace(['-','+','(',')',' '], '', $this->phone);

        $user = $this->findOne('phone = :phone', [':phone' => $this->phone]);
        
        if ($user && password_verify($this->password, $user->password)) {
            $userData = [
                'id' => $user->id,
                'name' => $user->name,
                'surname' => $user->surname,
                'phone' => $user->phone,
                'password' => $user->password
            ];
            $sessions->set('user', $userData);
            return true;
        }

        $this->addError('Не правельный номер или пароль');

        return false;
    }

    public function signOut()
    {
        $sessions = new Session();
        if (!empty($sessions->get('user'))) {
            $sessions->unset('user');
        }
    }

    /**
     * Возвращает true если пользователь не авторизован. Иначе false
     * @return boolean
     */
    public function isGuest()
    {
        $session = new Session();
        return !empty($session->get('user'));
    }

}