<?php

namespace App\Notification;

use Data\Mission;
use Data\User;

use App\Calendar;

class MailNotification implements NotificationInterface
{
    const ATTACHEMENT_ABILITY = true;

    protected $subject = '[City Junior] Mission ';

    protected $body = "\r\nThis is a MIME encoded message.\r\n";

    protected $user;

    protected $hash;

    public function __construct(Mission $mission, User $user)
    {
        $this->user = $user;

        $this->hash = md5(date('r', time()));

        $this->subject .= $mission->getName();
        $this->subject .= ' le '.$mission->getDateFormatted();

        $this->add('mixed', [
            'Type' => 'multipart/alternative; boundary="PHP-alt-'.$this->hash.'"',
        ]);

        ob_start();
        include __DIR__.'/files/mail_plain.phtml';
        $this->add('alt', [
            'Type' => 'text/plain; charset="UTF-8"',
            'Transfer-Encoding' => '7bit',
        ], ob_get_clean());

        ob_start();
        include __DIR__.'/files/mail_html.phtml';
        $this->add('alt', [
            'Type' => 'text/html; charset="UTF-8"',
            'Transfer-Encoding' => '7bit',
        ], ob_get_clean());
    }

    public function send()
    {
        mail(
            $this->user->getMail(),
            '=?UTF-8?B?'.base64_encode($this->subject).'?=',
            $this->body,
            'MIME-Version: 1.0'."\r\n".
            'From: City Junior App <noreply@cityjunior.clmb.fr>'."\r\n".
            'X-Mailer: CityJuniorApp/1.0 ('.phpversion().')'."\r\n".
            'Content-Type: multipart/mixed; boundary="PHP-mixed-'.$this->hash.'"'."\r\n".
            ''//'Content-Class: urn:content-classes:calendarmessage' . "\r\n"
        );
    }

    public function attach(string $raw)
    {
        $this->body .= '--PHP-alt-'.$this->hash.'--'."\r\n";
        $this->add('mixed', [
            'Type' => 'application/pdf; name="OrdreMission.pdf"',
            'Transfer-Encoding' => 'base64',
            'Disposition' => 'attachment; filename="OrdreMission.pdf"',
        ], chunk_split(base64_encode($raw)));
        $this->body .= '--PHP-mixed-'.$this->hash.'--'."\r\n";
    }

    private function add($name, $headers, $body = null)
    {
        $this->body .= "\r\n";
        $this->body .= '--PHP-'.$name.'-'.$this->hash."\r\n";
        foreach ($headers as $name => $value) {
            $this->body .= 'Content-'.$name.': '.$value."\r\n";
        }
        $this->body .= "\r\n";
        $this->body .= $body;
        $this->body .= "\r\n";
    }

    private function event(Mission $mission)
    {
        $this->add('mixed', [
            'Type' => 'text/calendar; name="Mission.ics"; method=REQUEST; charset="UTF-8"',
            'Transfer-Encoding' => '8bit',
        ], chunk_split(base64_encode((string) new Calendar($mission, $this->user))));
    }
}
