<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "accounts".
 *
 * @property string $id
 * @property string $login
 * @property string $password
 * @property string $cookies
 */
class Accounts extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'accounts';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['login', 'password'], 'required'],
            [['login', 'password'], 'string', 'max' => 64],
            [['cookies'], 'string', 'max' => 256]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'login' => 'Login',
            'password' => 'Password',
            'cookies' => 'Cookies',
        ];
    }

}
