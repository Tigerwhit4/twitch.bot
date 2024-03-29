<?php
/**
 * Created by PhpStorm.
 * User: terazoid
 * Date: 7/8/14
 * Time: 10:53 PM
 */

namespace app\components\data;


use yii\base\InvalidConfigException;
use yii\base\InvalidParamException;
use yii\base\Object;
use Yii;

/**
 * Class EmailAddress
 *
 * @property string $name
 * @property string $domain
 * @property string $address
 */
class EmailAddress extends Object
{
    private $name;
    private $domain;

    /**
     * @param string $domain
     * @throws \yii\base\InvalidParamException
     */
    public function setDomain($domain)
    {
        //TODO: move dimain in separate class and add domain validation using: checkdnsrr($domain,"MX")
        if (strlen($domain) >= 255 || !preg_match('/[a-z0-9]+(\.[a-z0-9]+)+/i', $domain)) {
            throw new InvalidParamException('invalid domain name');
        }
        $this->domain = $domain;
    }

    /**
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param string $name
     * @throws \yii\base\InvalidParamException
     */
    public function setName($name)
    {
        if (!preg_match('/[a-z0-9]([\.\-]?[a-z0-9]+)+/i', $name)) {
            throw new InvalidParamException('invalid email user name');
        }
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $address
     * @throws \yii\base\InvalidParamException
     */
    public function setAddress($address)
    {
        $addressParts = explode('@', $address);
        if (2 !== count($addressParts)) {
            throw new InvalidParamException('Invalid email address');
        }
        $this->setName($addressParts[0]);
        $this->setDomain($addressParts[1]);
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return "{$this->name}@{$this->domain}";
    }


    public function __construct($config = [])
    {
        parent::__construct($config);
        if (!isset($this->name) || !isset($this->domain)) {
            throw new InvalidConfigException("Email isn't set");
        }
    }

    public function beforeSave($insert)
    {
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    public static function populateGmailEmails(EmailAddress $baseAddress)
    {
        $result = [];
        $maxDotsCount = strlen($baseAddress->name) - 1;
        for($i=1,$j=1<<($maxDotsCount); $i<$j; $i++){
            $dots = decbin($i);
            $resultName = $baseAddress->name[0];
            for($ii = strlen($dots)-$maxDotsCount, $jj = strlen($dots), $kk = 1; $ii < $jj; $ii++,$kk++){
                if($ii>=0&&$dots[$ii]=='1'){
                    $resultName.='.';
                }
                $resultName.=$baseAddress->name[$kk];
            }
            $result[] = new EmailAddress(['name'=>$resultName, 'domain'=>$baseAddress->domain]);
        }
        return $result;
    }
}
