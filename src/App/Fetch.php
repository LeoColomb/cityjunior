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
     * @throws \Exception
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $stack = HandlerStack::create();
        $stack->push(
            Middleware::log(
                new Logger('HTTP', [new StreamHandler(LOG_FILE, LOG_LEVEL)]),
                new MessageFormatter('"{method} {target} HTTP/{version}" {code}'.$this->user->getName())
            )
        );
        $this->client = new Client([
            'base_uri' => 'http://cityjunior.lesitedupersonnel.fr/personnel/personnel/',
            'cookies' => true,
            'headers' => ['User-Agent' => 'CityJuniorApp/1.0 (Leo Colombaro Plateform) Mozilla/5.0 (Linux) AppleWebKit/537.36 (KHTML, like Gecko)'],
            'handler' => $stack,
            ]);
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
        if ($response->getBody()->getSize() < 1000) {
            throw new \Exception('Connection refused, wrong ids');
        }
    }

    /**
     * Fetch.
     */
    public function fetch()
    {
        $missions = $this->doFetch();
        $now = new \DateTime();
        if ((int) $now->format('n') < (int) $now->add(new \DateInterval('P10D'))->format('n')) {
            $missions = array_merge($missions, $this->doFetch([
                        'semaine' => $now->format('Y-n').'-1',
                        'mois' => $now->add(new \DateInterval('P10D'))->format('Y-n').'-1'
                    ])
            );
        }

        return $missions;
    }

    /**
     * [doFetch description]
     *
     * @param  array $params [description]
     * @return array
     * @throws \Exception
     */
    private function doFetch(array $params = [])
    {
        $response = $this->client->post('planet.php', [
            'form_params' => $params + [
                'nom_affichage' => 'sous_menu',
                'sous_menu' => '',
                'action' => 'inc.suiviMissionsTrain.php',
            ]
        ]);
        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Unable to fetch with anwser: '.$response->getReasonPhrase());
        }
        $body = new \DOMDocument();
        //TODO: Avoid silent operator
        @$body->loadHTML((string) $response->getBody());
        $rows = $body->getElementsByTagName('table');
        $rows = $rows->item(1);
        $rows = $rows->getElementsByTagName('tr');
        // Keys
        $keysNode = $rows->item(1)->getElementsByTagName('th');
        $keys = [];
        foreach ($keysNode as $node) {
            $keys[] = $node->nodeValue == "\xC2\xA0" ? 'Confirmee' : $node->nodeValue;
        }

        // Missions
        $missions = [];
        for ($i = 2; $i < $rows->length; ++$i) {
            $missions[] = [];
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

    public function attachment($id, $isAstreinte)
    {
        $response = $this->client->get('getPdf'.($isAstreinte ? 'Astreinte' : 'Mission').'.php', [
            'query' => [
                'num' => $id,
            ]
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new \Exception('Unable to fetch with anwser: '.$response->getReasonPhrase());
        }

        return (string) $response->getBody();
    }

    public function confirm($id, $isAstreinte)
    {
        $response = $this->client->post('ajax/confirmer'.($isAstreinte ? 'Astreinte' : 'Mission').'.php', [
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
