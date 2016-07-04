<?php

namespace Front;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use Sabre\VObject\Component\VCalendar as Calendar;
use Sabre\VObject\Component\VEvent as Event;
use Sabre\VObject\Component\VAlarm as Alarm;

use Data\User;
use Data\UserQuery;
use Data\Mission;
use Data\MissionQuery;

/**
 * Routes
 * @param \Slim\App $app
 */
function routes(\Slim\App $app)
{
    $app->get('/', function (Request $request, Response $response, $args) {
        // Sample log message
        $this->logger->info("Slim-Skeleton '/' route");

        // Render index view
        return $this->view->render($response, 'index.phtml', $args);
    })->setName('home');

    $app->group('/user', function () {
        $this->map(['DELETE', 'PUT'], '', function (Request $request, Response $response, $args) {
        })->setName('user');

        $this->get('/add', function (Request $request, Response $response, $args) {
        })->setName('user-add');

        $this->get('/delete', function (Request $request, Response $response, $args) {
        })->setName('user-delete');
    });

    $app->group('/mission', function () {
        $this->map(['DELETE', 'PUT'], '', function (Request $request, Response $response, $args) {
        })->setName('mission');

        $this->get('/accept', function (Request $request, Response $response, $args) {
        })->setName('mission-accept');

        $this->get('/delete', function (Request $request, Response $response, $args) {
        })->setName('mission-delete');
    });

    $app->get('/calendar/{user}', function (Request $request, Response $response, $args) {
        $user = UserQuery::create()
                    ->findOneByName($args['user']);
        $missions = MissionQuery::create()
            ->filterByUserId($user->getId())
            ->orderByDate()
            ->find();
        $calendar = new Calendar();
        foreach ($missions as $mission)
        {
            $start = clone $mission->getDate();
            $start->setTime(
                    $mission->getStart()->format('G'),
                    $mission->getStart()->format('i')
                );
            $end = clone $start;
            $end->add($mission->getStart()->diff($mission->getEnd(), true));
        	$event = $calendar->add('VEVENT', [
                'SUMMARY' => $mission->getName(),
                'DESCRIPTION' => '',
                'STATUS' => 'CONFIRMED',
                'DTSTART' => $start,
                'DTEND' => $end,
                'LOCATION' => $mission->getName(),
                'ATTENDEE' => 'mailto:'.$user->getMail()
            ]);
            $event->add(
                'ORGANIZER',
                'mailto:noreply@cityjunior.clmb.fr',
                [
                   'CN'   => 'City Junior'
                ]
            );
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
        $response = $response->withHeader('Content-type', 'text/calendar; charset=utf-8');
        $response->write($calendar->serialize());
        return $response;
    })->setName('calendar');
}
