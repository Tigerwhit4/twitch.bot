<?php

namespace app\controllers;

use app\components\data\EmailAddress;
use yii\helpers\VarDumper;

class UsersController extends \yii\web\Controller
{
    public function actionRegister()
    {
        return $this->render('register');
    }

}
