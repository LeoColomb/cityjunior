<?php

namespace App\Notification;

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Client;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Data\Mission;
use Data\User;

class SMSNotification implements NotificationInterface
{
    const ATTACHEMENT_ABILITY = false;

    protected $body = '';

    protected $user;

    public function __construct(Mission $mission, User $user)
    {
        $this->user = $user;

        $this->body .= 'Nouvelle mission City Junior : ';
        $this->body .= $mission->getName();
        if (!$mission->isAstreinte()) {
            $this->body .= ' → ';
            $this->body .= $mission->getArrival();
        }
        $this->body .= ' (';
        $this->body .= $mission->getType();
        $this->body .= ') le ';
        $this->body .= $mission->getDateFormatted();
        $this->body .= ' de ';
        $this->body .= $mission->getStartFormatted();
        $this->body .= ' à ';
        $this->body .= $mission->getEndFormatted();
        $this->body .= '.';
    }

    public function send()
    {
        $stack = HandlerStack::create();
        $stack->push(
            Middleware::log(
                new Logger('SMS', [new StreamHandler(LOG_FILE, LOG_LEVEL)]),
                new MessageFormatter(MessageFormatter::SHORT)
            )
        );
        $client = new Client([
            'handler' => $stack,
        ]);
        $response = $client->get('https://smsapi.free-mobile.fr/sendmsg', [
            'verify' => __DIR__.'/files/ca-bundle.crt',
            'query' => [
                'user' => $this->user->getSMSUser(),
                'pass' => $this->user->getSMSPassword(),
                'msg' => $this->body,
            ],
        ]);
        if ($response->getStatusCode() !== 200) {
            throw new NotificationException('Unable to login with anwser: '.$response->getReasonPhrase());
        }
    }

    public function attach($raw)
    {
        return false;
    }
}
