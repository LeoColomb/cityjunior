<?php

namespace App\Notification;

use Data\Mission;
use Data\User;

class MailNotification extends \PHPMailer implements NotificationInterface
{
    const ATTACHEMENT_ABILITY = true;

    public function __construct(Mission $mission, User $user)
    {
        parent::__construct();

        $this->CharSet = 'utf-8';
        
        $this->setLanguage('fr');
        $this->isHTML(true);
        $this->setFrom('noreply@cityjunior.clmb.fr', 'City Junior App');
        $this->addAddress($user->getMail());

        $this->Subject = '[City Junior] Mission '.$mission->getName();
        $this->Subject .= ' le '.$mission->getDateFormatted();

        ob_start();
        include __DIR__.'/files/mail_html.phtml';
        $this->Body = ob_get_clean();

        ob_start();
        include __DIR__.'/files/mail_plain.phtml';
        $this->AltBody = ob_get_clean();
    }

    public function attach($raw)
    {
        $this->addStringAttachment($raw, 'OrdreMission.pdf');
    }
}
