<?php namespace Impl\Service\Notification;

use Services_Twilio;

class SmsNotifier implements NotifierInterface {

    /**
     * Bilgilendirmenin alıcısı
     * @var string
     */
    protected $to;

    /**
     * Bilgilendirmenin göndericisi
     * @var string
     */
    protected $from;

    /**
     * Twilio SMS SDK
     * @var \Services_Twilio
     */
    protected $twilio;

    public function __construct(Services_Twilio $twilio)
    {
        $this->twilio = $twilio;
    }

    /**
     * Bilgilendirmenin alıcıları
     * @param  string $to Alıcı
     * @return Impl\Service\Notification\SmsNotifier  $this  Zincirlenebilirlik için dönder
     */
    public function to($to)
    {
        $this->to = $to;

        return $this;
    }

    /**
     * Bilgilendirmenin göndericisi
     * @param  string $from Gönderici
     * @return Impl\Service\Notification\NotifierInterface  $this  Zincirlenebilirlik için dönder
     */
    public function from($from)
    {
        $this->from = $from;

        return $this;
    }

    public function notify($subject, $message)
    {
        $sms = $this->twilio
            ->account
            ->sms_messages
            ->create(
                $this->from,
                $this->to,
                $subject."\n".$message
            );
    }

}