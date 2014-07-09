<?php
/**
 * Created by PhpStorm.
 * User: terazoid
 * Date: 7/9/14
 * Time: 12:33 AM
 */

namespace app\controllers;


use app\components\data\EmailAddress;
use app\models\Emails;
use yii\data\ActiveDataProvider;
use yii\web\Response;

class EmailsController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $activeEmails = new ActiveDataProvider([
            'query' => Emails::findActive(),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
        return $this->render(
            'index',
            [
                'emailModel' => new Emails(),
                'activeEmails' => $activeEmails,
            ]
        );
    }

    public function actionCreate()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        $emails = new Emails();
        $emails->load(\Yii::$app->request->post());

        Emails::addForGmail(new EmailAddress(['address' => $emails->email]));
    }
} 
