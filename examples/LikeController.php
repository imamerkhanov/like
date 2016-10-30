<?php
use imamerkhanov\like\LikeOff;
use yii\helpers\Json;

/**
 * @link https://github.com/imamerkhanov
 * @author Ilshat Amerkhanov
 */

class LikeController
{
    public function actionIndex($debug = false)
    {
            $login = '';
            $pass = '';

            $likeOff = new LikeOff(
                [
                    'url'      => 'https://example.com/api',
                    'login'    => $login,
                    'password' => $pass,
                    'debug'    => $debug
                ]
            );

            try {
                if (!empty($likeOff->user)) {
                    $likeOff->send('exchange.getTaskList', ['account_id' => $likeOff->user['account_id']]);
                    $Task = Json::decode($likeOff->response->content);
                    var_dump($Task);
                }
            } catch (\Exception $e) {
                echo  $e->getMessage() . " код - " . $e->getCode();
            }

    }
}
