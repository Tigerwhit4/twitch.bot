<?php

namespace app\controllers;

use app\models\MtsSessions;
use yii\base\InvalidParamException;

class MtsSmsController extends \yii\web\Controller
{
    public function actionGenerate()
    {
        if (\Yii::$app->request->isPost) {
            $newModel = new MtsSessions();
            $newModel->load($_POST, 'MtsSessions');
            if (!$newModel->save()) {
                throw new InvalidParamException("Can't save session. " . json_encode(
                        $newModel->firstErrors,
                        JSON_PRETTY_PRINT
                    ));
            }
            //\Yii::$app->response->refresh()->send();
        }
        $model = MtsSessions::createNew();
        return $this->render(
            'generate',
            [
                'model' => $model,
            ]
        );
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionSend($count = 1)
    {
        for ($i = 0; $i < $count; $i++) {
            /**
             * @var MtsSessions $sms
             */
            $sms = MtsSessions::find()->one();
            if (null === $sms) {
                return "no sessions";
            }
            $sms->sendMessage("380956157263", "test $i");
            //return $this->render('send');
        }
        return "done";
    }

    public function actionImage($phpsessid)
    {
        $ch = curl_init("http://www.mts.com.ua/back/modules/golden/captcha.php?PHPSESSID=" . urlencode($phpsessid));
        curl_setopt_array(
            $ch,
            [
                CURLOPT_REFERER => "http://www.mts.com.ua/ukr/sendsms.php",
                CURLOPT_USERAGENT => "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:29.0) Gecko/20100101 Firefox/29.0",
                CURLOPT_FOLLOWLOCATION => false,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_COOKIE => "lang=ukr; selected_data=1; PHPSESSID={$phpsessid}",
            ]
        );
        header("Content-Type: image/jpeg");
        return curl_exec($ch);
    }

}
