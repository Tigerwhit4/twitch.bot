<?php

namespace app\controllers;

class UsersController extends \yii\web\Controller
{
    public function actionRegister()
    {
        return $this->render('register');
    }

}
