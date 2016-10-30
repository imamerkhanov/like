<?php
/**
 * @link https://github.com/imamerkhanov
 * @author Ilshat Amerkhanov
 */
use imamerkhanov\like;

class LikeController
{
    public $settings;

    public function init()
    {
        $this->settings = InstaLike::find()->one();
        parent::init();
    }

    public function actionIndex($debug = false)
    {
        if ($this->settings !== null and $this->settings->on == 1) {
            $login = \Yii::$app->security->decryptByKey(base64_decode($this->settings->login), \Yii::$app->params['fileEncryptKey']);
            $pass = \Yii::$app->security->decryptByKey(base64_decode($this->settings->password), \Yii::$app->params['fileEncryptKey']);

            $likeOff = new LikeOff(
                [
                    'url'      => 'https://example.com/api',
                    'login'    => $login,
                    'password' => $pass,
                    'debug'    => $debug
                ]
            );

            if(!empty($likeOff->profile) and isset($likeOff->profile->balance))
            {
                $this->settings->count = $likeOff->profile->balance;
                $this->settings->save();
            }


            try {
                if (!empty($likeOff->user)) {
                    $likeOff->send('exchange.getTaskList', ['account_id' => $likeOff->user['account_id']]);
                    $Task = Json::decode($likeOff->response->content);

                    if (!empty($Task['response']['list']))
                        foreach ($Task['response']['list'] as $t) {
                            $likeOff->send('exchange.setLike', ['account_id' => $likeOff->user['account_id'], 'task_id' => $t['task_id']]);
                            $like = Json::decode($likeOff->response->content);
                            if ($like['status'] != 'ok')
                                break;
                        }
                }
            } catch (\Exception $e) {
                echo  $e->getMessage() . " код - " . $e->getCode();
            }

        }

    }
}
