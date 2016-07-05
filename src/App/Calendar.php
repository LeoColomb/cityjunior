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
            'SUMMARY' => $mission->getName(),
            'DESCRIPTION' => 'Mission City Junior'."\n\n".
                                '  • Type : '.$mission->getType()."\n".
                                '  • Date : '.$mission->getDateFormatted()."\n".
                                '  • Départ : '.$mission->getName()."\n".
                                '  • Début : '.$mission->getStartFormatted()."\n".
                                '  • Fin : '.$mission->getEndFormatted()."\n".
                                '  • Arrivée : '.$mission->getArrival()."\n",
            'STATUS' => 'CONFIRMED',
            'DTSTART' => $start,
            'DTEND' => $end,
            'LOCATION' => $mission->getName(),
            'ATTENDEE' => 'mailto:'.$this->user->getMail()
        ]);
        $event->add('ORGANIZER',
            'mailto:noreply@cityjunior.clmb.fr',
            [
                'CN'   => 'City Junior'
            ]);
        $event->add('VALARM', [
            'ACTION' => 'DISPLAY',
            'TRIGGER' => '-PT10M',
            'DESCRIPTION' => 'Prise de poste'
        ]);
        $event->add('VALARM', [
            'ACTION' => 'DISPLAY',
            'TRIGGER' => $mission->getType() == 'Astreinte' ? '-PT30M' : '-PT1H',
            'DESCRIPTION' => 'Réveil'
        ]);
    }
}
