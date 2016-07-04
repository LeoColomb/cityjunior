<?php

namespace App;

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Client;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Data\User;

class Fetch
{
    /**
     * Summary of $user.
     *
     * @var \Data\User
     */
    protected $user;

    /**
     * Web client.
     *
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * Login.
     *
     * @param \Data\User $user
     */
    public function __construct(User $user)
    {
        $stack = HandlerStack::create();
        $stack->push(
            Middleware::log(
                new Logger('HTTP', [new StreamHandler(LOGGER_FILE, Logger::DEBUG)]),
                new MessageFormatter('{req_body} - {res_body}')
            )
        );
        $this->client = new Client([
            'base_uri' => 'http://cityjunior.lesitedupersonnel.fr/personnel/personnel/',
            'cookies' => true,
            'headers' => ['User-Agent' => 'CityJuniorApp/1.0 (Leo Colombaro Plateform) Mozilla/5.0 (Linux) AppleWebKit/537.36 (KHTML, like Gecko)'],
            'handler' => $stack,
            ]);
        $this->user = $user;
        $response = $this->client->post('index.php', [
            'form_params' => [
                'login' => $this->user->getName(),
                'pass' => $this->user->getPassword(),
                'action' => 'Connexion',
            ]
        ]);
        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Unable to login with anwser: '.$response->getReasonPhrase());
        }
        if ($response->getHeader('Content-Length') < 460) {
            throw new \Exception('Connection refused, wrong ids');
        }
    }

    /**
     * Fetch.
     */
    public function fetch()
    {
        $response = $this->client->post('planet.php', [
            'form_params' => [
                'nom_affichage' => 'sous_menu',
                'sous_menu' => '',
                'action' => 'inc.suiviMissionsTrain.php',
            ]
        ]);
        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Unable to fetch with anwser: '.$response->getReasonPhrase());
        }
        $body = new \DOMDocument();
        $body->loadHTML((string) $response->getBody());
        $rows = $body->getElementsByTagName('table');
        $rows = $rows->item(1);
        $rows = $rows->getElementsByTagName('tr');
        // Keys
        $keysNode = $rows->item(1)->getElementsByTagName('th');
        $keys = null;
        foreach ($keysNode as $node) {
            $keys[] = $node->nodeValue == "\xC2\xA0" ? 'Confirmee' : $node->nodeValue;
        }

        // Missions
        $missions = null;
        for ($i = 2; $i < $rows->length; ++$i) {
            $missions[] = null;
            $missions[$i - 2]['ID'] = $rows
                ->item($i)
                ->getElementsByTagName('input')
                ->item(0)
                ->attributes
                ->getNamedItem('name')
                ->nodeValue;
            $missionsNodes = $rows->item($i)->getElementsByTagName('td');
            $j = 0;
            foreach ($missionsNodes as $node) {
                $missions[$i - 2][$keys[$j]] = $node->nodeValue;
                ++$j;
            }
        }

        return $missions;
    }

    public function attachment($id, $type)
    {
        $type = $type == 'Astreinte' ? $type : 'Mission';
        $response = $this->client->get('getPdf'.$type.'.php', [
            'query' => [
                'num' => $id,
            ]
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Unable to fetch with anwser: '.$response->getReasonPhrase());
        }

        return (string) $response->getBody();
    }

    public function confirm($id, $type)
    {
        $type = $type == 'Astreinte' ? $type : 'Mission';
        $response = $this->client->post('ajax/confirmer'.$type.'.php', [
            'form_params' => [
                'date' => round(microtime(true) * 1000),
                'mission' => $id,
            ]
        ]);
        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Unable to fetch with anwser: '.$response->getReasonPhrase());
        }
        if ((string) $response->getBody() == 'Erreur') {
            throw new \Exception('Server Error');
        }
    }

    /**
     * Logout.
     */
    public function __destruct()
    {
        $response = $this->client->get('logout.php');
        if ($response->getStatusCode() !== 200) {
            //throw new Exception('Unable to logout with anwser: ' . $response->getReasonPhrase());
        }
    }
}
