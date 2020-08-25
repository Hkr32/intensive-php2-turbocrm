<?php
namespace frontend\models;
use frontend\validators\RemoteEmailValidator;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends \common\models\User implements IdentityInterface
{
    public $password_repeat;

    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    public function attributeLabels()
    {
        return [
            'email' => 'Электронная почта',
            'phone' => 'Номер телефона',
            'company' => 'Название компании',
            'password' => 'Пароль',
            'password_repeat' => 'Повтор пароля',
        ];
    }

    public function rules()
    {
        return [
            [['company', 'phone', 'email', 'password', 'password_repeat'], 'safe'],
            [['company', 'phone', 'email', 'password', 'password_repeat'], 'required'],
            ['email', 'email'],
            ['email', 'unique'],
            ['email', RemoteEmailValidator::class],
            ['phone', 'match', 'pattern' => '/^[\d]{11}/i',
                'message' => 'Номер телефона должен состоять из 11 цифр'],
            ['company', 'string', 'min' => 3],
            ['password', 'string', 'min' => 8],
            ['password', 'compare']
        ];
    }

}
