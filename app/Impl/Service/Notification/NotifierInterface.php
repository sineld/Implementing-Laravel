<?php namespace Impl\Service\Notification;

interface NotifierInterface {

    /**
    * Bilgilendirme alıcıları
    * @param  string $to Alıcı
    * @return Impl\Service\Notification\NotifierInterface
    */
    public function to($to);

    /**
    * Bilgilendirmenin göndericisi
    * @param  string $from Gönderici
    * @return Impl\Service\Notification\NotifierInterface
    */
    public function from($from);

    /**
    * Bilgilendirmeyi Gönder
    * @param  string $subject Bilgilendirme Konusu
    * @param  string $message Bilgilendirme İçeriği
    * @return void
    */
    public function notify($subject, $message);

}