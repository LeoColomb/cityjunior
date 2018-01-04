<?php

namespace App;

use Notification\NotificationException;
use Data\MissionQuery;
use Data\Mission;
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
            $isAstreinte = $missionRaw['Type'] === 'Astreinte';
            $missionRawTrain = array_reverse(explode($isAstreinte ? '-' : ' - ', $missionRaw["D\xC3\xA9part/Gare"], 2));
            $mission
                ->setType($missionRaw['Type'])
                ->setDate(\DateTime::createFromFormat('d/m/Y', $missionRaw['Date']))
                ->setName(Mission::ucname($missionRawTrain[0]))
                ->setStart($missionRaw['Debut'])
                ->setArrival($isAstreinte ? $missionRawTrain[1] : Mission::ucname($missionRaw["Arriv\xC3\xA9e"]))
                ->setEnd($missionRaw['Fin'])
                ->setCode($isAstreinte ? null : $missionRaw['Code'])
                ->setTrain($isAstreinte ? null : substr($missionRawTrain[1], -4))
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
                        $notif->attach($fetcher->attachment($mission->getId(), $isAstreinte));
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
