<?php

namespace components;

use components\SMSRU;


/**
 * Класс для отправки СМС сообщений
 * @property int $to Номер получателя
 * @property string $text Текст сообщеия
 * @property string $from Отправитель
 * @property string $sms Объект класса components\SMSRU
 * @property string $errorText Текст ошибки
 */
class SMS
{
    
    public $to;
    public $text;
    public $from;
    public $sms;
    public $errorText;


    /**
     * Отравляет СМС сообщение
     */
    public function send()
    {

        $smsru = new SMSRU(SMSRU_API_KEY);

        $data = new \stdClass();
        $data->to = $this->to;
        $data->text = $this->text;

        $this->sms = $smsru->send_one($data);

        return $this->sms;
    }

    public function getStatus()
    {
        return $this->sms->status;
    }

    public function getStatusCode()
    {
        return $this->sms->status_code;
    }

    public function getStatusText(Type $var = null)
    {
        return $this->sms->status_text;
    }

}