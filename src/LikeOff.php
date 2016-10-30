<?php
/**
 * @link https://github.com/imamerkhanov
 * @author Ilshat Amerkhanov
 */
namespace imamerkhanov\like;

use yii\httpclient\Client;
use yii\base\Exception;
use yii\helpers\Json;

class LikeOff extends \yii\base\Component
{
    public $url;
    public $login;
    public $password;
    public $cookies=null;
    public $client;
    public $user;
    public $profile;
    public $response;
    public $debug = false;

    public function init()
    {
        $this->client = new Client(['baseUrl' => $this->url]);
        $this->login();

        try {
            $this->send('global.start');
        } catch (Exception $e) {
            $this->login();
            $this->send('global.start');
        }

        $data = Json::decode($this->response->content);

        if (isset($data['actions']) and is_array($data['actions']))
            foreach ($data['actions'] as $d)
                if ($d['type'] == 'account') {
                    $userData = array_values($d['data']);
                    if (!empty($userData[0]))
                        $this->user = $userData[0];
                } elseif ($d['type'] == 'profile')
                    $this->profile = (object)$d['data'];

        parent::init();
    }

    public function login()
    {
        $this->send('profile.doLogin', ['email' => $this->login, 'password' => $this->password]);
        $this->cookies = $this->response->cookies;
    }

    public function send($method, $params = [])
    {
        sleep(1);
        $params['mobile'] = 1;
        $params['method'] = $method;

        $this->response = $this->client->post('?m=' . $method, $params)->setCookies($this->cookies)->send();

        if ($this->debug)
            echo $this->url . '?m=' . $method . "\n" . $this->response->content . "\n\n";
    }

}