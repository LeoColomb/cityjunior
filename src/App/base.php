<?php

namespace App;

use Notification\NotificationException;
use Data\MissionQuery;
use Data\UserQuery;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

function base()
{
    $log = new Logger('APP');
    $log->pushHandler(new StreamHandler(LOG_FILE, LOG_LEVEL));

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
                $log->debug('Mission already saved', [
                    'user' => $user->getName(),
                    'mission' => $mission->getID()
                ]);
                continue;
            }
            $log->debug('New mission', [
                'user' => $user->getName(),
                'mission' => $mission->getID()
            ]);
            $missionRawTrain = array_reverse(explode(' - ', $missionRaw["D\xC3\xA9part/Gare"]));
            $mission
                ->setType($missionRaw['Type'])
                ->setDate(\DateTime::createFromFormat('d/m/Y', $missionRaw['Date']))
                ->setName($missionRawTrain[0])
                ->setStart($missionRaw['Debut'])
                ->setArrival($missionRaw["Arriv\xC3\xA9e"] == "\xC2\xA0" ? null : $missionRaw["Arriv\xC3\xA9e"])
                ->setEnd($missionRaw['Fin'])
                ->setCode($missionRaw['Code'] == "\xC2\xA0" ? null : $missionRaw['Code'])
                ->setTrain(count($missionRawTrain) === 2 ? substr($missionRawTrain[1], -4) : null)
                ->setConfirmed(strpos($missionRaw['Confirmee'], ' non ') == false)
                ->setUserId($user->getId());

            if (!$mission->getConfirmed()) {
                $notifiers = ['App\\Notification\\MailNotification'];
                if ($user->getID() == 1) {
                    array_push($notifiers, 'App\\Notification\\SMSNotification');
                }
                foreach ($notifiers as $notifier) {
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
                    $log->debug('Notification sent', [
                        'user' => $user->getName(),
                        'mission' => $mission->getID(),
                        'notifier' => $notifier
                    ]);
                }
            } else {
                $log->debug('Mission already confirmed', [
                    'user' => $user->getName(),
                    'mission' => $mission->getID()
                ]);
            }

            $mission->save();
        }
    }
}
