<?php

namespace App;

use Data\Mission;
use App\Notification\NotificationException;
use Propel\Runtime\Exception\PropelException;

function base()
{
    $users = \Data\UserQuery::create()->find();
    foreach ($users as $user) {
        echo $user->getName();
        $fetcher = new Fetch($user);
        $missions = $fetcher->fetch();

        foreach ($missions as $missionRaw) {
            $mission = new Mission();
            $mission->setId($missionRaw['ID'])
                ->setType($missionRaw['Type'])
                ->setDate(\DateTime::createFromFormat('d/m/Y', $missionRaw['Date']))
                ->setName($missionRaw["D\xC3\xA9part/Gare"])
                ->setStart($missionRaw['Debut'])
                ->setArrival($missionRaw["Arriv\xC3\xA9e"] == "\xC2\xA0" ? null : $missionRaw["Arriv\xC3\xA9e"])
                ->setEnd($missionRaw['Fin'])
                ->setCode($missionRaw['Code'] == "\xC2\xA0" ? null : $missionRaw['Code'])
                ->setConfirmed(strpos($missionRaw['Confirmee'], ' non ') == false)
                ->setUserId($user->getId());
            try {
                $mission->save();
            } catch (PropelException $exception) {
                echo 'Already saved';
                continue;
            }

            if (!$mission->getConfirmed()) {
                $notif = new Notification\MailNotification($mission, $user);
                if ($notif::$hasAttachmentAbility) {
                    $notif->attach($fetcher->attachment($mission->getId(), $mission->getType()));
                }

                try {
                    $notif->send();
                } catch (NotificationException $exception) {
                }
            }
        }
    }
}
