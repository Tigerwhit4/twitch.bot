<?php

namespace app\models;

use app\components\data\StringParser;
use yii\base\Exception;
use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "mts_sessions".
 *
 * @property string $id
 * @property string $phpsessid
 * @property string $token_value
 * @property string $token_name
 * @property string $captcha
 */
class MtsSessions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mts_sessions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['phpsessid', 'token_value', 'token_name', 'captcha'], 'required'],
            [['phpsessid', 'token_name'], 'string', 'max' => 32],
            [['token_value', 'captcha'], 'string', 'max' => 16],
            [['phpsessid'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'phpsessid' => 'Phpsessid',
            'token_value' => 'Token Value',
            'token_name' => 'Token Name',
            'captcha' => 'Captcha',
        ];
    }

    public function getImageUrl()
    {
        return Url::to(['mts-sms/image','phpsessid'=>$this->phpsessid]);
    }

    public static function createNew()
    {
        /**
         * @var MtsSessions $result
         */
        $result = new static();
        $content = file_get_contents("http://www.mts.com.ua/ukr/sendsms.php");
        $result->phpsessid = StringParser::getStringBetween(
            '/back/modules/golden/captcha.php?PHPSESSID=',
            '&',
            $content
        );
        if (!preg_match('/<input type="hidden" name=\'([a-z0-9]{32})\' value="([0-9]+)" >/i', $content, $matches)) {
            \yii\helpers\VarDumper::dump($content, 10, true);
            throw new Exception("tokens name and value not found");
        }
        $result->token_name = $matches[1];
        $result->token_value = $matches[2];
        return $result;
    }

    public function sendMessage($phone, $message)
    {
        $ch = curl_init("http://www.mts.com.ua/back/modules/sms/db_sms.php");
        $proneCode = substr($phone, 0, 5);
        $phoneNumber = substr($phone, 5);
        $post = [
            'script' => '/ukr/sendsms.php',
            'sms_tag_id' => 3,
            'network1' => $proneCode,
            'phone1' => $phoneNumber,
            'message' => $message,
            'lang' => 'lat',
            'captcha' => $this->captcha,
        ];
        $post[$this->token_name] = $this->token_value;
        curl_setopt_array(
            $ch,
            [
                CURLOPT_POST => true,
                CURLOPT_REFERER => "http://www.mts.com.ua/ukr/sendsms.php",
                CURLOPT_USERAGENT => "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:29.0) Gecko/20100101 Firefox/29.0",
                CURLOPT_FOLLOWLOCATION => false,
                CURLOPT_HEADER => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS => http_build_query($post),
                CURLOPT_COOKIE => "lang=ukr; selected_data=1; PHPSESSID={$this->phpsessid}",
                CURLOPT_HEADER => [
                    "Content-Type: application/x-www-form-urlencoded",
                ],
            ]
        );
        $resp = curl_exec($ch);
        if (false === strpos($resp, "/ukr/sendsms.php?sms_message=2")) {
            throw new Exception("Invalid response:\n" . $resp);
        }
        $this->delete();
    }
}

/*
 CREATE TABLE IF NOT EXISTS `mts_sessions` (
`id` int(10) unsigned NOT NULL,
  `phpsessid` char(32) COLLATE utf8_unicode_ci NOT NULL,
  `token_value` char(16) COLLATE utf8_unicode_ci NOT NULL,
  `token_name` char(32) COLLATE utf8_unicode_ci NOT NULL,
  `captcha` char(16) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
 */
