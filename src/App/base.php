<?php

namespace App;

use App\Notification\MailNotification;
use App\Notification\SMSNotification;
use App\Notification\NotificationException;
use Data\MissionQuery;
use Data\UserQuery;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

function base()
{
    $log = new Logger('App');
    $log->pushHandler(new StreamHandler(LOG_FILE, Logger::DEBUG));

    $users = UserQuery::create()->find();
    foreach ($users as $user) {
        $log->debug('Starting analysis for the next user', ['user' => $user->getName()]);
        $fetcher = new Fetch($user);
        $missions = $fetcher->fetch();

        foreach ($missions as $missionRaw) {
            $mission = MissionQuery::create()
                ->filterByPrimaryKey($missionRaw['ID'])
                ->findOneOrCreate();
            if ($mission->getType()) {
            	$log->debug('Mission already saved. ID: '.$mission->getID());
                continue;
            }
            $log->debug('New mission', [
                'user' => $user->getName(),
                'mission' => $mission->getID()
            ]);
            $mission
                ->setType($missionRaw['Type'])
                ->setDate(\DateTime::createFromFormat('d/m/Y', $missionRaw['Date']))
                ->setName($missionRaw["D\xC3\xA9part/Gare"])
                ->setStart($missionRaw['Debut'])
                ->setArrival($missionRaw["Arriv\xC3\xA9e"] == "\xC2\xA0" ? null : $missionRaw["Arriv\xC3\xA9e"])
                ->setEnd($missionRaw['Fin'])
                ->setCode($missionRaw['Code'] == "\xC2\xA0" ? null : $missionRaw['Code'])
                ->setConfirmed(strpos($missionRaw['Confirmee'], ' non ') == false)
                ->setUserId($user->getId())
                ->save();

            if (!$mission->getConfirmed()) {
                foreach (['MailNotification', 'SMSNotification'] as $notifier) {
                    $log->info('Preparing a new notification', [
                        'user' => $user->getName(),
                        'mission' => $mission->getID(),
                        'notifier' => $notifier
                    ]);
                    $notif = new $notifier($mission, $user);
                    if ($notif::ATTACHEMENT_ABILITY) {
                        $notif->attach($fetcher->attachment($mission->getId(), $mission->getType()));
                    }

                    $notif->send();
                }
            } else {
                $log->debug('Mission already confirmed', [
                    'user' => $user->getName(),
                    'mission' => $mission->getID()
                ]);
            }

        }
    }
}
