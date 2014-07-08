<?php

namespace app\models;

use app\components\data\EmailAddress;
use yii\base\InvalidParamException;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "emails".
 *
 * @property string $id
 * @property string $email
 * @property integer $status
 */
class Emails extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_USED = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'emails';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email'], 'required'],
            [['status'], 'integer'],
            [['email'], 'string', 'max' => 128],
            [['email'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'status' => 'Status',
        ];
    }

    public static function addForGmail(EmailAddress $email)
    {
        if ('gmail.com' !== $email->domain) {
            throw new InvalidParamException('Expected gmail email');
        }
        $mails = EmailAddress::populateGmailEmails($email);
        $mails[] = $email;
        $insertMails = self::getDb()->createCommand();
        $addresses = ArrayHelper::getColumn($mails, function($obj){return [$obj->address];});
        $insertMails->batchInsert(self::tableName(), ['email'], $addresses);
        $insertMails->execute();
    }
}
