<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "emails".
 *
 * @property string $id
 * @property string $email
 * @property integer $status
 */
class Emails extends \yii\db\ActiveRecord
{
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
}
