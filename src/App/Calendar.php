<?php

namespace App;

use Sabre\VObject\Component\VCalendar;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

use Data\Mission;
use Data\User;

class Calendar
{
    private $logger;

    private $user;

    private $calendar;

    /**
     * Summary of __construct
     * @param \Data\Mission|array $missions
     * @param \Data\User $user
     */
    public function __construct($missions, User $user)
    {
        $this->logger = new Logger('APP.CALENDAR');
        $this->logger->pushHandler(new StreamHandler(LOG_FILE, LOG_LEVEL));

        $this->user = $user;

        $this->calendar = new VCalendar([
            'X-WR-TIMEZONE' => date_default_timezone_get()
        ]);

        $timezone = \Sabre\VObject\Reader::read(fopen(__DIR__.'/files/Paris.ics','r'));
        $this->calendar->add($timezone->VTIMEZONE);

        if ($missions instanceof \Propel\Runtime\Collection\ObjectCollection) {
            $this->calendar->add('X-WR-CALNAME', 'City Junior '.$this->user->getName());
            foreach ($missions as $mission)
            {
                $this->addEvent($mission);
            }
        } else {
            $this->addEvent($missions);
        }
    }

    public function __toString()
    {
        return $this->calendar->serialize();
    }

    private function addEvent(Mission $mission)
    {
        $this->logger->debug("Creating VEVENT composent", [
            'user' => $this->user->getName(),
            'mission' => $mission->getID()
        ]);
        $start = clone $mission->getDate();
        $start->setTime(
                $mission->getStart()->format('G'),
                $mission->getStart()->format('i')
            );
        $end = clone $start;
        $end->add($mission->getStart()->diff($mission->getEnd(), true));
        $event = $this->calendar->add('VEVENT', [
            'SUMMARY' => $mission->getName().
                            ($mission->isAstreinte() ? '' : ' → '.$mission->getArrival()),
            'DESCRIPTION' => 'Mission City Junior'."\n\n".
                                '  • Type : '.$mission->getType()."\n".
                                '  • Date : '.$mission->getDateFormatted()."\n".
                                '  • Départ : '.$mission->getName()."\n".
                                '  • Début : '.$mission->getStartFormatted()."\n".
                                '  • Fin : '.$mission->getEndFormatted()."\n".
                                ($mission->isAstreinte() ?
                                '  • Disponibilité : '.$mission->getArrival()
                                :
                                '  • Arrivée : '.$mission->getArrival()."\n".
                                '  • Train : '.$mission->getTrain()."\n\n".
                                'Info trafic : '.$mission->getLink()
                                ),
            'CATEGORIES' => $mission->getType(),
            'STATUS' => 'CONFIRMED',
            'DTSTART' => $start,
            'DTEND' => $end,
            'LOCATION' => 'Gare '.$mission->getName()
        ]);
        $event->add('ATTENDEE',
            'mailto:'.$this->user->getMail(),
            [
                'CN' => $this->user->getName()
            ]);
        $event->add('ORGANIZER',
            'mailto:noreply@cityjunior.clmb.fr',
            [
                'CN' => 'City Junior'
            ]);
        $event->add('VALARM', [
            'ACTION' => 'DISPLAY',
            'TRIGGER' => '-P0DT1H0M0S',
            'DESCRIPTION' => 'Réveil'
        ]);
        $event->add('VALARM', [
            'ACTION' => 'DISPLAY',
            'TRIGGER' => '-P0DT0H10M0S',
            'DESCRIPTION' => 'Prise de poste'
        ]);
        if (!$mission->isAstreinte()) {
            $event->add('VALARM', [
                'ACTION' => 'DISPLAY',
                'TRIGGER' => 'P0DT0H20M0S',
                'DESCRIPTION' => 'Embarquement'
            ]);
        }
    }
}
