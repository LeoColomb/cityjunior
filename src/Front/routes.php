<?php

namespace Front;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use App\Calendar;

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
        $this->logger->info("Slim-Skeleton '/calendar' route", ['user' => $args['user']]);
        $user = UserQuery::create()
                    ->findOneByName($args['user']);
        if (!$user)
        {
            $this->logger->debug("Not a known user", ['user' => $args['user']]);
        	return false;
        }
        $missions = MissionQuery::create()
            ->filterByUserId($user->getId())
            ->orderByDate()
            ->find();

        $response = $response->withHeader('Content-type', 'text/calendar; charset=utf-8');
        $response->write((string) new Calendar($missions, $user));
        return $response;
    })->setName('calendar');
}
