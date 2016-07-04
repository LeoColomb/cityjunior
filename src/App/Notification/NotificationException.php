<?php

namespace App\Notification;

class NotificationException extends \Exception
{
    public function __construct($message = '', $code = 0)
    {
        parent::__construct($message, $code);
    }
}
