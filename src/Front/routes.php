<?php

namespace Front;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use App\Fetch;
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

        return $this->view->render($response, 'index.phtml', $args);
    })->setName('home');

    $app->any('/user[/{methode}]', function (Request $request, Response $response, $args) {
        $query = $request->getQueryParams();
        if ((array_key_exists('methode', $args) && $args['methode'] == 'add') || $request->isPost()) {
            if (UserQuery::create()->findOneByName($query['name'])) {
                $this->logger->debug("Already a known user", $query);

                return $response->withStatus(409);
            }
            $user = new User();
            $user
                ->setName($query['name'])
                ->setPassword($query['password'])
                ->setMail($query['mail']);
            try {
                $fetcher = new Fetch($user);
            } catch (\Exception $exception) {
                $this->logger->info($exception, $query);

                return $response->withStatus(401);
            }
            $user->save();

            return $response->withStatus(201);
        } elseif ((array_key_exists('methode', $args) && $args['methode'] == 'delete') || $request->isDelete()) {
            $user = UserQuery::create()->findOneByName($query['name']);
            if ($user) {
                $user->delete();
                return $response->withStatus(200);
            }

            return $response->withStatus(404);
        }

        return $response->withStatus(404);
    })->setName('user');

    $app->group('/mission', function () {
        $this->map(['DELETE', 'POST'], '', function (Request $request, Response $response, $args) {
        })->setName('mission');

        $this->get('/accept', function (Request $request, Response $response, $args) {
        })->setName('mission-accept');

        $this->get('/delete', function (Request $request, Response $response, $args) {
        })->setName('mission-delete');
    });

    $app->get('/calendar/{user}', function (Request $request, Response $response, $args) {
        $this->logger->info("Slim-Skeleton '/calendar' route", $args);
        $user = UserQuery::create()
                    ->findOneByName($args['user']);
        if (!$user)
        {
            $this->logger->debug("Not a known user", $args);

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
